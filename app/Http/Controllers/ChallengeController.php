<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Challenge;
use Validator;

class ChallengeController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['index', 'get']]);
    }

    public function index(Request $request) {
        $per_page = 15;
        if($request['per_page']) {
            $per_page = intval($request['per_page']);
        }

        $status = $request['status'];

        if ($status) {
            $data = Challenge::where('status', $status)->paginate($per_page);
        } else {
            $data = Challenge::paginate($per_page);
        }

        return $this->sendPaginate($data);
    }

    public function get(Request $request) {
        $data = Challenge::where('id', $request['id'])->first();

        if ($data) {
            return $this->sendSuccess($data, 'Challenge available');
        }

        return $this->sendNotFound();
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'start_at' => 'required',
            'deadline_at' => 'required'
        ]);
        
        if($validator->fails()) {
            return $this->sendUnprocessedEntity($validator->errors());
        }

        $data = $request->all();
        $data['user_id'] = auth()->user()->id;

        try {
            $challenge = Challenge::create($data);
            return $this->sendCreated($challenge);
        } catch (\Throwable $th) {
            return $this->sendBadRequest('Failed to create challenge');
        }
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
            'start_at' => 'required',
            'deadline_at' => 'required'
        ]);

        if($validator->fails()) {
            return $this->sendUnprocessedEntity($validator->errors());
        }

        $id = $request['id'];
        $data = $request->only(['title', 'description', 'start_at', 'deadline_at']);
        $data['user_id'] = auth()->user()->id;

        try {
            $challenge = Challenge::find($id)->update($data);
            return $this->sendSuccess($challenge, 'Success updated.');
        } catch (\Throwable $th) {
            return $this->sendForbidden();
        }

       
    }

    public function delete(Request $request) {
        $id = $request['id'];

        try {
            $challenge = Challenge::find($id)->delete();
            if($challenge) {
                return $this->sendSuccess([], 'Success deleting this challenge.');
            }
        } catch (\Throwable $th) {
            return $this->sendNotFound('Failed to delete this challenge!');
        }   
    }
}
