<?php
namespace App\Http\Controllers\Auth;

// use mail;
use App\Http\Controllers\Controller;
use App\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class ProfileResetPassController extends Controller
{
    //Show Forgot Password Form
    public function showForgotForm()
    {
        return view('forgotform');
    }

    //Reset Users Profile Password
    // public function resetPass(Request $request)
    // {

    //     if (UserProfile::where('email', '=', $request->email)->count() > 0) {
    //         // user found
    //         $user              = UserProfile::where('email', '=', $request->email)->firstOrFail();
    //         $autopass          = str_random(8);
    //         $input['password'] = Hash::make($autopass);

    //         $user->update($input);
    //         $subject = "Reset Password Request";
    //         $msg     = "Your New Password is : " . $autopass;

    //         mail($request->email, $subject, $msg);
    //         Session::flash('success', 'Your Password Reseted Successfully. Please Check your email for new Password.');
    //         return redirect(route('user.forgotpass'));

    //     } else {
    //         // user not found
    //         Session::flash('error', 'No Account Found With This Email.');
    //         return redirect(route('user.forgotpass'));
    //     }
    // }

    public function resetPass(Request $request)
    {
        if (UserProfile::where('email', $request->email)->exists()) {
            // User found
            $user              = UserProfile::where('email', $request->email)->first();
            $autopass          = str_random(8);
            $input['password'] = Hash::make($autopass);

            $user->update($input);

            // Send email using Laravel Mail
            Mail::send([], [], function ($message) use ($request, $autopass) {
                $message->to($request->email)
                    ->subject('Reset Password Request')
                    ->setBody("Your New Password is: <strong>{$autopass}</strong>", 'text/html');
            });

            Session::flash('success', 'Your Password has been reset successfully. Please check your email for the new password.');
            return redirect(route('user.forgotpass'));

        } else {
            // User not found
            Session::flash('error', 'No account found with this email.');
            return redirect(route('user.forgotpass'));
        }
    }

}
