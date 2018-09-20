<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Exception;
use Session;
use DB;

class DashboardController extends Controller {

    private $_user;
    private $_request;
    private $_response = [];

    /**
     * 
     * @param Request $request
     */
    public function __construct(Request $request) {
        /* Assign Request value */
        $this->_request = $request;
        /* Execute authentication filter before processing any request */
        $this->middleware('auth');
        //Execute ajax request filter to check if incoming request is ajax or not
        $this->middleware('clean-input');
        /* Assign logged in user value */
        $this->_user = Auth::user();
    }

    public function getDashboard() {
        try {
            return view('dashboard.index');
        } catch (Exception $ex) {
            $error = $ex->getMessage() . ' - ' . $ex->getLine();
            toastr()->error($error);
            return redirect()->to('/dashboard');
        }
    }

}
