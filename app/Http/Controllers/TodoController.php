<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Todo;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TodoController extends Controller
{
    public function  createtodo(Request $request) {

        try {
            $validatedData = $request->validate([
                'title' => 'required|string',
                'details' => 'required|string',
                'status' => 'required|in:completed,in progress,not started'
            ]);
    
            $todo = Todo::create($validatedData);
    
            return response()->json([
                "msg" => "Todo created successfully",
                "todo" => $todo
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'msg' => 'Error occured',
                'error' => $e->getMessage(),
            ], 500);
        }
        
    }

    public function updatetodo(Request $request, $id) {
        try {
        
        $todo = Todo::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'sometimes|required|string',
            'detail' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:completed,in progress,not started'
        ]);

        $todo->update($validatedData);

        return response()->json([
            'msg' => 'Todo updated successfully',
            'todo' => $todo,
        ], 200);
        } catch (Exception $e) {
            return response()->json([
                'msg' => 'Error occured',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function todos() {
        try {
            //code...
            $todo = Todo::all();

        return response()->json([$todo],200);
        } catch (Exception $e) {
            return response()->json([
                "msg" => "Error occured",
                'error' => $e->getMessage()
            ]);
        }
        
    }

    public function deletetodo($id) {

        try {
        $todo = Todo::findOrFail($id);

        $todo->delete();

        return response()->json([
            "msg" => "Todo with id $id deleted successfully"
        ], 200);
        } catch (Exception $e) {
            return response()->json([
                'msg' => 'Error occured',
                'error' => $e->getMessage()
            ]);
        }
        
    }

    public function todo($id) {

        try {
        $todo = Todo::findOrFail($id);

        return response()->json([$todo], 200);
            
        } catch (Exception $e) {
            return response()->json([
                "msg" => "Error occured",
                "error" => $e->getMessage()
            ]);
        }
        
    }

    public function filterbystatus(Request $request) {
        try {
            $search = $request->query('status');

            $users = QueryBuilder::for(Todo::class)->allowedFilters('status')->get();

            return response()->json([
                'msg' => "Todo list has being filtered by status $search",
                'todo' => $users 
            ]);    
        } catch (Exception $e) {
            return response()->json([
                'msg' => 'Error occured',
                'error' => $e->getMessage()
            ]);
        }
            
    }

    public function index(Request $request) {
        try {
            $query = Todo::query();

            if ($search = $request->query('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('details', 'like', '%' . $search . '%');
                });
            }

            $sortBy = $request->query('sort_by', 'created_at');
            $query->orderBy($sortBy);
            $todos = $query->paginate(10);
    
            return response()->json($todos, 200);
        } catch (Exception $e) {
            return response()->json([
                'msg'=>"Error occured",
                'error'=> $e->getMessage()
            ]);
        }  
    }
}
