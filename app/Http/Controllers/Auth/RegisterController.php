<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mail;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'username' => ['required', 'string', 'unique:users', 'alpha_dash', 'min:4', 'max:30'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        
        $position_up = User::max('position') + 1;
        
        $referrer = User::whereUsername(session()->pull('referrer'))->first();
        
        if ($referrer->position > 0) {
            $position_down = $referrer->position - 1;
            $mail_id = $referrer->email;

            $referrer->position = $position_down;
            $referrer->save();
            $referrer->touch();

            if ($position_down == 0) {

                $datas = array('name'=>"Referral App");
                Mail::send(['html'=>'mail'], $datas, function($message) use($mail_id) {
                    $message->to($mail_id, 'Referral App')->subject
                        ('Laravel Referral Milestone Completed');
                    $message->from('panniguys@gmail.com','Referral App');
                }); 
            } 
        }
        
        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'position' => $position_up,
            'password' => Hash::make($data['password']),
            'referrer_id' => $referrer ? $referrer->id : null,
        ]); 
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request)
    {
        if ($request->has('ref')) {
            session(['referrer' => $request->query('ref')]);
        }

        return view('auth.register');
    }
}
