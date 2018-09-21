<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Session;
use Validator;
use App\User;
use Exception;
use WebUsers;

class LoginController extends Controller {
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
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //$this->middleware('guest')->except('logout');
        $this->middleware('clean-input');
    }

    /**
     * @functionname getLogin
     * @functiondescription Show application login form
     * @return \Illuminate\Http\Response
     */
    public function getLogin() {
        if (Auth::check()) {
            if (WebUsers::isUser()) {
                return redirect('/dashboard');
            }
            toastr()->error('Unauthorized Access!');
            return redirect('/');
        }
        return view('auth.login');
    }

    /**
     * @functionname postLogin
     * @functiondescription Process login request
     * @return user
     */
    public function postLogin(Request $request) {
        try {
            /* validate user inputs */
            $validation = Validator::make($request->all(), User::$userLoginValidationRules, User::$userLoginValidationMessages);
            if ($validation->fails()) {
                foreach ($validation->errors()->all() as $error) {
                    toastr()->error($error);
                }
                return redirect()->to('/login');
            } else {
                $userdata = array('email' => $request->input('username'), 'password' => $request->input('password'), 'status' => '1');
                /* doing login */
                if (Auth::validate($userdata)) {
                    if (Auth::attempt($userdata)) {
                        if (WebUsers::isAdmin()) {
                            return redirect('/admin');
                        }
                        if (WebUsers::isUser()) {
                            return redirect('/dashboard');
                        }
                        toastr()->error('Unauthorized Access!');
                        return redirect('/');
                    }
                }
                /* Failed to login */
                toastr()->error('Wrong username or password. Try again.');
                return redirect()->to('/login');
            }
        } catch (Exception $ex) {
            $error = $ex->getMessage() . ' - ' . $ex->getLine();
            toastr()->error($error);
            return redirect()->to('/login');
        }
    }

    /**
     * @functionname getLogout
     * @functiondescription Log the user out of the application.
     * @return \Illuminate\Http\Response
     */
    public function getLogout() {
        try {
            Auth::logout();
            toastr()->success('Logged out successfully!');
            return redirect('/login');
        } catch (Exception $ex) {
            $error = $ex->getMessage() . ' - ' . $ex->getLine();
            toastr()->error($error);
             return redirect()->to('/login');
        }
    }

}
