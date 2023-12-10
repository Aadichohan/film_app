<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;


use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //

    public function login(AuthRequest $request) {

        $request->validated();
        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials))
         return response()->json([
            'message'=> 'Unauthorized'
         ],401);

         $user = $request->user();
        //  $user = Auth::user();
        //  dd($user);
         $tokenResult = $user->createToken('token');
         $token = $tokenResult->token;

         if ($request->remember_me){
             $token->expires_at = Carbon::now()->addWeeks(1);
         }

         $token->save();
        //  dd([

        //     "access_token" => $tokenResult->accessToken,
        //     "token_type" => "Bearer",
        //     "expires_at" => Carbon::parse(
        //         $tokenResult->token->expires_at
        //      )->toDateTimeString()
        //  ]);

     return response()->json([

         "access_token" => $tokenResult->accessToken,
         "token_type" => "Bearer",
         "expires_at" => Carbon::parse(
             $tokenResult->token->expires_at
          )->toDateTimeString()
      ]);
    }

    // public function login(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');
        
    //     if (Auth::attempt($credentials)) {
    //         // dd(Auth::user());
    //         $token = Auth::user()->createToken('MyApp')->accessToken;
    //         return response()->json(['token' => $token], 200);
    //     } else {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }
    // }

    // public function logout(Request $request)
    // {
    //     // dd($request->input());
    //    // if ($request->user()) {
    //         $request->user()->token()->revoke();
    //         return response()->json(['message' => 'Successfully logged out']);
    //     // } else {
    //     //     return response()->json(['error' => 'Unauthorized'], 401);
    //     // }
    // }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->token()->delete();
            return response()->json(['message' => 'Successfully logged out']);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
