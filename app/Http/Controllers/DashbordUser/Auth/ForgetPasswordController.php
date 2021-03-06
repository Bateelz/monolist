<?php

namespace App\Http\Controllers\DashbordUser\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPassword;
use App\Mail\SuccessForgetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgetPasswordController extends Controller
{
    public function index(){
        return view('dasborduser.auth.reset');
      }

      public function showResetPasswordForm($token) {
         $return_details=  DB::table('password_resets')->where('token',$token)->first();
         if($return_details){
             $user=User::where('email',$return_details->email)->first();
             return view('dasborduser.auth.confirm',compact('user','token'));
         }
          abort(404);
       }

      public function forget(Request $request){
          $request->validate([
              'email' => 'required|email',
          ]);
          $user=User::where('email',$request->email)->first();
          if ($user) {
              $token = Str::random(64);
              DB::table('password_resets')->insert([
              'email' => $request->email,
              'token' => $token,'created_at' => Carbon::now()]);
            $data=
            [
              'email'=>$request->email,
              'token' => $token,
            ];
               Mail::to($request->email)->send(new ForgetPassword($data));
               alert()->success('We have e-mailed your password reset link!', 'Send Email');
              return back();
          }
          alert()->error('Not Found Email', 'Wrong Email');
          return back();
      }


      public function submitResetPasswordForm(Request $request)
      {
          $request->validate([
              'email' => 'required|email|exists:users',
              'password' => 'required|string|min:6|confirmed',
              'password_confirmation' => 'required'
          ]);
          $check = DB::table('password_resets')->where(['email' => $request->email,'token' => $request->token])->first();
          if(!$check){
              return back()->withInput()->with('error', 'Invalid token!');
          }
          $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
        //   DB::table('password_resets')->where(['token'=> $request->token])->delete();
          $data=['email'=>$request->email];
          Mail::to($request->email)->send(new SuccessForgetPassword($data));
          return redirect('/')->with('message', 'Your password has been changed!');
      }
}
