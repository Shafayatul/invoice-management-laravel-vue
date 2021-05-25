<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;
use App\Mail\ForgotPasswordSuccess;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function forgot(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'email' => 'required|string|email',
        ]);

        if($validate->fails()){
            return response()->json(['errors' => $validate->errors(), 'code' => 422]);
        }

        $user = User::where('email', $request->email)->first();
       
        if (!$user){
            return response()->json([
                'message' => 'We can not find a user with that e-mail address.'
            ], 404);
        }

        if($user->role == 'client'){
            return response()->json([
                'message' => 'We can not find a user with that e-mail address.'
            ], 404);
        }

        $passwordReset = new PasswordReset();
        $passwordReset->email = $user->email;
        $passwordReset->token = Str::random(60);
        $passwordReset->save();
        if($user && $passwordReset){
            $actionUrl = url("change-password").'/'.$passwordReset->token;
            $actionText = "Reset Password";
            $introLines = "You are receiving this email because we received a password reset request for your account.";
            $outroLines = "If you did not request a password reset, no further action is required.";
            $displayableActionUrl = str_replace(['mailto:', 'tel:'], '', $actionUrl);
            Mail::to($user->email)->send(new ForgotPassword($actionUrl, $actionText, $introLines, $outroLines, $displayableActionUrl));

            return response()->json([
                'message' => 'We have e-mailed your password reset link!'
            ]);
        }else{
            return response()->json([
                'message' => 'No Notification Sent'
            ]);
        } 
    }

    public function check(Request $request)
    {
        $passwordReset = PasswordReset::where('token', $request->token)
            ->first();
        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }

        return response()->json($passwordReset, 200);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);
        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();
        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
            return response()->json([
                'message' => 'We can not find a user with that e-mail address.'
            ], 404);
        $user->password = Hash::make($request->password);
        $user->save();
        PasswordReset::where('token', $request->token)
            ->orWhere('email', $request->email)->delete();
        $lines = ["You are changed your password successful.", "If you did change password, no further action is required.", "If you did not change password, protect your account."];
        Mail::to($user->email)->send(new ForgotPasswordSuccess($lines));
        return response()->json($user, 200);
    }
}
