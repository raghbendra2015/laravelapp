<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\BaseController;
use App\User;
use Auth;
use DateTime;
use DB;
//use Illuminate\Support\Facades\DB;
use Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Mail;
use Mockery\Exception;
use Validator;

class AuthController extends BaseController
{

    use \App\Traits\TwilioTrait;

    /*
    |--------------------------------------------------------------------------
    | AuthController Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
     */

    private $signupSuccessMsg = "You have registered successfully, Please verify your account with the OTP.";
    private $forgetPasswordSuccessMsg = "Your password has been successfully sent to your email address.";
    private $loginSuccessMsg = "Logged in successfully.";
    private $loginErrorMsg = "Invalid email or password.";
    private $loginInactiveErrorMsg = "It seems, your account is not activated. Kindly connect with the administrator for the same.";
    private $userNotFound = "User not found.";
    private $otpNotFound = "Verification code invalid or expired.";
    private $otpVerified = "Account verified successfully.";
    private $otpResend = "OTP resend successfully.";
    private $noToken = "Basic auth token not found.";
    private $mobileNumberInvalid = "Mobile number is not found.";
    private $modeChanged = "The user mode has been changed successfully.";
    private $providerInformationFound = "Provider information found.";
    private $requesterInformationFound = "Requester information found.";
    private $profileImageUploaded = "The profile image has been uploaded successfully.";
    private $workHistoryUploaded = "Provider work history uploaded successfully.";
    private $workHistoryDeleted = "Provider work history deleted successfully.";
    private $mediaNotFound = "Provider work history not found.";
    private $requesterProfileUpdated = "Your profile has been updated successfully.";
    private $providerProfileUpdated = "Your profile has been updated successfully.";
    private $passwordUpdated = "Your password has been updated successfully.";
    private $fileUploadError = "File not found.";
    private $socialEmailRequired = "The email address is required for signin.";

    public function __construct()
    {
    }

