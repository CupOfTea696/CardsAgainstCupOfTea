<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Validator;

use App\Models\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */
    
    use ThrottlesLogins;
    
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    
    public function loginForm()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);
        
        if ($this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }
        
        $credentials = $request->only('username', 'password');
        
        if (Auth::attempt($credentials, true)) {
            $this->clearLoginAttempts($request);
            
            return redirect()->intended(route('lobby'));
        }
        
        return $this->sendFailedLoginResponse($request, $user);
    }
    
    public function signUpForm()
    {
        return view('auth.signup');
    }
    
    public function signUp(Request $request)
    {
        $validator = $this->validator($request->all());
        
        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
        
        Auth::login($this->create($request->all()));
        
        return redirect()->route('lobby');
    }
    
    public function loginOrSignUp(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);
        
        if ($this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }
        
        $credentials = $request->only('username', 'password');
        
        if (Auth::attempt($credentials, true)) {
            $this->clearLoginAttempts($request);
            
            return redirect()->intended(route('lobby'));
        }
        
        if ($user = User::where('username', $request->input('username'))->count()) {
            return $this->sendFailedLoginResponse($request, $user);
        }
        
        if ($request->input('email')) {
            return $this->signUp($request);
        }
        
        return $this->sendFailedLoginResponse($request, $user);
    }
    
    public function logout()
    {
        Auth::logout();
        
        return redirect()->home();
    }
    
    protected function sendFailedLoginResponse(Request $request, $user)
    {
        $this->incrementLoginAttempts($request);
        
        if (! $user) {
            $errors = ['username' => Lang::get('auth.user_not_found')];
        } else {
            $errors = ['password' => Lang::get('auth.password_mismatch')];
        }
        
        return redirect()->route('login')
            ->withInput($request->only('username'))
            ->withErrors($errors);
    }
    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:255|unique:Users',
            'email' => 'required|email|max:255|unique:Users',
            'password' => 'required|min:6',
        ]);
    }
    
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
    
    /**
     * Get the URL we should redirect to.
     *
     * @return string
     */
    protected function getRedirectUrl()
    {
        // redirect url for failed sign up
        return route('signUp');
    }
    
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function loginUsername()
    {
        return 'username';
    }
}
