<?php

namespace App\Http\Controllers\Users;

use App\User;
use App\UserType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * UsersController constructor.
     */
    public function __construct()
    {
    }

    public function getUserTypes()
    {
        try {
            $userTypes = UserType::all();
            return response()->json(['status' => true, 'message' => 'user-types successfully fetched', 'result' => $userTypes], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Whoops! something went wrong', 'error' => $exception->getMessage()], 500);
        }
    }

    public function getUsers()
    {
        try {
            $users = User::with('user_type')->where('is_deleted', '=', false)->orderBy('full_name', 'ASC')->get();
            return response()->json(['status' => true, 'message' => 'users successfully fetched', 'result' => $users], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Whoops! something went wrong', 'error' => $exception->getMessage()], 500);
        }
    }

    public function getPaginatedUsers()
    {
        try {
            $paginate_num = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $users = User::with('user_type')->where('is_deleted', '=', false)->orderBy('full_name', 'ASC')->paginate($paginate_num);
            return response()->json(['status' => true, 'message' => 'users successfully fetched', 'result' => $users], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Whoops! something went wrong', 'error' => $exception->getMessage()], 500);
        }
    }

    public function create()
    {
        try {
            $credential = request()->only('full_name', 'email', 'phone', 'password', 'user_type_id');
            $rules = [
                'full_name' => 'required|max:255',
                'email' => 'required|email|max:255',
                'password' => 'required|min:4',
            ];
            $validator = Validator::make($credential, $rules);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['error' => $error], 500);
            }
            $oldUser = User::where('email', '=', $credential['email'])->first();
            if ($oldUser instanceof User) {
                return response()->json(['status' => false, 'message' => 'Whoops! this email address is already taken by other user', 'error' => 'email duplication'], 500);
            } else {
                $newUser = new User();
                $newUser->full_name = $credential['full_name'];
                $newUser->email = $credential['email'];
                $newUser->phone = isset($credential['phone'])? $credential['phone']: null;
                $newUser->password = bcrypt($credential['password']);
                $newUser->is_active = true;
                $newUser->user_type_id = isset($credential['user_type_id'])? $credential['user_type_id'] : 5;
                if ($newUser->save()) {
                    return response()->json(['status' => true, 'message' => 'successfully registered', 'result' => $newUser], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'Whoops! something went wrong~ try again'], 500);
                }
            }
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Whoops! something went wrong', 'error' => $exception->getMessage()], 500);
        }
    }

    public function update()
    {
        try {
            $credential = request()->only('id', 'full_name', 'email', 'phone', 'user_type_id', 'is_active');
            $rules = [
                'id' => 'required|max:255',
            ];
            $validator = Validator::make($credential, $rules);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['error' => $error], 500);
            }
            $oldUser = User::where('id', '=', $credential['id'])->first();
            if ($oldUser instanceof User) {
                $oldUser->full_name = isset($credential['full_name'])? $credential['full_name'] : $oldUser->full_name;
                $oldUser->email = isset($credential['email'])? $credential['email'] : $oldUser->email;
                $oldUser->phone = isset($credential['phone'])? $credential['phone'] : $oldUser->phone;
                $oldUser->is_active = isset($credential['is_active'])? $credential['is_active'] : $oldUser->is_active;
                $oldUser->user_type_id = isset($credential['user_type_id'])? $credential['user_type_id'] : $oldUser->user_type_id;
                if ($oldUser->update()) {
                    return response()->json(['status' => true, 'message' => 'successfully updated', 'result' => $oldUser], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'Whoops! something went wrong~ try again'], 500);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'Whoops! unable to find the user', 'error' => 'unknown user id'], 500);
            }
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'error' => $exception->getMessage()], 500);
        }
    }
    public function delete($id) {
        try {
            $oldUser = User::where('id', '=', $id)->where('is_deleted', '=', false)->first();
            if ($oldUser instanceof User) {
                $oldUser->email = $oldUser->email . ' | DELETED EMAIL |' . str_random(10);
                $oldUser->is_deleted = true;
                $oldUser->is_active = false;
                if ($oldUser->update()) {
                    return response()->json(['status' => true, 'message' => 'user successfully deleted'], 200);
                } else {
                    return response()->json(['status' => false, 'error' => 'Whoops! failed to delete the user account'], 500);
                }
            } else {
                return response()->json(['status' => false, 'error' => 'Whoops! unable to find the user information'], 500);
            }
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'error' => 'Whoops! something went wrong', 'message' => $exception->getMessage()], 500);
        }
    }
}
