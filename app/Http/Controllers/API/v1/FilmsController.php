<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use Mockery\Exception;
use App\Service;
use DB;

class FilmsController extends BaseController {

    public function __construct() {
    }

    /**
     * Display a listing of the services.
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request) {
       try {
            $filmsList = Films::select([ DB::raw("IFNULL(id,'') as id"), DB::raw("IFNULL(name,'') as name"), DB::raw("IFNULL(description,'') as description"),DB::raw("IFNULL(release_date,'') as release_date"),DB::raw("IFNULL(rating,'') as rating"),DB::raw("IFNULL(ticket_price,'') as ticket_price"),DB::raw("IFNULL(photo,'') as photo")])->where('status', '=', 1)->orderBy('name')->get()->toArray();
            return $this->sendSuccessResponse('success', $filmsList, config('constant.api_response.OK'));
       } catch (Exception $ex) {
            $message = $ex->getMessage() . '----' . $ex->getLine();
            return $this->sendFailureResponse($message, config('constant.api_response.UNPROCESSABLE_ENTITY'));

       }

    }

    /**
     * Display the specified services.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($slug, Request $request)
    {
       try {
            /*Check basic auth token*/
            $headerToken = $request->header('basicauthtoken');
            if($headerToken == config('constant.api_access_token.API_AUTH')){
                $service =  Service::where('service_slug', $slug)->get(['id']);
                if(isset($service) && !empty($service[0]->id)){
                    $id = $service[0]->id;
                    $serviceImageTable = config('constant.db_table.SERVICE_IMAGE');
                    $columns = [DB::raw("IFNULL(service_title,'') as service_title"),DB::raw("IFNULL(service_description,'') as service_description"),DB::raw("IFNULL(thumbnail,'') as thumbnail"),DB::raw("IFNULL(image,'') as image"),DB::raw("IFNULL(status,'') as status")];
                    $data['serviceData'] = Service::join("$serviceImageTable as img", 'img.service_id', '=', 'services.id')->where('services.id', $id)->get($columns);
                    return $this->sendSuccessResponse('success', $data, config('constant.api_response.OK'));
                } else{
                    return $this->sendFailureResponse('Requested data not found', config('constant.api_response.NOT_FOUND'));
                }
            }else{
                return $this->sendFailureResponse($this->noToken, config('constant.api_response.UNPROCESSABLE_ENTITY'));
            }
       } catch (Exception $e) {
            $message = $ex->getMessage() . '----' . $ex->getLine();
            return $this->sendFailureResponse($message, config('constant.api_response.UNPROCESSABLE_ENTITY'));
       }
    }

}
