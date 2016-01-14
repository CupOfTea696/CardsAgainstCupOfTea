<?php

namespace App\Http\Controllers;

use Validator;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function edit()
    {
        return view('account.edit');
    }
    
    public function update(Request $request)
    {
        $this->validate($request, [
            'email' => 'email|max:255|unique:Users',
            'password' => 'min:6',
        ]);
        
        $data = [];
        
        if ($email = $request->get('email')) {
            $data['email'] = $email;
        }
        
        if ($password = $request->get('password')) {
            $data['password'] = bcrypt($password);
        }
        
        $request->user()->update($data);
        
        return redirect()->route('account.edit')->with('alert', [
            'message' => trans('auth.save.success'),
            'level' => 'success'
        ]);
    }
}
