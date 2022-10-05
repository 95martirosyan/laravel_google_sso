<?php

namespace App\Http\Controllers\Auth;

use App\Interfaces\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a Traits
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    private UserRepositoryInterface $userRepository;

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
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->middleware('guest')->except('logout');
        $this->userRepository = $userRepository;
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {

        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }

        // check if they're an existing user
        $existingUser = $this->userRepository->getUserByEmail($user->email);

        if($existingUser){
            // log user in
            auth()->login($existingUser, true);
        } else {
            // create user and log in
            $log = $this->userRepository->createUser([
                'name'            => $user->name,
                'email'           => $user->email,
                'google_id'       => $user->id,
                'avatar'          => $user->avatar,
            ]);
            auth()->login($log, true);
        }
        return redirect()->to('/home');
    }
}
