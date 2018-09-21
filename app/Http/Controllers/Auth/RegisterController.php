<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use DB;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('clean-input');
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Overrid method to show registration form
     */
    public function showRegistrationForm() {
        try {
            return view('auth.register');
        } catch (\Exception $ex) {
            toastr()->error('Somthing went wrong!');
            return redirect('/login');
        }
    }

    /**
     * Register new account.
     *
     * @param Request $request
     * @return User
     */
    protected function register(Request $request) {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $validator = \Validator::make($data, User::userRegisterValidationRules(), User::$userRegisterValidationMessages);

            if ($validator->fails()) {
                return redirect()->to('register')->withErrors($validator)->withInput();
            }

            $data['password']    = bcrypt(array_get($data, 'password'));
            $data['role_id']    = 2;
            $user                = app(User::class)->create($data);
            DB::commit();
            toastr()->success('Registered successfully.');
            return redirect()->to('/register');
        } catch (\Exception $ex) {
            DB::rollBack();
            $error = $ex->getMessage() . ' - ' . $ex->getLine();
            toastr()->error($error);
            return redirect()->to('/register');
        }
    }


}
