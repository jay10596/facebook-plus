<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User as UserResource;

use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;
use Validator;
use App\User;

class AuthController extends Controller
{

    public $user;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'redirectToProvider', 'handleProviderCallback']]);
    }

    public $successStatus = 200;

    public function login()
    {
        $data = request()->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if(Auth::attempt($data)) {
            return $this->responseAfterLogin();
        }

        //Inner objects are created based on manual app->Exceptions->ValidationErrorException structure for best practice.
        return response()->json(['errors' => ['meta' => ['unauthorised' => 'Incorrect Email or Password!']]], 401);
    }

    public function register(Request $request)
    {
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'city' => 'required',
            'gender' => 'required',
            'birthday' => 'required',
            'interest' => 'required',
            'about' => 'required',
            'provider_id' => '',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        $data['password'] = bcrypt($data['password']);

        User::create($data);

        return $this->login();
    }

    public function responseAfterLogin() {
        $user = auth()->user();

        $token =  $user->createToken('MyApp')->accessToken;

        return response()->json([
            'access_token' => $token,
            'name' => auth()->user()->name
        ]);
    }

    public function me()
    {
        $user = auth()->user();

        return response()->json(new UserResource($user), $this->successStatus);
    }

    public function logout(Request $request)
    {
        $user = auth()->user()->token()->revoke();

        return response()->json('Successfully logged out', $this->successStatus);
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect(); //We have to use stateless() because we are not using Laravel's default auth system
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();

        if (User::where('email', $user->email)->first()) {
            //If user already exists
            return view('passSocialiteDetails', ['email' => $user->email]);
        } else {
            //Else register User
            $user = User::create([
                'name' => ($user->nickname ?? $user->name),
                'email' => $user->email,
                'birthday' => '1996/1/1',
                'provider_id' => $user->id,
                'provider_name' => $provider,
                'password' => bcrypt('password')
            ]);

            return view('passSocialiteDetails', ['email' => $user->email]);
        }
    }
}
