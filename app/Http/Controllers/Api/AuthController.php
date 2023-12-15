<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{

    public function newUser(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'password' => 'required|min:5',
            'email' => 'required|email|unique:users'
        ], [
            'name.required' => 'Name field is required.',
            'password.required' => 'Password field is required.',
            'email.required' => 'Email field is required.',
            'email.email' => 'Email field must be email address.',
            'email.unique' => 'Email Already Register Kindly Enter another one'
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['role'] = '1';
        $user = User::create($validatedData);
        if ($user) {
            return response()->json([
                'status' => true,
                'api_token' => $user->createToken("API TOKEN")->plainTextToken,
                'message' => 'User Registered Successfully...',
                'data' => $user
            ], 200);
        }
    }
    public function login(Request $request)
    { 
        $validatedData = $request->validate([
            'password' => 'required|min:8',
            'email' => 'required|email'
        ], [
            'password.required' => 'Password field is required.',
            'email.required' => 'Email field is required.',
            'email.email' => 'Email field must be email address.',
        ]);
        $validatedData['status'] = '1';
     //   $validatedData['role'] = '0';
        if ($auth_login = Auth::attempt($validatedData)) {
            // $request->session()->regenerate();
            if($auth_login == true){
                $user = User::where('email', $request->email)->first();
                return response()->json([
                    'name' => $user->name,
                    'email' => $user->email,
                    'api_token' => $user->createToken("API TOKEN")->plainTextToken,
                    'userdata' => $user
                ], 200);
            }   
            else {
                return response()->json([
                    'errors' => ['Invalid User Login Email or Password, kindly Login Again..'],
                ], 401);
            }     
        } else {
            return response()->json([
                'errors' => ['Invalid User Login Email or Password, kindly Login Again..'],
            ], 401);
        }
    }
    public function verify_token(Request $request)
    {
        $sanctums=$request->user();
        if ($request->api_token != null) {
            return response()->json([
               // 'data'=> $sanctums,
                'name'=>$sanctums->name,
                'email'=>$sanctums->email,
                'api_token'=>$request->api_token,
            ], 200);

        } else {
            return false;
        }
    }
    public function checkResetToken(Request $request){
        

        $count = DB::table('Password_reset_tokens')->where('email',$request->email)->count();
        if($count == 0){
            return response()->json([
                'errors' => ['Invalid Token'],
            ], 200);
        }
        
     
    }
    public function forgotPassword(Request $request)
    {
       
        try {
            $response = Password::sendResetLink($request->only('email'));
           
            switch ($response) {
                case Password::RESET_LINK_SENT:
                    return response()->json([
                        'success' => ['true'],
                    ], 200);
                case Password::INVALID_USER:
                    return response()->json([
                        'errors' => ['Invalid User...'],
                    ], 200);
            }
        } catch (\Throwable $e) {
            dd($e);
            return response()->json([
                'errors' => ['Email Sending Problem'],
            ], 200);
        }
    }
    
    public function resetPassword(Request $request){
        //  dd($request->all());
        try {

            $response = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), 
                function($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();
                    $user->tokens()->delete();

                    event(new PasswordReset($user));
                   
                }
            );
            switch ($response) {
                case Password::PASSWORD_RESET:
                    // $authUser=Auth::user();
                    // dd($authUser);
                    return response()->json([
                        'success' => ['true'],
                    ], 200);
               
                case Password::INVALID_USER:
                    return response()->json([
                        'errors' => ['Invalid User Kindly try Again Leter.'],
                    ], 200);
                    case Password::INVALID_TOKEN:
                        return response()->json([
                            'errors' => ['Your Token is Unauthorized.'],
                        ], 200);
            }
        } catch (\Throwable $e) {
            dd($e);
            return  $this->response()->json(false, [], $e->getMessage());
        }
    }


    public function sendPasswordResetLinkEmail(Request $request) {
		$request->validate(['email' => 'required|email']);

		$status = Password::sendResetLink(
			$request->only('email')
		);

		if($status === Password::RESET_LINK_SENT) {
			return response()->json(['message' => __($status)], 200);
		} else {
			throw ValidationException::withMessages([
				'email' => __($status)
			]);
		}
	}
}
