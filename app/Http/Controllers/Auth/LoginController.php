<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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

        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect()->route('dashboard');
    }

    protected function validateLogin(Request $request)
    {
        Validator::extend('user_validator', function ($attribute, $value) {
            $query = User::where('email',$value)->where("status",1)->first();
            if(empty($query) or $query->type === "ADMIN"){
                return true;
            }
            return $query->company->status === 1;
        });


        $request->validate([
            $this->username() => [
                'required',
                'email',
                'user_validator'
            ],
        ],[
            'user_validator' => 'Your account or company is no longer have access'
        ]);
    }
}
