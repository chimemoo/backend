<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Models\ChallengeSubmission;
use Validator;

class ChallengeSubmissionController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['get']]);
    }

    public function get(Request $request) {
        $id = $request['id'];
        $per_page = 15;
        if($request['per_page']) {
            $per_page = intval($request['per_page']);
        }

        $challenge = Challenge::find($id);

        if(!$challenge) {
            return $this->sendNotFound('Challenge not found!');
        }

        $challengeSubmission = ChallengeSubmission::where('challenge_id', $request['id'])->paginate($per_page);

        if ($challengeSubmission) {
            return $this->sendPaginate($challengeSubmission);
        }

        return $this->sendNotFound();
    }

    public function submit(Request $request) {
        $challenge_id = intval($request['id']);
        $user_id = auth()->user()->id;

        $validator = Validator::make($request->all(), [
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendUnprocessedEntity($validator->errors());
        }

        $challenge = Challenge::find($challenge_id);

        if (!$challenge) {
            return $this->sendNotFound('Challenge not found!');
        }

        $submissionAvailable = ChallengeSubmission::where(['challenge_id' => $challenge_id, 'user_id' => $user_id])->get();

        if (count($submissionAvailable) > 0) {
            return $this->sendForbidden('You cannot re submitting this challenge!');
        }

        $data = $request->all();
        $data['user_id'] = $user_id;
        $data['challenge_id'] = $challenge_id;

        try {
            $challengeSubmission = ChallengeSubmission::create($data);
            return $this->sendCreated($challengeSubmission);
        } catch (\Throwable $th) {
            return $this->sendBadRequest('Failed to submit submission');
        }
    }

    public function update(Request $request) {
        $challenge_id = intval($request['id']);
        $user_id = auth()->user()->id;

        $validator = Validator::make($request->all(), [
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendUnprocessedEntity($validator->errors());
        }

        $challenge = Challenge::find($challenge_id);

        if (!$challenge) {
            return $this->sendNotFound('Challenge not found!');
        }

        $data = $request->all();
        $data['user_id'] = $user_id;
        $data['challenge_id'] = $challenge_id;

        try {
            $challengeSubmission = ChallengeSubmission::where(
                ['challenge_id' => $challenge_id, 'user_id' => $user_id])
                ->where('status', '!=', 'accepted')
                ->update($data);
            if ($challengeSubmission) {
                return $this->sendSuccess($data, 'Submission success updated');
            }
            return $this->sendNotFound('Challenge submission not found');
        } catch (\Throwable $th) {
            return $this->sendBadRequest('Failed to submit submission');
        }
    }

    public function unsubmit(Request $request) {
        $challenge_id = intval($request['id']);
        $user_id = auth()->user()->id;

        $challengeSubmission = ChallengeSubmission::where(['challenge_id' => $challenge_id, 'user_id' => $user_id])->first();

        if(!$challengeSubmission) {
            return $this->sendForbidden('You you haven`t submitting to this challenge');
        }

        try {
            $challenge = ChallengeSubmission::where('id', $challengeSubmission->id)->delete();
            return $this->sendSuccess($challenge, 'Success unsubmit this challenge.');
        } catch (\Throwable $th) {
            return $this->sendNotFound('Failed to unsubmit this challenge!');
        }
    }
}
