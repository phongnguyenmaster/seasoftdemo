<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Socialite;
use Auth;
use Exception;
use App\User;
use Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public function redirectToSocial($socialType)
    {
        $validator = Validator::make(array('socialType' => $socialType), [
            'socialType' => 'required|in:google,facebook,github',
        ]);
        if ($validator->fails()) {
            return abort(404);
        }
        return Socialite::driver($socialType)->redirect();
    }
    public function handleSocialCallback($socialType)
    {
        $socialCol = $socialType . '_id';
        $userSocical = Socialite::driver($socialType)->user();
        $findUserBySocialId = User::where($socialCol, $userSocical->id)->first();
        if ($findUserBySocialId) {
            Auth::login($findUserBySocialId);
        } else {
            $findUserByEmail = null;
            if ($userSocical->email) {
                $findUserByEmail = User::where('email', $userSocical->email)->first();
            }
            if ($findUserByEmail) {
                // Update google Id and login
                $findUserByEmail->$socialCol = $userSocical->id;
                $findUserByEmail->save();
                Auth::login($findUserByEmail);
            } else {
                $userNew = new User;
                $userNew->name = $userSocical->name;
                $userNew->email = $userSocical->email;
                $userNew->$socialCol = $userSocical->id;
                $userNew->password = bcrypt($userSocical->id);
                $userNew->save();
                Auth::login($userNew);
            }
        }
        return redirect('/')->with('status', 'You are logged in!');
    }
}
