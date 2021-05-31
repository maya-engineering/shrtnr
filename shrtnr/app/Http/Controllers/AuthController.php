<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository =  $repository;
    }
 
    public function register(UserRegisterRequest $request){

        $this->repository->create($request->all());

        return response()->json(['status' => 200, 'message' => 'Registration Successfully Completed!'],200);

    }

    public function login(UserLoginRequest $request) {

        if(auth()->attempt($request->all())){

            $accessToken = auth()->user()->createToken('test')->accessToken;

        }else {

            $accessToken = false;

        }

        if($accessToken != false){

            return response()->json(['status' => 200, 'message' => 'User Login Successfully', 'token' => $accessToken],200);

        }else{

            return response()->json(['status' => 200, 'message' => 'Invalid Credential'], 200);

        }

    }

    public function logout(){
        
        $user = Auth::user()->token()->revoke();

        if($user){

            return response()->json(['status' => 200, 'message' => 'Logout Successfully!'],200);
        
        }

        return response()->json(['status' => 401, 'message' => 'Something Went Wrong!'], 401);

    }


}
