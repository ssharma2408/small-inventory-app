<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Admin\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

use Hash;
use DB;

class RegisterController extends BaseController
{   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        
		if( ! isset($request->email)){
			return $this->sendError('Login Error', ['error'=>'Please pass Email']);
		}
		
		if( ! isset($request->password)){
			return $this->sendError('Login Error', ['error'=>'Please pass password']);
		}

		//check if clinic admin/doctor or staff
		
		if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){

			$user = Auth::user();
			
			$user_role = $user->roles->toArray()[0]['title'];
			
			$success['token'] =  $user->createToken('MyApp')->plainTextToken; 
			$success['name'] =  $user->name;
			$success['user_id'] =  $user->id;
			$success['role'] =  $user_role;			

			return $this->sendResponse($success, 'User login successfully.');			
		} 
		else{
			return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
		} 
		
		
    }	

}
