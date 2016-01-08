<?php

namespace App\Http\Controllers\Auth;

use Password;
use App\Http\Controllers\Controller;
use App\Models\Password as PasswordModel;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */
    
    use ResetsPasswords;
    
    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $token = null)
    {
        $email = $request->input('email');
        
        if (view()->exists('auth.passwords.reset')) {
            return view('auth.passwords.reset')->with(compact('token', 'email'));
        }
        
        return view('auth.reset')->with('token', $token);
    }
    
    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'password' => 'required|min:6',
        ]);
        
        $credentials = $request->only(
            'password', 'token'
        );
        
        $credentials['password_confirmation'] = $credentials['password'];
        $credentials['email'] = PasswordModel::select('email')->where('token', $credentials['token'])->first();
        
        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });
        
        switch ($response) {
            case Password::PASSWORD_RESET:
                return $this->getResetSuccessResponse($response);
                
            default:
                return $this->getResetFailureResponse($request, $response);
        }
    }
}
