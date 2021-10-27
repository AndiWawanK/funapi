<?php

namespace App\Http\Controllers\API;
use DB;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function create(Request $request){
        $currentUser = $request->user();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        DB::beginTransaction();
        try{
            $todo = Todo::create([
                'user_id' => $currentUser->id,
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'iscomplete' => 0,
            ]);
            DB::commit();
            return response()->json($todo, 201);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['error' => $e]);
        }
    }

    public function showAll(Request $request){
        $currentUser = $request->user();
        $_DEFAULT_PAGE = 0;
        $_DEFAULT_LIMIT = 15;
        try{
            $limit = $request->limit ? : $_DEFAULT_LIMIT;
            $page = $request->page ? $request->page - 1 : $_DEFAULT_PAGE;
            $status = $request->status ? : null;
            if($status){
                if($status === 'ongoing'){
                    $todos = Todo::where([
                        ['user_id', '=', $currentUser->id],
                        ['iscomplete', '=', 0]
                    ])
                    ->skip($limit*$page)
                    ->take($limit)
                    ->orderBy('created_at', 'desc')
                    ->get();

                    return response()->json($todos);
                }
                $todos = Todo::where([
                    ['user_id', '=', $currentUser->id],
                    ['iscomplete', '=', 1]
                ])
                ->skip($limit*$page)
                ->take($limit)
                ->orderBy('created_at', 'desc')
                ->get();

                return response()->json($todos);
            }
            $todos = Todo::where('user_id', $currentUser->id)
                ->skip($limit*$page)
                ->take($limit)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($todos);
        }catch(Exception $e){
            return response()->json(['error' => $e]);
        }
    }

    public function detail(Request $request){
        $currentUser = $request->user();
        try{
            $todo = Todo::where([
                ['user_id', '=', $currentUser->id],
                ['id', '=', $request->todoId]
            ])->first();
            return response()->json($todo);
        }catch(Exception $e){
            return response()->json(['error' => $e]);
        }
    }

    public function delete(Request $request){
        $currentUser = $request->user();
        try{
            $todo = Todo::where([
                ['user_id', $currentUser->id],
                ['id', $request->todoId]
            ])->delete();
            return response()->json([
                'deleted' => true
            ]);
        }catch(Exception $e){
            return response()->json(['error' => $e]);
        }
    }
    
    public function update(Request $request){
        $currentUser = $request->user();
        DB::beginTransaction();
        try{
            $updateTodo = Todo::where([
                ['user_id', '=', $currentUser->id],
                ['id', '=', $request->todoId]
            ])->update($request->all());
            DB::commit();
            $todo = Todo::where([
                ['user_id', '=', $currentUser->id],
                ['id', '=', $request->todoId]
            ])->first();
            return response()->json($todo);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['error' => $e]);
        }
    }

    public function complete(Request $request){
        $currentUser = $request->user();
        DB::beginTransaction();
        try{
            $complete = Todo::where([
                ['user_id', '=', $currentUser->id],
                ['id', '=', $request->todoId]
            ])->update(['iscomplete' => 1]);
            DB::commit();
            return response()->json([
                'iscomplete' => true
            ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['error' => $e]);
        }
    }
}
