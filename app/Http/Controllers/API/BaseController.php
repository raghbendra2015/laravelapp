<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * @SWG\Swagger(
 *   basePath="/api",
 *   @SWG\Info(
 *     title="earthDECK-API",
 *     description="earthDECK REST API",
 *     version="1.0.0",
 *   ),
 *   @SWG\SecurityScheme(
 *     securityDefinition="api-key",
 *     type="apiKey",
 *     in="header",
 *     name="api-key"
 *   ),
 * )
 */
class BaseController extends Controller {

    /**
     * @name sendSuccessResponse
     * @description function for send success response
     * @param type $message
     * @param type $result
     * @return type
     */
    public function sendSuccessResponse($message, $result, $code = 200) {
//        if (count($result) == 0) {
//            $result = (object) $result;
//        }
        
        $response = [
            'message'       => $message,
            'data'          => $result
        ];
        
        return response($response, $code);
    }

    /**
     * @name sendFailureResponse
     * @description function for send failure response
     * @param type $message
     * @param type $code
     * @param type $result
     * @return type
     */
    public function sendFailureResponse($message, $code = 422, $result = []) {
        if (count($result) == 0) {
            $result = (object) $result;
        }
        
        $response = [
            'message'       => $message,
            'data'          => $result
        ];
        
        return response($response, $code);
    }

}
