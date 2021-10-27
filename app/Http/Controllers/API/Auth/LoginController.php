<?php

namespace App\Http\Controllers\API\Auth;
use Validator;
use DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    public function index(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        DB::beginTransaction();
        try{

            $user = User::where(['email' => $request->input('email')])->first();

            if(!$user){
                return response()->json(['message' => 'We could not find an account for that email address.'], 404);
            }elseif(!Hash::check($request->password, $user->password)){
                return response()->json(['message' => 'Wrong password!'], 401);
            }

            if($user->tokens()->where('name', $request->input('email'))->first()) {
                $user->tokens()->where('tokenable_id', $user->id)->delete();
            }

            $token = $user->createToken($request->input('email'))->plainTextToken;

            return response()->json([
                'data' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);

        }catch(Exception $e){
            DB::rollback();
            return response()->json(['error' => $e]);
        }
    }
}
