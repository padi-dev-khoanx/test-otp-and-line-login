<?php

namespace App\Http\Controllers;

use App\Services\SocialAccountService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Twilio\TwiML\Voice\Redirect;

class SocialAuthController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('line')->redirect();
    }

    public function handleProviderCallback()
    {
        $user = Socialite::driver('line')->user();
        $authUser = $this->findOrCreateUser($user);
        Auth::login($authUser, true);
        return \redirect()->route('home');
    }

    public function findOrCreateUser($user)
    {
        $authUser = User::where('line_id', $user->id)->first();
        if (!empty($authUser)) {
            return $authUser;
        }
        return User::create([
            'name'     => $user->name,
            'line_id'     => $user->id,
            'isVerified'    => 1,
        ]);
    }
}
