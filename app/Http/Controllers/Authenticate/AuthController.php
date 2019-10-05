<?php

namespace App\Http\Controllers\Authenticate;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
    }
    public function authenticate(Request $request)
    {
        $rules = [
            'email' => 'required|email|max:255',
            'password' => 'required|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=>'Whoops! invalid information for authentication', 'result'=>'email and password are required'],500);
        }
        $credentials = $request->only('email', 'password');
        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['status'=>false, 'error' => 'Whoops! invalid credential used!', 'result'=>null], 401);
            } else{
                $user= JWTAuth::toUser($token);
                if ($user instanceof User) {
                    if ($user->is_active and !$user->is_deleted) {
                        return response()->json(['status'=>true, 'message'=> 'successfully authenticated', 'token'=>$token, 'result'=>$user],200);
                    } else {
                        return response()->json(['status'=>false, 'error'=>'Whoops! your account is disabled', 'result'=>null],500);
                    }
                } else {
                    return response()->json(['status'=>false, 'error'=>'Whoops! user not found', 'result'=>null],500);
                }
            }
        } catch (JWTException $e) {
            return response()->json(['status'=>false, 'message'=>$e->getMessage(), 'result'=>null],500);
        }
    }

    public function register(Request $request)
    {
        try {
            $inputData = $request->only(['full_name', 'email', 'password', 'phone']);
            $rules = [
                'email' => 'required|email',
                'password' => 'required|max:255',
                'full_name' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status'=>false, 'error'=>'Whoops! invalid information for registration', 'result'=>null, 'message'=>$validator->messages()],500);
            }
            $old_user = User::where('email', '=', $inputData['email'])->first();
            if ($old_user instanceof User) {
                return response()->json(['status'=>false, 'error'=>'Whoops! this email is already taken by another user', 'result'=>null],500);
            }
            $newUser = new User();
            $newUser->full_name = $inputData['full_name'];
            $newUser->email = $inputData['email'];
            $newUser->phone = isset($inputData['phone'])? $inputData['phone']: null;
            $newUser->password = bcrypt($inputData['password']);
            $newUser->is_active = false;
            $newUser->user_type_id = 1;
            if($newUser->save()) {
//                dispatch(new SendWelcomeEmailTask($newUser));
                return response()->json(['status'=>true, 'message'=> 'successfully registered', 'result'=>$newUser],200);
            } else {
                return response()->json(['status'=>false, 'error'=>'Something went Wrong! unable to register', 'result'=>null],500);
            }
        } catch (\Exception $e) {
            return response()->json(['status'=>false, 'message'=>$e->getMessage(), 'result'=>null],500);
        }
    }

    public function resetPassword() {
        try{
            $credentials = request()->only(['email', 'password', 'reset_code']);
            $rules = [
                'email' => 'required|max:255',
                'password' => 'required|max:255',
                'reset_code' => 'required|max:255',
            ];
            $validator = Validator::make($credentials, $rules);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status'=>false, 'error'=>$error, 'result'=>null],500);
            }
            $cur_date = Carbon::now();
            $email_user = User::where('email', '=', $credentials['email'])->where('reset_code', '=', $credentials['reset_code'])->whereTime('reset_time', '<=', $cur_date)->first();
            if($email_user instanceof User){
                $email_user->password = bcrypt($credentials['password']);
                $email_user->reset_code = rand(100000, 999999);
                if($email_user->update()){
//                    dispatch(new SendEmailMessage($email_user, "You have successfully changed your login credential"));
                    return response()->json(['status'=>true, 'message'=> 'successfully changed login credentials', 'result'=>$email_user],200);
                } else {
                    return response()->json(['status'=>false, 'error'=>'Whoops! unable to reset your credentials', 'result'=>$cur_date],500);
                }
            } else{
                return response()->json(['status'=>false, 'error'=>'Whoops! unable to find user info', 'result'=>$cur_date],500);
            }
        }catch (\Exception $exception){
            return response()->json(['status'=>false, 'error'=>$exception->getMessage(), 'result'=>null],500);
        }
    }
    public function getResetCode() {
        try{
            $credentials = request()->only(['email']);
            $rules = [
                'email' => 'required|max:255',
            ];
            $validator = Validator::make($credentials, $rules);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status'=>false, 'error'=>$error, 'result'=>null],500);
            }
            $cur_date = Carbon::now();
            $email_user = User::where('email', '!=', null)->where('email', '=', $credentials['email'])->first();
            if($email_user instanceof User){
                $email_user->reset_code = rand(100000, 999999);
                $email_user->reset_time = $cur_date->addMinute(30);
                if($email_user->update()){
//                    dispatch(new SendEmailMessage($email_user, " if you are changing your login credential, your Reset Code is: " . $email_user->reset_code));
                    return response()->json(['status'=>true, 'message'=> 'we have sent a reset code to your phone and email'],200);
                } else {
                    return response()->json(['status'=>false, 'error'=>'Whoops! unable to update user info', 'result'=>null],500);
                }
            } else {
                return response()->json(['status'=>false, 'error'=>'Whoops! unable to update user info', 'result'=>null],500);
            }
        } catch (\Exception $exception){
            return response()->json(['status'=>false, 'error'=>$exception->getMessage(), 'result'=>null],500);
        }
    }
}
