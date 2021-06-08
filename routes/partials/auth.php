<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use \App\Models\ShippingInfo;
use \App\AlertHelper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\PasswordReset;

Route::post('/validate-register', function(Request $request) {
    return response()->json(Validator::make($request->all(), [
        'username' => ['required', 'string', 'max:255', 'unique:users'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => 'required|min:8',
        'password_confirmation' => 'required|min:8|same:password',
        'g-recaptcha-response' => 'required|captcha',
    ])->errors());
})->name('validate-register');

Route::post('/validate-login', function(Request $request) {
    $validator = Validator::make($request->all(), [
        'email' => 'required|max:255|email',
        'password' => 'required',
    ]);

    if(sizeof($validator->errors()) > 0) {
        return response()->json($validator->errors());
    }
 
    if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false]);
})->name('validate-register');

Route::post('/reset-password', function() {
    if(\App\Models\User::where('email', request('email'))->exists()) {
        event(new PasswordReset(\App\Models\User::where('email', request('email'))->first()));
    }

    AlertHelper::alertSuccess('Password reset email sent!');
    return back();
})->name('reset-password');

Route::post('/set-password', function() {
    $token = request('token');
    
    if(! DB::table('password_resets')->where('token', $token)->exists()) {
        return redirect('/');
    }

    $validator = Validator::make(request()->all(), [
        'password' => 'required|min:8',
        'password_confirmation' => 'required|min:8|same:password',
    ]);

    if ($validator->fails()) {
        return back()->withInput()->withErrors($validator->messages());
    }


    $tokenData = DB::table('password_resets')->where('token', $token)->first();
    $user = \App\Models\User::where('email', $tokenData->email)->first();
    $user->password = \Hash::make(request('password'));
    $user->save();

    DB::table('password_resets')->where('token', $token)->delete();

    AlertHelper::alertSuccess('Password reset successful!');
    return back();
})->name('reset-password');

Route::get('/change-password', function() {
    $token = request('token');
    
    if(! DB::table('password_resets')->where('token', $token)->exists()) {
        AlertHelper::alertWarning('Invalid request.');
        return redirect('/');
    }

    $tokenData = DB::table('password_resets')->where('token', $token)->first();

    $created = new \Carbon\Carbon($tokenData->created_at);
    $now = \Carbon\Carbon::now();

    if($created->diff($now)->days > 1) {
        AlertHelper::alertWarning('Invalid request.');
        return redirect('/');
    }

    return view('change_password')->with('tokenData', $tokenData);
});