    /*Normal Signup for Requester and Provider*/
    public function signup(Request $request)
    {
        try {
            DB::beginTransaction();
            /*Check basic auth token*/
            $headerToken = $request->header('basicauthtoken');
            if ($headerToken == config('constant.api_access_token.API_AUTH')) {
                //get all the data and store it inside $data variable
                $data = $request->all();
                $validation = Validator::make($data, User::$userValidationRules, User::$userValidationMessages);
                if ($validation->fails()) {
                    $validationMessage = [];
                    $validationMessage = implode("\n ", $validation->errors()->all());
                    return $this->sendFailureResponse($validationMessage, config('constant.api_response.UNPROCESSABLE_ENTITY'));
                } else {

                    $newOTP = mt_rand(12345, 67890);

                    $user = new User();
                    $user->first_name = !empty($request->first_name) ? $request->first_name : "";
                    $user->last_name = !empty($request->last_name) ? $request->last_name : "";
                    $user->email = $request->email;
                    $user->password = Hash::make($request->password);
                    $user->mobile_code = $request->mobile_code;
                    $user->mobile_number = $request->mobile_number;
                    $user->term_checked = !empty($request->term_checked) ? 1 : 0;
                    $user->latitude = !empty($request->latitude) ? $request->latitude : "";
                    $user->longitude = !empty($request->longitude) ? $request->longitude : "";
//                    $user->complete_address = !empty($request->complete_address)?$request->complete_address:"";
                    $user->device_token = !empty($request->device_token) ? $request->device_token : "";
                    $user->verification_code = $newOTP;
                    $user->verification_expiry = date('Y-m-d H:i:s');
                    $user->country_id = 1;
                    $user->role_id = 0;

                    if ($user->save()) {

                        $message = "Hi, your verification code is: " . $newOTP . "";

                        $result = $this->sendSMSToUser(array('message' => $message, 'phone_number' => $request->mobile_code . $request->mobile_number));

                        DB::commit();

                        return $this->sendSuccessResponse($this->signupSuccessMsg, ["user_id" => $user->user_id, "first_name" => $user->first_name, "last_name" => $user->last_name, "email" => $user->email, "mobile_code" => $user->mobile_code, "mobile_number" => $user->mobile_number], config('constant.api_response.CREATED'));

                    }
                }
                return $this->sendFailureResponse('failure', config('constant.api_response.NOT_MODIFIED'));
            } else {
                return $this->sendFailureResponse($this->noToken, config('constant.api_response.UNPROCESSABLE_ENTITY'));
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $message = $ex->getMessage() . '----' . $ex->getLine();
            return $this->sendFailureResponse($message, config('constant.api_response.UNPROCESSABLE_ENTITY'));
        }
    } //End Method

    /*OTP verification */
    public function otpVerfication(Request $request)
    {
        try {
            /*Check basic auth token*/
            $headerToken = $request->header('basicauthtoken');
            if ($headerToken == config('constant.api_access_token.API_AUTH')) {
                $data = $request->all();
                $validation = Validator::make($data, User::$verificationValidationRules, User::$verificationValidationMessages);
                if ($validation->fails()) {
                    $validationMessage = [];
                    $validationMessage = implode("\n ", $validation->errors()->all());
                    return $this->sendFailureResponse($validationMessage, config('constant.api_response.UNPROCESSABLE_ENTITY'));
                } else {
                    $verificationCode = $request->verification_code;
                    $mobileCode = $request->mobile_code;
                    $mobileNumber = $request->mobile_number;

                    $date = new DateTime;
                    $date->modify('-3 minutes');
                    $formatted_date = $date->format('Y-m-d H:i:s');

                    $user = User::where('verification_code', $verificationCode)
                        ->where('mobile_code', $mobileCode)
                        ->where('mobile_number', $mobileNumber)
                        ->where('verification_expiry', '>=', $formatted_date)
                        ->firstOrFail();
                    $user->is_verified = 1;
                    $user->status = 1;
                    if ($user->save()) {
                        return $this->sendSuccessResponse($this->otpVerified, [], config('constant.api_response.OK'));
                    }
                }
            } else {
                return $this->sendFailureResponse($this->noToken, config('constant.api_response.UNPROCESSABLE_ENTITY'));
            }
        } catch (ModelNotFoundException $ex) {
            return $this->sendFailureResponse($this->otpNotFound, config('constant.api_response.NOT_FOUND'));
        }
    } //End Method

    /*OTP Resend Verification*/
    public function otpResendVerfication(Request $request)
    {
        try {
            /*Check basic auth token*/
            $headerToken = $request->header('basicauthtoken');
            if ($headerToken == config('constant.api_access_token.API_AUTH')) {
                $data = $request->all();
                $validation = Validator::make($data, User::$resendValidationRules, User::$resendValidationMessages);
                if ($validation->fails()) {
                    $validationMessage = [];
                    $validationMessage = implode("\n ", $validation->errors()->all());
                    return $this->sendFailureResponse($validationMessage, config('constant.api_response.UNPROCESSABLE_ENTITY'));
                } else {

                    $newOTP = mt_rand(12345, 67890);

                    $mobileCode = $request->mobile_code;
                    $mobileNumber = $request->mobile_number;

                    $user = User::where('mobile_code', $mobileCode)->where('mobile_number', $mobileNumber)->first();

                    if (!empty($user)) {
                        $date = new DateTime;
                        $date->modify('-3 minutes');
                        $formattedDate = $date->format('Y-m-d H:i:s');

                        User::where('mobile_number', $mobileNumber)
                            ->update(['verification_code' => $newOTP, 'verification_expiry' => $formattedDate]);

                        $user = User::where('mobile_code', $mobileCode)
                            ->where('mobile_number', $mobileNumber)
                            ->where('verification_expiry', '>=', $formattedDate)
                            ->first();
                        $verification_code = !empty($user->verification_code) ? $user->verification_code : '';

                        $message = "Hi, your verification code is: " . $newOTP . "";

                        $result = $this->sendSMSToUser(array('message' => $message, 'phone_number' => $mobileCode . $mobileNumber));

                        return $this->sendSuccessResponse($this->otpResend, ['verification_code' => $verification_code], config('constant.api_response.OK'));
                    } else {

                        return $this->sendFailureResponse($this->mobileNumberInvalid, config('constant.api_response.NOT_FOUND'));

                    }
                }
            } else {
                return $this->sendFailureResponse($this->noToken, config('constant.api_response.UNPROCESSABLE_ENTITY'));
            }
        } catch (ModelNotFoundException $ex) {
            return $this->sendFailureResponse($this->otpNotFound, config('constant.api_response.NOT_FOUND'));
        }
    } //End Method

    /*Login WS for consumer*/
    public function login(Request $request)
    {
        try {
            DB::beginTransaction();
            /*Check basic auth token*/
            $headerToken = $request->header('basicauthtoken');
            if ($headerToken == config('constant.api_access_token.API_AUTH')) {
                //get all the data and store it inside $data variable
                $data = $request->all();
                $validation = Validator::make($data, User::$userLoginValidationRules, User::$userLoginValidationMessages);
                if ($validation->fails()) {
                    $validationMessage = [];
                    $validationMessage = implode("\n ", $validation->errors()->all());
                    return $this->sendFailureResponse($validationMessage, config('constant.api_response.UNPROCESSABLE_ENTITY'));
                } else {
                    $userdata = array('email' => trim($request->email), 'password' => trim($request->password));
                    if (Auth::validate($userdata)) {
                        if (Auth::attempt($userdata)) {
                            if (Auth::user()->status == 0) {
                                /** if user accout is not active */
                                Auth::logout();
                                return $this->sendFailureResponse($this->loginInactiveErrorMsg, config('constant.api_response.UNPROCESSABLE_ENTITY'));
                            }
                            $deviceToken = $request->device_token;

                            $updateDevice = User::where('user_id', Auth::user()->user_id)
                                ->firstOrFail();
                            $updateDevice->device_token = $deviceToken;

                            $user = array();
                            if ($updateDevice->save()) {
                                $user = DB::table("users as A")
                                    ->select('A.user_id', 'A.first_name', 'A.last_name', 'A.email', 'A.mobile_code', 'A.mobile_number', 'A.latitude', 'A.longitude', 'A.device_token', 'A.role_id as user_role', 'A.profile_picture', 'B.date_of_birth', 'B.about', 'B.company', 'B.year_of_experience', 'B.other_number', 'B.zip_code', 'B.complete_address', 'B.ssn_security_number', 'B.license_number', 'B.background_verification_doc', 'B.chat_count', 'A.is_setting_completed')
                                    ->leftJoin('user_details as B', 'A.user_id', 'B.user_id')
                                    ->where('A.user_id', Auth::user()->user_id)
                                    ->first();
//                                $user['status'] = config('constant.api_response.OK');
                                //                                $user['message'] = $this->loginSuccessMsg;
                            }

                            //return response()->json($user);
                            return $this->sendSuccessResponse($this->loginSuccessMsg, $user, config('constant.api_response.OK'));
                        }
                    } else {
                        return $this->sendFailureResponse($this->loginErrorMsg, config('constant.api_response.UNPROCESSABLE_ENTITY'));
                    }
                }
            } else {
                return $this->sendFailureResponse($this->noToken, config('constant.api_response.UNPROCESSABLE_ENTITY'));
            }
        } catch (ModelNotFoundException $ex) {
            return $this->sendFailureResponse($this->userNotFound, config('constant.api_response.NOT_FOUND'));
        } catch (Exception $ex) {
            DB::rollBack();
            $message = $ex->getMessage() . '----' . $ex->getLine();
            return $this->sendFailureResponse($message, config('constant.api_response.UNPROCESSABLE_ENTITY'));
        }
    } //End Method

    /*Login with socialLogin*/
    public function socialLogin(Request $request)
    {
        try {
            /*Check basic auth token*/
            $headerToken = $request->header('basicauthtoken');
            if ($headerToken == config('constant.api_access_token.API_AUTH')) {
                //get all the data and store it inside $data variable
                $data = $request->all();
                $validation = Validator::make($data, User::$fbValidationRules, User::$fbValidationMessages);
                if ($validation->fails()) {
                    $validationMessage = [];
                    $validationMessage = implode("\n ", $validation->errors()->all());
                    return $this->sendFailureResponse($validationMessage, config('constant.api_response.UNPROCESSABLE_ENTITY'));
                } else {
                    $email = $request->email;
                    if (empty($email)) {
                        /** email is required for social siginin  */
                        return $this->sendFailureResponse($this->socialEmailRequired, config('constant.api_response.UNPROCESSABLE_ENTITY'));
                    }

                    $facebookId = $request->social_id;
                    $socialType = $request->social_type;
                    $firstName = $request->first_name;
                    $lastName = $request->last_name;
                    $deviceToken = $request->device_token;
                    $mobileNumber = '';
                    $user = User::where('email', $email)
                        ->where('status', 1)
                        ->firstOrFail();
                    /* If user found in DB then update and login*/
                    if ($firstName) {$user->first_name = $firstName;}
                    if ($lastName) {$user->last_name = $lastName;}
                    if ($email) {$user->email = $email;}
                    if ($email) {$user->device_token = $deviceToken;}
                    if ($mobileNumber) {$user->mobile_number = $mobileNumber;}
                    $user->social_id = $request->social_id;
                    $user->social_type = $request->social_type;
                    //$user->role_id = $request->role_id;
                    $user->status = 1;
                    $user->is_verified = 1;

                    if ($user->save()) {

                        $user = DB::table("users as A")
                            ->select('A.user_id', 'A.first_name', 'A.last_name', 'A.email', 'A.mobile_code', 'A.mobile_number', 'A.latitude', 'A.longitude', 'A.device_token', 'A.role_id as user_role', 'A.profile_picture', 'B.date_of_birth', 'B.about', 'B.company', 'B.year_of_experience', 'B.other_number', 'B.zip_code', 'B.complete_address', 'B.ssn_security_number', 'B.license_number', 'B.background_verification_doc', 'B.chat_count', 'A.is_setting_completed')
                            ->leftJoin('user_details as B', 'A.user_id', 'B.user_id')
                            ->where('A.user_id', $user->user_id)
                            ->first();

                        return $this->sendSuccessResponse($this->loginSuccessMsg, $user, config('constant.api_response.OK'));

                        //return $this->sendSuccessResponse($this->otpVerified, ["first_name" => $user->first_name ,"last_name" => $user->last_name,"email" =>$user->email], config('constant.api_response.OK'));
                    }
                }
            } else {
                return $this->sendFailureResponse($this->noToken, config('constant.api_response.UNPROCESSABLE_ENTITY'));
            }
        } catch (ModelNotFoundException $ex) {
            $user = new User();
            $user->social_id = $request->social_id;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->mobile_number = '';
            $user->social_id = $request->social_id;
            $user->social_type = $request->social_type;
            //$user->role_id = $request->role_id;
            $user->status = 1;
            $user->is_verified = 1;

            if ($user->save()) {

                $user = DB::table("users as A")
                    ->select('A.user_id', 'A.first_name', 'A.last_name', 'A.email', 'A.mobile_code', 'A.mobile_number', 'A.latitude', 'A.longitude', 'A.device_token', 'A.role_id as user_role', 'A.profile_picture', 'B.date_of_birth', 'B.about', 'B.company', 'B.year_of_experience', 'B.other_number', 'B.zip_code', 'B.complete_address', 'B.ssn_security_number', 'B.license_number', 'B.background_verification_doc', 'B.chat_count', 'A.is_setting_completed')
                    ->leftJoin('user_details as B', 'A.user_id', 'B.user_id')
                    ->where('A.user_id', $user->user_id)
                    ->first();

                return $this->sendSuccessResponse($this->otpVerified, $user, config('constant.api_response.OK'));
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $message = $ex->getMessage() . '----' . $ex->getLine();
            return $this->sendFailureResponse($message, config('constant.api_response.UNPROCESSABLE_ENTITY'));
        }
    } //End Method

    /*Forgot password*/
    public function forgotPassword(Request $request)
    {

        DB::beginTransaction();
        try {
            /*Check basic auth token*/
            $headerToken = $request->header('basicauthtoken');
            if ($headerToken == config('constant.api_access_token.API_AUTH')) {

                $data = $request->all();
                $validation = Validator::make($data, User::$userForgetValidationRules, User::$userForgetValidationMessages);

                if ($validation->fails()) {

                    $validationMessage = [];
                    $validationMessage = implode("\n ", $validation->errors()->all());
                    return $this->sendFailureResponse($validationMessage, config('constant.api_response.UNPROCESSABLE_ENTITY'));

                } else {

                    $email = $request->email;
                    $password = $this->getRandomString(8);

                    $passwordHashed = Hash::make($password);

                    $userDetails = DB::table('users')->select('user_id', 'first_name', 'last_name', 'email')
                        ->where('email', $email)
                        ->where('status', 1)
                        ->get()->toArray();

                    if (is_array($userDetails) && !empty($userDetails)) {

                        $id = $userDetails[0]->user_id;
                        $name = $userDetails[0]->first_name . ' ' . $userDetails[0]->last_name;
                        $email = $userDetails[0]->email;

                        $user = User::find($id);
                        $user->password = $passwordHashed;
                        $user->save();

                        $datas = array('password' => $password, 'name' => $name);

                        //Send mail
                        Mail::send('mail', $datas, function ($message) use ($request) {
                            $message->to($request->email)->subject('Dou Gua - Forget Password');
                            $message->from('ravi_sipl@systematixindia.com');
                        });

                        DB::commit();
                        return $this->sendSuccessResponse($this->forgetPasswordSuccessMsg, array(), config('constant.api_response.OK'));
                    } else {

                        return $this->sendFailureResponse($this->userNotFound, config('constant.api_response.NOT_FOUND'));
                    }

                }

            } else {
                return $this->sendFailureResponse($this->noToken, config('constant.api_response.UNPROCESSABLE_ENTITY'));
            }

        } catch (Exception $ex) {

            DB::rollBack();
            $data['status'] = 0;
            $data['message'] = $ex->getMessage() . " on line number: " . $ex->getLine();
            return response()->json($data);

        }
    } //End Method

    /*switchMode*/
    public function switchMode(Request $request)
    {

        DB::beginTransaction();
        try {

            /*Check basic auth token*/
            $headerToken = $request->header('basicauthtoken');

            if ($headerToken == config('constant.api_access_token.API_AUTH')) {

                $userId = $request->user_id;
                $userRole = $request->user_role;

                if (!empty($userId) && !empty($userRole)) {

                    $user = User::find($userId);
                    $user->role_id = $userRole;
                    $user->is_setting_completed = 0;
                    $user->save();

                    $userData = DB::table("users as A")
                        ->select('A.user_id', 'A.first_name', 'A.last_name', 'A.email', 'A.mobile_code', 'A.mobile_number', 'A.latitude', 'A.longitude', 'A.device_token', 'A.role_id as user_role', 'A.profile_picture', 'B.date_of_birth', 'B.about', 'B.company', 'B.year_of_experience', 'B.other_number', 'B.zip_code', 'B.complete_address', 'B.ssn_security_number', 'B.license_number', 'B.background_verification_doc', 'B.chat_count', 'A.is_setting_completed')
                        ->leftJoin('user_details as B', 'A.user_id', 'B.user_id')
                        ->where('A.user_id', $user->user_id)
                        ->first();

                    DB::commit();
                    return $this->sendSuccessResponse($this->modeChanged, $userData, config('constant.api_response.OK'));

                } else {

                    return $this->sendFailureResponse($this->userNotFound, config('constant.api_response.NOT_FOUND'));
                }

            } else {

                return $this->sendFailureResponse($this->noToken, config('constant.api_response.UNPROCESSABLE_ENTITY'));

            }

        } catch (Exception $ex) {

            DB::rollBack();
            $data['status'] = 0;
            $data['message'] = $ex->getMessage() . " on line number: " . $ex->getLine();
            return response()->json($data);

        }
    } //End Method

    /*Generate random password*/
    public function getRandomString($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@!#$';
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $string;
    } //End Method

    /*get Profile Provider*/
    public function getProfileProvider(Request $request)
    {
        try {
            DB::beginTransaction();
            /*Check basic auth token*/
            $headerToken = $request->header('basicauthtoken');
            if ($headerToken == config('constant.api_access_token.API_AUTH')) {

                if (!empty($request->user_id)) {

                    $userExist = DB::table("users")->where('user_id', $request->user_id)->where('role_id', 3)->count();

                    if (isset($userExist) && $userExist > 0) {

                        $user['userDetails'] = DB::table("users as A")
                            ->select('A.user_id', 'A.first_name', 'A.last_name', 'profile_picture', 'A.email', 'A.mobile_code', 'A.mobile_number', 'A.latitude', 'A.longitude', 'A.device_token', 'A.role_id as user_role', 'A.profile_picture', 'B.date_of_birth', 'B.about', 'B.company', 'B.year_of_experience', 'B.other_number', 'B.zip_code', 'B.complete_address', 'B.ssn_security_number', 'B.license_number', 'B.background_verification_doc', 'B.chat_count', 'A.is_setting_completed')
                            ->leftJoin('user_details as B', 'A.user_id', 'B.user_id')
                            ->where('A.user_id', $request->user_id)
                            ->first();

                        $user['userDetails']->userCategory = DB::table("user_categories as A")
                            ->select('A.category_id', 'B.category_name')
                            ->leftJoin('category as B', 'A.category_id', 'B.category_id')
                            ->leftJoin('sub_category as C', 'A.sub_category_id', 'C.sub_category_id')
                            ->where('A.user_id', $request->user_id)
                            ->groupBy("A.category_id")
                            ->pluck('A.category_id')->toArray();

                        $user['userDetails']->userSubCategory = DB::table("user_categories as A")
                            ->select('A.sub_category_id', 'C.sub_category_name')
                            ->leftJoin('category as B', 'A.category_id', 'B.category_id')
                            ->leftJoin('sub_category as C', 'A.sub_category_id', 'C.sub_category_id')
                            ->where('A.user_id', $request->user_id)
                            ->get();

                        $user['userDetails']->userMedia = DB::table("user_media")
                            ->select('media_id', 'user_id', 'media_path', 'media_name', 'media_type')
                            ->where('user_id', $request->user_id)
                            ->orderBy('media_id', 'DESC')
                            ->get();

                        $array = array_map(function ($v) {
                            return (is_null($v)) ? "" : $v;
                        }, $user);

                        return $this->sendSuccessResponse($this->providerInformationFound, $array, config('constant.api_response.OK'));
                    } else {
                        return $this->sendFailureResponse($this->userNotFound, config('constant.api_response.NOT_FOUND'));
                    }

                }

            } else {
                return $this->sendFailureResponse($this->noToken, config('constant.api_response.UNPROCESSABLE_ENTITY'));
            }
        } catch (ModelNotFoundException $ex) {
            return $this->sendFailureResponse($this->userNotFound, config('constant.api_response.NOT_FOUND'));
        } catch (Exception $ex) {
            DB::rollBack();
            $message = $ex->getMessage() . '----' . $ex->getLine();
            return $this->sendFailureResponse($message, config('constant.api_response.UNPROCESSABLE_ENTITY'));
        }
    } //End Method

    /*get Profile Requester*/
    public function getProfileRequester(Request $request)
    {
        try {
            DB::beginTransaction();
            /*Check basic auth token*/
            $headerToken = $request->header('basicauthtoken');

            if ($headerToken == config('constant.api_access_token.API_AUTH')) {

                if (!empty($request->user_id)) {

                    $userExist = DB::table("users")->where('user_id', $request->user_id)->where('role_id', 2)->count();

                    if (isset($userExist) && $userExist > 0) {

                        $user['userDetails'] = DB::table("users")
                            ->select('user_id', 'profile_picture', 'first_name', 'last_name', 'mobile_code', 'mobile_number', 'email')
                            ->where('user_id', $request->user_id)
                            ->first();

                        return $this->sendSuccessResponse($this->requesterInformationFound, $user, config('constant.api_response.OK'));
                    } else {
                        return $this->sendFailureResponse($this->userNotFound, config('constant.api_response.NOT_FOUND'));
                    }

                } else {
                    return $this->sendFailureResponse($this->userNotFound, config('constant.api_response.NOT_FOUND'));
                }

            } else {
                return $this->sendFailureResponse($this->noToken, config('constant.api_response.UNPROCESSABLE_ENTITY'));
            }
        } catch (ModelNotFoundException $ex) {
            return $this->sendFailureResponse($this->userNotFound, config('constant.api_response.NOT_FOUND'));
        } catch (Exception $ex) {
            DB::rollBack();
            $message = $ex->getMessage() . '----' . $ex->getLine();
            return $this->sendFailureResponse($message, config('constant.api_response.UNPROCESSABLE_ENTITY'));
        }
    } //End Method

    /*get Profile Update*/
    public function getProfileUpdate(Request $request)
    {
        try {
            DB::beginTransaction();
            /*Check basic auth token*/
            $headerToken = $request->header('basicauthtoken');

            if ($headerToken == config('constant.api_access_token.API_AUTH')) {

                if (!empty($request->user_id) && !empty($request->user_role)) {

                    $userExist = DB::table("users")->where('user_id', $request->user_id)->where('role_id', $request->user_role)->count();

                    if ($userExist > 0 && $request->user_role == 2) {

                        $firstName = !empty($request->first_name) ? $request->first_name : "";
                        $lastName = !empty($request->last_name) ? $request->last_name : "";
                        $mobileCode = !empty($request->mobile_code) ? $request->mobile_code : "";
                        $mobileNumber = !empty($request->mobile_number) ? $request->mobile_number : "";
                        $email = !empty($request->email) ? $request->email : "";

                        DB::table('users')
                            ->where('user_id', $request->user_id)
                            ->where('role_id', $request->user_role)
                            ->update(['first_name' => $firstName, 'last_name' => $lastName, 'mobile_code' => $mobileCode, 'mobile_number' => $mobileNumber,
                                "email" => $email,
                            ]);

                        if (!empty($firstName) && !empty($lastName) && !empty($mobileCode) && !empty($mobileNumber) && !empty($email)) {
                            DB::table('users')
                                ->where('user_id', $request->user_id)
                                ->update(['is_setting_completed' => 1]);
                        }

                        DB::commit();

                        $user = DB::table("users as A")
                            ->select('A.user_id', 'A.first_name', 'A.last_name', 'A.email', 'A.mobile_code', 'A.mobile_number', 'A.latitude', 'A.longitude', 'A.device_token', 'A.role_id as user_role', 'A.profile_picture', 'B.date_of_birth', 'B.about', 'B.company', 'B.year_of_experience', 'B.other_number', 'B.zip_code', 'B.complete_address', 'B.ssn_security_number', 'B.license_number', 'B.background_verification_doc', 'B.chat_count', 'A.is_setting_completed')
                            ->leftJoin('user_details as B', 'A.user_id', 'B.user_id')
                            ->where('A.user_id', $request->user_id)
                            ->first();

                        return $this->sendSuccessResponse($this->requesterProfileUpdated, $user, config('constant.api_response.OK'));

                    } elseif ($userExist > 0 && $request->user_role == 3) {

                        //Step 1
                        $firstName = !empty($request->first_name) ? $request->first_name : "";
                        $lastName = !empty($request->last_name) ? $request->last_name : "";
                        $mobileCode = !empty($request->mobile_code) ? $request->mobile_code : "";
                        $mobileNumber = !empty($request->mobile_number) ? $request->mobile_number : "";
                        $otherNumber = !empty($request->other_number) ? $request->other_number : "";
                        $dateOfBirth = !empty($request->date_of_birth) ? $request->date_of_birth : "";
                        $email = !empty($request->email) ? $request->email : "";

                        //Step 2
                        $about = !empty($request->about) ? $request->about : "";
                        $company = !empty($request->company) ? $request->company : "";
                        $yearExperience = !empty($request->year_experience) ? $request->year_experience : "";
                        $address = !empty($request->complete_address) ? $request->complete_address : "";
                        $securityLicenseNumber = !empty($request->ssn_security_number) ? $request->ssn_security_number : "";
                        $licenseNumber = !empty($request->license_number) ? $request->license_number : "";
                        //$background_verification_doc = !empty($request->background_verification_doc)?$request->background_verification_doc:"";

                        //Step 3
                        //$isSettingCompleted = !empty($request->is_setting_completed)?$request->is_setting_completed:"";
                        $categoryId = !empty($request->category_id) ? $request->category_id : "";
                        $subCategoryId = !empty($request->sub_category_id) ? $request->sub_category_id : "";

                        if ($request->step_key == 1) {
                            //Step 1 Update
                            DB::table('users')
                                ->where('user_id', $request->user_id)
                                ->where('role_id', $request->user_role)
                                ->update(['first_name' => $firstName, 'last_name' => $lastName, 'mobile_code' => $mobileCode, 'mobile_number' => $mobileNumber, 'email' => $email]);

                            $userDetailsExist = DB::table("user_details")->where('user_id', $request->user_id)->count();
                            if ($userDetailsExist > 0) {

                                DB::table('user_details')
                                    ->where('user_id', $request->user_id)
                                    ->update([
                                        'date_of_birth' => date('Y-m-d', strtotime($dateOfBirth)),
                                        'other_number' => $otherNumber,
                                    ]);

                            } else {

                                DB::table('user_details')->insert(
                                    [
                                        'user_id' => $request->user_id,
                                        'date_of_birth' => date('Y-m-d', strtotime($dateOfBirth)),
                                        'other_number' => $otherNumber,
                                    ]);

                            }

                        }

                        if ($request->step_key == 2) {
                            //Step 2 Update / Insert
                            $userDetailsExist = DB::table("user_details")->where('user_id', $request->user_id)->count();
                            if ($userDetailsExist > 0) {

                                DB::table('user_details')
                                    ->where('user_id', $request->user_id)
                                    ->update([
//                                            'date_of_birth' => date('Y-m-d', strtotime($dateOfBirth)),
                                        'about' => $about,
                                        'company' => $company,
                                        'year_of_experience' => $yearExperience,
//                                            'other_number' => $otherNumber,
                                        'zip_code' => $mobileNumber,
                                        'complete_address' => $address,
                                        'ssn_security_number' => $securityLicenseNumber,
                                        'license_number' => $licenseNumber,
                                        //'background_verification_doc' => $background_verification_doc
                                    ]);

                            } else {

                                DB::table('user_details')->insert(
                                    [
                                        'user_id' => $request->user_id,
//                                        'date_of_birth' => date('Y-m-d', strtotime($dateOfBirth)),
                                        'about' => $about,
                                        'company' => $company,
                                        'year_of_experience' => $yearExperience,
//                                        'other_number' => $otherNumber,
                                        'zip_code' => $mobileNumber,
                                        'complete_address' => $address,
                                        'ssn_security_number' => $securityLicenseNumber,
                                        'license_number' => $licenseNumber,
                                        //'background_verification_doc' => $background_verification_doc
                                    ]);

                            }
                        }

                        if ($request->step_key == 3) {
                            //Step 3
                            if (!empty($categoryId) && !empty($subCategoryId) && is_array($subCategoryId)) {
                                DB::table('user_categories')->where('user_id', $request->user_id)->delete();
                                DB::commit();

                                $allSubCategories = DB::table("sub_category")
                                    ->select('sub_category.sub_category_id', 'sub_category.category_id')
                                    ->whereIn('sub_category_id', $subCategoryId)
                                    ->get();

                                foreach ($allSubCategories as $subCategory) {
                                    DB::table('user_categories')->insert(
                                        [
                                            'user_id' => $request->user_id,
                                            'category_id' => $subCategory->category_id,
                                            'sub_category_id' => $subCategory->sub_category_id,
                                        ]);
                                }
                                DB::table('users')
                                    ->where('user_id', $request->user_id)
                                    ->update(['is_setting_completed' => 1]);
                            }
                        }
                        DB::commit();

                        $user = DB::table("users as A")
                            ->select('A.user_id', 'A.first_name', 'A.last_name', 'A.email', 'A.mobile_code', 'A.mobile_number', 'A.latitude', 'A.longitude', 'A.device_token', 'A.role_id as user_role', 'A.profile_picture', 'B.date_of_birth', 'B.about', 'B.company', 'B.year_of_experience', 'B.other_number', 'B.zip_code', 'B.complete_address', 'B.ssn_security_number', 'B.license_number', 'B.license_number', 'B.background_verification_doc', 'B.chat_count', 'A.is_setting_completed')
                            ->leftJoin('user_details as B', 'A.user_id', 'B.user_id')
                            ->where('A.user_id', $request->user_id)
                            ->first();

                        DB::commit();
                        return $this->sendSuccessResponse($this->providerProfileUpdated, $user, config('constant.api_response.OK'));

                    } else {
                        return $this->sendFailureResponse($this->userNotFound, config('constant.api_response.NOT_FOUND'));
                    }

                } else {
                    return $this->sendFailureResponse($this->userNotFound, config('constant.api_response.NOT_FOUND'));
                }

            } else {
                return $this->sendFailureResponse($this->noToken, config('constant.api_response.UNPROCESSABLE_ENTITY'));
            }
        } catch (ModelNotFoundException $ex) {
            return $this->sendFailureResponse($this->userNotFound, config('constant.api_response.NOT_FOUND'));
        } catch (Exception $ex) {
            DB::rollBack();
            $message = $ex->getMessage() . '----' . $ex->getLine();
            return $this->sendFailureResponse($message, config('constant.api_response.UNPROCESSABLE_ENTITY'));
        }
    } //End Method

    /*Profile image upload*/
    public function profileImageUpload(Request $request)
    {

        try {
            DB::beginTransaction();
            /*Check basic auth token*/
            $headerToken = $request->header('basicauthtoken');

            if ($headerToken == config('constant.api_access_token.API_AUTH')) {

                if (!empty($request->user_id)) {

                    $userExist = DB::table("users")->where('user_id', $request->user_id)->count();

                    if (isset($userExist) && $userExist > 0) {

                        if ($request->has('upload_picture')) {
                            $data = $request->upload_picture;
                            $userImageUrl = config('constant.common.USER_PROFILE_IMAGE');

                            $finalName = '';
                            $response = $this->uploadImg($data);
                            if ($response['status'] == false) {
                                return $this->sendFailureResponse($this->fileUploadError, config('constant.api_response.UNPROCESSABLE_ENTITY'));
                            } else {
                                $finalName = $userImageUrl . $response['filename'];
                            }

                            User::where('user_id', $request->user_id)
                                ->update(['profile_picture' => $finalName]);
                            /*
                            $userProfile = User::where('user_id', $request->user_id)->select('profile_picture')->first();

                            $profImage = !empty($userProfile->profile_picture)?$userProfile->profile_picture:'';*/
                            DB::commit();
                            return $this->sendSuccessResponse($this->profileImageUploaded, $finalName, config('constant.api_response.OK'));

                        } else {
                            return $this->sendFailureResponse($this->fileUploadError, config('constant.api_response.UNPROCESSABLE_ENTITY'));
                        }

                    } else {

                        return $this->sendFailureResponse($this->userNotFound, config('constant.api_response.NOT_FOUND'));

                    }

                } else {
                    return $this->sendFailureResponse($this->userNotFound, config('constant.api_response.NOT_FOUND'));
                }

            } else {
                return $this->sendFailureResponse($this->noToken, config('constant.api_response.UNPROCESSABLE_ENTITY'));
            }

        } catch (Exception $ex) {
            DB::rollBack();
            $data['status'] = 0;
            $data['message'] = $ex->getMessage() . " on line number: " . $ex->getLine();
            return response()->json($data);
        }
    } //End Function

    public function uploadImg($data)
    {
        $newImageName = rand() . time('i');

        $uploadPath = "uploads/user_profile/";
        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                //throw new \Exception('invalid image type');
                return ['status' => false, 'message' => 'only accept jpg, jpeg, gif and png images.'];
            }

            $data = base64_decode($data);

            if ($data === false) {
                //throw new \Exception('base64_decode failed');
                return ['status' => false, 'message' => 'base64_decode failed'];
            }
        } else {
            return ['status' => false, 'message' => 'did not match data URI with image data'];
            //throw new \Exception('did not match data URI with image data');
        }

        file_put_contents($uploadPath . $newImageName . '.' . $type, $data);
        return ['status' => true, 'filename' => $newImageName . '.' . $type];
    }

