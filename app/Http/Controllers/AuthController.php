<?php

namespace App\Http\Controllers;

use App\Events\UserForgotPassword;
use App\Events\UserRegister;
use App\Http\Requests\RegisterRequest;
use App\Jobs\ForgotUserEmailJob;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;


class AuthController extends Controller
{

    /**
     * @OA\Post(
     * path="/api/register",
     * summary="Register",
     * description="Register by name,email, password,password confirmation",
     * operationId="register",
     * tags={"register"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"name","email","password","passsword confirmation"},
     *       @OA\Property(property="name", type="string", format="string", example="TEST"),
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *       @OA\Property(property="password_confirmation", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Register successfully",
     *    @OA\JsonContent(
     *       @OA\Property(
     *          property="message",
     *          type="string",
     *          example="Succesfully registrated")
     *        )
     *     )
     * )
     *)
     */

    public function register(RegisterRequest $request)
    {
        $fields = $request->validated();
        $hash_to_verify = md5(time() . $fields['email']);
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'email_verification_hash' => $hash_to_verify,
            'email_verified' => 1
        ]);


        $token = $user->createToken('myapptoken')->plainTextToken;

        event(new UserRegister($user));

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Logged out'
        ];
    }


    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Login",
     * description="Login by email, password",
     * operationId="authLogin",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Login successfully",
     *    @OA\JsonContent(
     *       @OA\Property(
     *          property="message",
     *          type="string",
     *          example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(
     *        property="message",
     *        type="string",
     *        example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * )
     *)
     */


    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad credentials'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response);
    }

    public function verifyEmail($token)
    {
        $user = Auth::user();
        if ($user->email_verification_hash != $token) {
            return response(['message' => 'incorrect hash'], 404);
        }
        $user->email_verified = 1;
        $user->save();
        return response(['message' => 'email verified']);
    }

    public function remember(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
        ]);
        $user = User::where('email', $fields['email'])->first();
        if (empty($user)) {
            return response([
                'message' => 'user not exist'
            ], 404);
        }
        $password = rand(11111, 99999);
        $user->password = bcrypt($password);
        $user->save();
        event(new UserForgotPassword($user,$password));
        $token = $user->createToken('myapptoken')->plainTextToken;
        return response([
            'message' => 'new password send to your email'
        ]);
    }

}
