<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use Mockery\Exception;
use DB;
use App\Films;

class FilmsController extends BaseController {

    public function __construct() {
    }

    /**
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request) {
        try {
            $filmsList = Films::select([ DB::raw("IFNULL(id,'') as id"), DB::raw("IFNULL(name,'') as name"), DB::raw("IFNULL(description,'') as description"),DB::raw("IFNULL(slug,'') as slug"),DB::raw("IFNULL(date_format(release_date,'%d-%M-%Y'),'') as release_date"),DB::raw("IFNULL(rating,'') as rating"),DB::raw("IFNULL(ticket_price,'') as ticket_price"),DB::raw("IFNULL(photo,'') as photo")])->whereNull('deleted_at')->orderBy('release_date', 'desc')->offset($request->offset)->limit(1)->get()->toArray();
            return $this->sendSuccessResponse('success', $filmsList, config('constant.api_response.OK'));
        } catch (Exception $ex) {
            $message = $ex->getMessage() . '----' . $ex->getLine();
            return $this->sendFailureResponse($message, config('constant.api_response.UNPROCESSABLE_ENTITY'));

        }

    }

}
