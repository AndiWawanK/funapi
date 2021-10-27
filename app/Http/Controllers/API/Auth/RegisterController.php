<?php

namespace App\Http\Controllers\API\Auth;
use DB;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function index(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        DB::beginTransaction();
        try{
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]);
            DB::commit();
            $token = $user->createToken($request->input('email'))->plainTextToken;
            return response()->json([
                'data' => $user,
                'access_token' => $token
            ], 201);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['error' => $e]);
        }
    }
}