    /*upload user media*/
    public function uploadUserMedia(Request $request)
    {
        try {
            DB::beginTransaction();
            /*Check basic auth token*/
            $headerToken = $request->header('basicauthtoken');

            if ($headerToken == config('constant.api_access_token.API_AUTH')) {

                if (!empty($request->user_id)) {

                    $userExist = DB::table("users")
                        ->where('user_id', $request->user_id)
                        ->where('role_id', 3)
                        ->count();

                    if (isset($userExist) && $userExist > 0) {

                        if ($request->has('upload_picture')) {

                            $data = $request->upload_picture;
                            $userImageUrl = config('constant.common.PROVIDER_WORK_HISTORY');

                            $finalName = '';
                            $response = $this->uploadMediaImage($data);
                            if ($response['status'] == false) {
                                return $this->sendFailureResponse($this->fileUploadError, config('constant.api_response.UNPROCESSABLE_ENTITY'));
                            } else {
                                $finalName = $userImageUrl . $response['filename'];
                            }
                            //, 'media_type' => $file->getClientOriginalExtension()
                            DB::table('user_media')->insert([
                                ['media_path' => $finalName, 'media_name' => $response['filename'], 'user_id' => $request->user_id],
                            ]);

//                            foreach($request->file('upload_picture') as $file){
                            //                                //$file = $request->file('upload_picture');
                            //                                $newImageName = rand().time('i').'.'.$file->getClientOriginalExtension();
                            //                                $file->move('uploads/user_media/', $newImageName);
                            //
                            //                                $finalName = $userImageUrl.$newImageName;

                            //}
                            $userMedia = DB::table("user_media")
                                ->select('media_id', 'user_id', 'media_path', 'media_name', 'media_type')
                                ->where('user_id', $request->user_id)
                                ->orderBy('media_id', 'DESC')
                                ->get();
                            DB::commit();
                            return $this->sendSuccessResponse($this->workHistoryUploaded, $userMedia, config('constant.api_response.OK'));
                        } else {
                            return $this->sendFailureResponse($this->fileUploadError, config('constant.api_response.UNPROCESSABLE_ENTITY'));
                        }

                    } else {

                        return $this->sendFailureResponse($this->userNotFound, config('constant.api_response.NOT_FOUND'));

                    }

                } else {
                    return $this->sendFailureResponse($this->userNotFound, config('constant.api_response.NOT_FOUND'));
                }

            } else {
                return $this->sendFailureResponse($this->noToken, config('constant.api_response.UNPROCESSABLE_ENTITY'));
            }

        } catch (Exception $ex) {
            DB::rollBack();
            $data['status'] = 0;
            $data['message'] = $ex->getMessage() . " on line number: " . $ex->getLine();
            return response()->json($data);
        }
    } //End Function

