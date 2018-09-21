<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Exception;
use Session;
use DB;
use App\Films;

class AdminController extends Controller {

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
        //$this->middleware('ajax-request', ['except' => ['deleteFilm']]);
        /* Assign logged in user value */
        $this->_user = Auth::user();
    }
    /**
     * home
     * @param Request $request
     */
    public function index() {
        try {
            return view('admin.films-list');
        } catch (Exception $ex) {
            $error = $ex->getMessage() . ' - ' . $ex->getLine();
            toastr()->error($error);
            return redirect()->to('/admin');
        }
    }


    /**
     * Get the film data for yajara grid
     *
     * @return \Illuminate\Http\Response
     */
    public function getFilmGridData() {
        try {
            $filmList = Films::getFilmList();
            return \DataTables::of($filmList)
                ->editColumn('photo', function ($filmList) {
                    return !empty($filmList->photo) ? '<img src="'.url('uploads/'.$filmList->photo).'" alt="no-image" title="'.$filmList->name.'" height="30" width="30" />' : '';
                })
                ->editColumn('release_date', function ($filmList) {
                    return !empty($filmList->release_date) ? date('d-m-Y', strtotime($filmList->release_date)) : '';
                })
                ->addColumn('action', function ($filmList) {
                    $action = '<a href="'.url('admin/edit/'.$filmList->id).'" class="edit_group fa-icon" title="Edit Film"><i class="fa fa-edit"></i></a><a 
href="javascript:void(0);" cstm="'.$filmList->id.'" id="deleteFilm'.$filmList->id.'" class="delete_group fa-icon"
 title="Delete Film"><i
 class="fa fa-trash"></i></a>';
                    return $action;
                })
                ->rawColumns(['photo','action'])
                ->make(true);
        } catch (\Exception $ex) {
            $exception = 'Error - ' . $ex->getMessage() . ' (Line - ' . $ex->getLine() . ')';
            return response()->json(['status' => false,'message' => $exception],200);
        }
    }
    /**
     * addFilm
     * @param Request $request
     */
    public function addFilm() {
        try {
            return view('admin.films-add');
        } catch (Exception $ex) {
            $error = $ex->getMessage() . ' - ' . $ex->getLine();
            toastr()->error($error);
            return redirect()->to('/admin');
        }
    }

    /**
     * saveFilm
     * @param Request $request
     */
    public function saveFilm(Request $request) {
        DB::beginTransaction();
        try {
            $data = $request->all();

            if(isset($this->_request->update_film) && !empty($this->_request->update_film)){
                $validator = \Validator::make($data, Films::updateFilmValidationRules(), Films::$addFilmValidationMessages);
                if ($validator->fails()) {
                    return redirect()->to('admin/add')->withErrors($validator)->withInput();
                }
                $films = Films::find($this->_request->update_film);
                if ($this->_request->hasFile('photo')) {
                    $item = $this->_request->file('photo');
                    $path = $this->_request->file('photo')->getRealPath();
                    $fileExtension = $this->_request->file('photo')->getClientOriginalExtension();
                    /* get file name without extension */
                    $fileName = pathinfo($item->getClientOriginalName(), PATHINFO_FILENAME);
                    /* Create unique file name */
                    $fileNewName = $fileName . '_' . time() . '.' . $fileExtension;
                    $item->move(public_path() . '/uploads/', $fileNewName);
                    $films->photo = $fileNewName;
                }

                $genre = implode(',', $this->_request->genre);

                $films->name = $this->_request->film_name;
                $films->description = $this->_request->desc;
                $films->release_date = date("Y-m-d", strtotime($this->_request->release_date));
                $films->rating = $this->_request->rating;

                $films->country = $this->_request->country;
                $films->ticket_price = $this->_request->price;
                $films->user_id = Auth::user()->id;
                $films->genre = $genre;
                $films->slug = str_slug($this->_request->film_name);
                $films->save();
                DB::commit();
                toastr()->success(config('constant.message.UPDATED'));
                return redirect()->to('/admin');
            }else {
                $validator = \Validator::make($data, Films::addFilmValidationRules(), Films::$addFilmValidationMessages);

                if ($validator->fails()) {
                    return redirect()->to('admin/add')->withErrors($validator)->withInput();
                }
                if ($this->_request->hasFile('photo')) {
                    $item = $this->_request->file('photo');
                    $path = $this->_request->file('photo')->getRealPath();
                    $fileExtension = $this->_request->file('photo')->getClientOriginalExtension();
                    /* get file name without extension */
                    $fileName = pathinfo($item->getClientOriginalName(), PATHINFO_FILENAME);
                    /* Create unique file name */
                    $fileNewName = $fileName . '_' . time() . '.' . $fileExtension;
                    $item->move(public_path() . '/uploads/', $fileNewName);
                    $genre = implode(',', $this->_request->genre);
                    $films = new Films();
                    $films->name = $this->_request->film_name;
                    $films->description = $this->_request->desc;
                    $films->release_date = date("Y-m-d", strtotime($this->_request->release_date));
                    $films->rating = $this->_request->rating;
                    $films->photo = $fileNewName;
                    $films->country = $this->_request->country;
                    $films->ticket_price = $this->_request->price;
                    $films->user_id = Auth::user()->id;
                    $films->genre = $genre;
                    $films->slug = str_slug($this->_request->film_name);
                    $films->save();
                    DB::commit();
                    toastr()->success(config('constant.message.CREATED'));
                    return redirect()->to('/admin');
                }
            }
        }catch (\Exception $ex) {
            DB::rollBack();
            $error = $ex->getMessage() . ' - ' . $ex->getLine();
            toastr()->error($error);
            return redirect()->to('/admin/add');
        }
    }

    /**
     * getEditFilm
     * @param Request $request
     */
    public function getEditFilm() {
        try {
            $id = $this->_request->segment(3);
            $data['filmsData'] = Films::getFilms($id);
            return view('admin.films-edit',$data);
        } catch (Exception $ex) {
            $error = $ex->getMessage() . ' - ' . $ex->getLine();
            toastr()->error($error);
            return redirect()->to('/admin');
        }
    }

    /**
     * getEditFilm
     * @param Request $request
     */
    public function deleteFilm() {
        try {
           $filmId = $this->_request->filmId;
           $reslt = Films::deleteFilm($filmId);
           return response()->json(['status' => true,'message' => "Records has been deleted"],200);
        } catch (Exception $ex) {
            $error = $ex->getMessage() . ' - ' . $ex->getLine();
            toastr()->error($error);
            return redirect()->to('/admin');
        }
    }

}
