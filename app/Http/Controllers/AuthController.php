<?php

namespace App\Http\Controllers;

use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|unique:users,email',
            'password'=>'required|string|confirmed'
        ]);
        $hash_to_verify = md5(time().$fields['email']);
        $user = User::create([
            'name'=>$fields['name'],
            'email'=>$fields['email'],
            'password'=>bcrypt($fields['password']),
            'email_verification_hash'=>$hash_to_verify
        ]);
        $service = new MailService();
        $service->send('',
            '<a href="">confirm email hash '.$hash_to_verify.'</a>',
            $user->email,
            $user->name,
            'Confirm email');
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user'=>$user,
            'token'=>$token
        ];

        return response($response,201);
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return [
            'message'=>'Logged out'
        ];
    }


    public function login(Request $request){
        $fields = $request->validate([
            'email'=>'required|string',
            'password'=>'required|string'
        ]);

        $user = User::where('email',$fields['email'])->first();
        if(!$user || !Hash::check($fields['password'],$user->password)){
            return response([
                'message'=>'Bad credentials'
            ],401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user'=>$user,
            'token'=>$token
        ];

        return response($response,201);
    }

    public function verifyEmail($token){
        $user = Auth::user();
        if($user->email_verification_hash != $token){
            return response(['message'=>'incorrect hash'],404);
        }
        $user->email_verified = 1;
        $user->save();
        return response(['message'=>'email verified']);
    }

    public function remember(Request $request){
        $fields = $request->validate([
            'email'=>'required|string',
        ]);
        $user = User::where('email',$fields['email'])->first();
        if(empty($user)){
            return response([
                'message'=>'user not exist'
            ],404);
        }
        $password = rand(11111,99999);
        $user->password = bcrypt($password);
        $user->save();
        $service = new MailService();
        $service->send('', 'Your new password '.$password, $user->email, $user->name, 'Your new password');
        $token = $user->createToken('myapptoken')->plainTextToken;
        return response([
            'message'=>'new password send to your email'
        ]);
    }

}