    public function uploadMediaImage($data)
    {
        $newImageName = rand() . time('i');

        $uploadPath = "uploads/user_media/";
        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                //throw new \Exception('invalid image type');
                return ['status' => false, 'message' => 'only accept jpg, jpeg, gif and png images.'];
            }

            $data = base64_decode($data);

            if ($data === false) {
                //throw new \Exception('base64_decode failed');
                return ['status' => false, 'message' => 'base64_decode failed'];
            }
        } else {
            return ['status' => false, 'message' => 'did not match data URI with image data'];
            //throw new \Exception('did not match data URI with image data');
        }

        file_put_contents($uploadPath . $newImageName . '.' . $type, $data);
        return ['status' => true, 'filename' => $newImageName . '.' . $type];
    }

    /*delete user media*/
    public function deleteUserMedia(Request $request)
    {
        try {
            DB::beginTransaction();
            /*Check basic auth token*/
            $headerToken = $request->header('basicauthtoken');

            if ($headerToken == config('constant.api_access_token.API_AUTH')) {

                if (!empty($request->media_id) && !empty($request->user_id)) {

                    $mediaExist = DB::table("user_media")
                        ->where('user_id', $request->user_id)
                        ->where('media_id', $request->media_id)
                        ->first();

                    if (!empty($mediaExist->media_name)) {

                        DB::table('user_media')->where('media_id', $request->media_id)->where('user_id', $request->user_id)->delete();

                        $base_directory = './uploads/user_media/';
                        @unlink($base_directory . $mediaExist->media_name);
                        DB::commit();
                        return $this->sendSuccessResponse($this->workHistoryDeleted, [], config('constant.api_response.OK'));

                    } else {

                        return $this->sendFailureResponse($this->mediaNotFound, config('constant.api_response.NOT_FOUND'));

                    }

                } else {
                    return $this->sendFailureResponse($this->mediaNotFound, config('constant.api_response.NOT_FOUND'));
                }

            } else {
                return $this->sendFailureResponse($this->noToken, config('constant.api_response.UNPROCESSABLE_ENTITY'));
            }

        } catch (Exception $ex) {
            DB::rollBack();
            $data['status'] = 0;
            $data['message'] = $ex->getMessage() . " on line number: " . $ex->getLine();
            return response()->json($data);
        }
    } //End Function

    /*updatePassword*/
    public function updatePassword(Request $request)
    {

        DB::beginTransaction();
        try {

            /*Check basic auth token*/
            $headerToken = $request->header('basicauthtoken');

            if ($headerToken == config('constant.api_access_token.API_AUTH')) {

                $requestCurrentPassword = $request->current_password;
                $newPassword = $request->new_password;
                $current_password = DB::table('users')->select('password', 'email', 'first_name')->where('user_id', $request->user_id)->first();

                if (!empty($current_password->password)) {
                    if (Hash::check($requestCurrentPassword, $current_password->password)) {
                        $userId = $request->user_id;
                        $objUser = User::find($userId);
                        $objUser->password = Hash::make($newPassword);
                        $objUser->save();

//                          $datas  = array('password' => $request->new_password, 'name' => $current_password->first_name);
                        //
                        //                        //Send mail
                        //                        Mail::send('mail', $datas, function($message) use($request) {
                        //                            $message->to($current_password->email)->subject('Dou Gua - Password Changed');
                        //                            $message->from('ravi_sipl@systematixindia.com');
                        //                          });

                        DB::commit();
                        return $this->sendSuccessResponse($this->passwordUpdated, array(), config('constant.api_response.OK'));
                    } else {
                        return $this->sendFailureResponse($this->userNotFound, config('constant.api_response.NOT_FOUND'));
                    }
                } elseif (empty($current_password->password)) {

                    $userId = $request->user_id;
                    $objUser = User::find($userId);
                    $objUser->password = Hash::make($newPassword);
                    $objUser->save();

                    DB::commit();
                    return $this->sendSuccessResponse($this->passwordUpdated, array(), config('constant.api_response.OK'));

                } else {
                    return $this->sendFailureResponse($this->userNotFound, config('constant.api_response.NOT_FOUND'));
                }

            } else {

                return $this->sendFailureResponse($this->noToken, config('constant.api_response.UNPROCESSABLE_ENTITY'));

            }

        } catch (Exception $ex) {

            DB::rollBack();
            $data['status'] = 0;
            $data['message'] = $ex->getMessage() . " on line number: " . $ex->getLine();
            return response()->json($data);

        }
    } //End Method

    public function test(Request $request)
    {
        try {
            echo "testing";
            $response = $this->sendSMSToUser(array('message' => "Hi, I am checking the message service", 'phone_number' => "+618319237876"));
            echo "<pre>";
            print_r($response);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
