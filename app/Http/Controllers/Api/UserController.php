<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Str;
class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required_without:phone|string|email|max:255|unique:users',
            'phone' => 'required_without:email|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user = User::create($request->toArray());
        $token = $user->createToken('Rcs')->accessToken;
        $response = ['token' => $token];
        return response($response, 200);
    }

    public function login(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'email'=>'required_without:phone|string|email|max:255',
            'phone'=>'required_without:email|string|max:255',
            'password'=>'required|string|min:6',
        ]);

        if($validator->fails()){
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        if(!auth()->attempt($request->toArray())){
            $responseMessage[]="Invalid Username or password";
            return response(['errors'=>$responseMessage],422);
        }

        $token=auth()->user()->createToken('Rcs')->accessToken;
        $response = ['token' => $token];
        return response($response, 200);

    }

    public function getuserlist(Request $request)
    {
        $userlists=User::orderBy('id','desc')->get();
        return response()->json([
            'data'=>UserResource::collection($userlists),
        ]);
    }
}
