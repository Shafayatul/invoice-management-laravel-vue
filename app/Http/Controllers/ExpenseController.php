<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    protected $data_per_page = 10;

    public function __construct(Request $request){
        if(isset($request->per_page))
            $this->data_per_page = $request->per_page;
    }

    public function index(Request $request) {
        if(Auth::user()->role == 'admin'){
            $expenses = Expense::with(['user', 'category', 'company'])->where('company_id', Auth::user()->company_id)->paginate($this->data_per_page);
        }else{
            $expenses = Expense::with(['user', 'category', 'company'])->paginate($this->data_per_page);
        }
        return response()->json([
            'expenses' => $expenses,
            'status'    => 'success',
            'code'      => 200
        ], 200);
    }
 
    public function show(Request $request)
    {
        $expense = Expense::with(['user', 'category', 'company'])->find($request->expense_id);
        
        if (!$expense) {
            return response()->json([
                'success' => false,
                'message' => 'Expense not found '
            ], 404);
        }
 
        return response()->json([
            'success' => true,
            'data' => $expense->toArray()
        ], 200);
    }
 
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'category_id' => 'required|array',
            'category_id.*' => 'required|exists:payment_categories,id',
            'bills_file' => 'array',
            'bills_file.*' => 'mimes:jpg,png,jpeg,pdf',
            'expense_date' => 'required|array',
            'expense_date.*' => 'required|date',
            'expense_amount' => 'required|array',
            'expense_amount.*' => 'required|numeric'
        ]);

        if($validate->fails())
            return response()->json(['error' => $validate->errors()], 422);

        foreach($request->category_id as $key => $category_id){
            if($request->hasFile('bills_file')){
                $file = $request->file('bills_file')[$key];
                $name = uniqid().'.'.strtolower($file->getClientOriginalExtension());
                $path = $request->file('bills_file')[$key]->storeAs(
                    'expense-file',
                    $name,
                    'public'
                );
            }else{
                $path = null;
            }
    
            $expense = new Expense();
            $expense->category_id = $category_id;
            $expense->user_id = Auth::id();
            $expense->company_id = Auth::user()->company_id;
            $expense->bills_file = $path;
            $expense->expense_date = Carbon::parse($request->expense_date[$key])->format("Y-m-d h:i:s");
            $expense->expense_amount = $request->expense_amount[$key];
            $expense->save();
        }

        

        if($expense->id > 0)
            return response()->json([
                'success' => true,
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Expense not added'
            ], 500);
    }
 
    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'category_id'      => 'required|array',
            'category_id.*'    => 'required|exists:payment_categories,id',
            'bills_file'       => 'array',
            'bills_file.*'     => 'mimes:jpg,png,jpeg,pdf',
            'expense_date'     => 'required|array',
            'expense_date.*'   => 'required|date',
            'expense_amount'   => 'required|array',
            'expense_amount.*' => 'required|numeric',
        ]);

        if($validate->fails())
            return response()->json(['error' => $validate->errors()], 422);


        foreach($request->category_id as $key => $category_id){

            $expense = Expense::find($key);

            if (!$expense) {
                return response()->json([
                    'success' => false,
                    'message' => 'Expense not found'
                ], 400);
            }

            if($request->hasFile('bills_file')){
                $file = $request->file('bills_file')[$key];
                $name = uniqid().'.'.strtolower($file->getClientOriginalExtension());
                $path = $request->file('bills_file')[$key]->storeAs(
                    'expense-file',
                    $name,
                    'public'
                );

                if (Storage::disk('public')->exists($expense->bills_file)) {
                    Storage::delete($expense->bills_file);
                }

            }else{
                $path = $expense->bills_file;
            }

            $expense->category_id    = $category_id;
            $expense->user_id        = Auth::id();
            $expense->company_id     = Auth::user()->company_id;
            $expense->bills_file     = $path;
            $expense->expense_date   = Carbon::parse($request->expense_date[$key])->format("Y-m-d h:i:s");
            $expense->expense_amount = $request->expense_amount[$key];
            $expense->save();
        }

        if ($expense->id > 0)
            return response()->json([
                'success' => true
            ], 200);
        else
            return response()->json([
                'success' => false,
                'message' => 'Expense can not be updated'
            ], 500);
    }
 
    public function destroy(Request $request){
        $expense = Expense::find($request->expense_id);

        if (!$expense) {
            return response()->json([
                'success' => false,
                'message' => 'Expense not found'
            ], 404);
        }

        if (Storage::disk('public')->exists($expense->bills_file)) {
            Storage::delete($expense->bills_file);
        }


        if ($expense->delete()) {
            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Expense can not be deleted'
            ], 500);
        }
    }
}
