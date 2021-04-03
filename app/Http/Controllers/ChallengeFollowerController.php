<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Models\ChallengeFollower;

class ChallengeFollowerController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['get']]);
    }

    public function get(Request $request) {
        $id = intval($request['id']);
        $per_page = 15;
        if($request['per_page']) {
            $per_page = intval($request['per_page']);
        }

        $challenge = Challenge::find($id);

        if(!$challenge) {
            return $this->sendNotFound('Challenge not found!');
        }

        $challengeFollower = ChallengeFollower::where('challenge_id', $request['id'])->paginate($per_page);

        if ($challengeFollower) {
            return $this->sendPaginate($challengeFollower);
        }

        return $this->sendNotFound();
    }

    public function follow(Request $request) {
        $challenge_id = intval($request['id']);
        $user_id = auth()->user()->id;

        $challengeFollower = ChallengeFollower::where(['challenge_id' => $challenge_id, 'user_id' => $user_id])->first();

        if($challengeFollower) {
            return $this->sendForbidden('You cannot re following same challenge!');
        }

        $challenge = Challenge::find($challenge_id);

        if(!$challenge) {
            return $this->sendNotFound('Challenge not found!');
        }

        $data = [
            'challenge_id' => $challenge_id,
            'user_id' => $user_id
        ];

        $challenge = ChallengeFollower::create($data);
  
        return $this->sendCreated($challenge, "Successfully folowed the challenge");
    }

    public function unfollow(Request $request) {
        $challenge_id = intval($request['id']);
        $user_id = auth()->user()->id;

        $challengeFollower = ChallengeFollower::where(['challenge_id' => $challenge_id, 'user_id' => $user_id])->first();

        if(!$challengeFollower) {
            return $this->sendForbidden('You you haven`t following this challenge!');
        }

        try {
            $challenge = ChallengeFollower::where('id', $challengeFollower->id)->delete();
            return $this->sendSuccess($challenge, 'Success unfollowing this challenge.');
        } catch (\Throwable $th) {
            return $this->sendNotFound('Failed to unfollow this challenge!');
        }
    }
}
