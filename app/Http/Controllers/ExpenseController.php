<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(){
        $expenses = auth()->user()->expenses;
 
        return response()->json([
            'success' => true,
            'data' => $expenses
        ]);
    }
 
    public function show($id)
    {
        $expense = auth()->user()->expenses()->find($id);
 
        if (!$expense) {
            return response()->json([
                'success' => false,
                'message' => 'Expense not found '
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $expense->toArray()
        ], 400);
    }
 
    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'title' => 'required',
        //     'description' => 'required'
        // ]);
 
        // $post = new Expense();
        // $post->title = $request->title;
        // $post->description = $request->description;
 
        // if (auth()->user()->posts()->save($post))
        //     return response()->json([
        //         'success' => true,
        //         'data' => $post->toArray()
        //     ]);
        // else
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Post not added'
        //     ], 500);
    }
 
    public function update(Request $request, $id)
    {
        $post = auth()->user()->posts()->find($id);
 
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 400);
        }

        $updated = $post->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Post can not be updated'
            ], 500);
    }
 
    public function destroy($id)
    {
        $post = auth()->user()->posts()->find($id);
 
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 400);
        }
 
        if ($post->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post can not be deleted'
            ], 500);
        }
    }
}
