<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChallengeSubmission extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'challenge_id', 'user_id', 'submission_url', 'repository_url', 'description', 'status', 'point'
    ];

    /**
     * The model's default values for attributes.
     * 
     * @var array
     */
    protected $attributes = [
        'status' => 'notseen', //notseen, accepted, needrevision
    ];
}
