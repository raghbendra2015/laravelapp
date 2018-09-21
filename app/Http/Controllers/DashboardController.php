<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Exception;
use Session;
use DB;
use App\Films;

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
        //$this->middleware('auth');
        //Execute ajax request filter to check if incoming request is ajax or not
        $this->middleware('clean-input');
        /* Assign logged in user value */
        $this->_user = Auth::user();
    }
    /**
     * getDashboard
     * @param Request $request
     */
    public function getDashboard() {
        try {
            return view('films.films-list');
        } catch (Exception $ex) {
            $error = $ex->getMessage() . ' - ' . $ex->getLine();
            toastr()->error($error);
            return redirect()->to('/dashboard');
        }
    }
    /**
     * getFilmsList
     * @param Request $request
     */
    public function getFilmsList() {
        try {
            return view('films.films-list');
        } catch (Exception $ex) {
            $error = $ex->getMessage() . ' - ' . $ex->getLine();
            toastr()->error($error);
            return redirect()->to('/dashboard');
        }
    }


    public function showFilm($slug){
        try {
            //$slug = base64_decode($slug);
            // Get the film comments
            $data['validationMessages'] = Films::$validationMessages;
            $filmData = Films::getFilmData($slug);
            $data['filmComments'] = Films::getFilmComments($filmData->id);
            $data['filmData'] = $filmData;
            //Render the film details with comments
            return view('films.film-detail', $data);
        } catch (\Exception $ex) {
            //die( $ex->getMessage() . ' (Line - ' . $ex->getLine() );
            $data['exception'] = 'Error - ' . $ex->getMessage() . ' (Line - ' . $ex->getLine() . ')';
            return view('errors.common-error', $data);
        }
    }

    /**
     * Store a newly created resource in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeComment() {
        DB::beginTransaction();
        try {
            $data = $this->_request->all();
            $id = '';
            $saveData = Films::saveComment($data);
            DB::commit();
            $html = '<div class="row">
                                    <div class="form-group col-md-3 col-sm-6">
                                            <label>'.$data['comment'].'</label>
                                            <p>'.Auth::user()->name.'</p>
                                        </div>
                                    </div>';
            //Save comment
            if ($saveData) {
                return response()->json(['status' => true,'message' => config('constant.message.COMMENT_SUCCESS'), 'result' => $html],200);
            } else {
                return response()->json(['status' => false,'message' => config('app.messages.default_error')],200);
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            $exception = 'Error - ' . $ex->getMessage() . ' (Line - ' . $ex->getLine() . ')';
            return response()->json(['status' => false,'message' => $exception],200);
        }
    }
}
