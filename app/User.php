<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'google_id', 'facebook_id', 'github_id', 'twitter_id', 'linkedin_id', 'gitlab_id', 'bitbucket_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function getListUser($page) {
        $take = 10;
        $skip = 0;
        if ($page > 1) {
            $skip = $take * ($page - 1);
        }
        $model = User::orderBy('updated_at', 'desc')->skip($skip)->take($take);
        return $model->get();
    }
}
