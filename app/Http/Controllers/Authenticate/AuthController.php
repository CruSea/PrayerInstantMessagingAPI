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
            $newUser->user_type_id = 1;
            if($newUser->save()) {
                return response()->json(['status'=>true, 'message'=> 'successfully registered', 'result'=>$newUser],200);
            } else {
                return response()->json(['status'=>false, 'error'=>'Something went Wrong! unable to register', 'result'=>null],500);
            }
        } catch (\Exception $e) {
            return response()->json(['status'=>false, 'message'=>$e->getMessage(), 'result'=>null],500);
        }
    }
}
