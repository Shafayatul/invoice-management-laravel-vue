<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class IncomeController extends Controller
{
    protected $data_per_page = 10;

    public function __construct(Request $request)
    {
        if(isset($request->per_page))
            $this->data_per_page = $request->per_page;
    }

    public function index(Request $request)
    {
        if(Auth::user()->role == 'admin'){
            $incomes = Income::with(['createdBy', 'client', 'category', 'invoice'])
            ->whereHas('createdBy', function($user){
                $user->where('company_id', Auth::user()->company_id);
            })
            ->simplePaginate($this->data_per_page);
        }else{
            $incomes = Income::with(['createdBy', 'client', 'category', 'invoice'])->simplePaginate($this->data_per_page);
        }
        return response()->json([
            'incomes' => $incomes,
            'status'    => 'success',
            'code'      => 200
        ], 200);
    }

    public function show(Request $request)
    {
        $income = Income::with(['createdBy', 'client', 'category', 'invoice'])->find($request->income_id);
        
        if (!$income) {
            return response()->json([
                'success' => false,
                'message' => 'Income not found '
            ], 404);
        }
 
        return response()->json([
            'success' => true,
            'data' => $income->toArray()
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'category_id'     => 'required|array',
            'category_id.*'   => 'required|exists:payment_categories,id',
            'client_id'       => 'required|array',
            'client_id.*'     => 'required|exists:users,id',
            'receipt_file'    => 'array',
            'receipt_file.*'  => 'mimes:jpg,png,jpeg,pdf',
            'income_amount'   => 'required|array',
            'income_amount.*' => 'required|numeric'
        ]);

        if($validate->fails())
            return response()->json(['error' => $validate->errors()], 422);

        foreach($request->category_id as $key => $category_id){
            if($request->hasFile('receipt_file')){
                $file = $request->file('receipt_file')[$key];
                $name = uniqid().'.'.strtolower($file->getClientOriginalExtension());
                $path = $request->file('receipt_file')[$key]->storeAs(
                    'income-file',
                    $name,
                    'public'
                );
            }else{
                $path = null;
            }
    
            $income                = new Income();
            $income->category_id   = $category_id;
            $income->client_id     = $request->client_id[$key];
            $income->created_by    = Auth::id();
            $income->receipt_file  = $path;
            $income->income_amount = $request->income_amount[$key];
            $income->save();
        }

        if($income->id > 0)
            return response()->json([
                'success' => true,
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Income not added'
            ], 500);
    }

    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'category_id'     => 'required|array',
            'category_id.*'   => 'required|exists:payment_categories,id',
            'client_id'       => 'required|array',
            'client_id.*'     => 'required|exists:users,id',
            'receipt_file'    => 'array',
            'receipt_file.*'  => 'mimes:jpg,png,jpeg,pdf',
            'income_amount'   => 'required|array',
            'income_amount.*' => 'required|numeric'
        ]);

        if($validate->fails())
            return response()->json(['error' => $validate->errors()], 422);


        foreach($request->category_id as $key => $category_id){

            $income = Income::find($key);

            if (!$income) {
                return response()->json([
                    'success' => false,
                    'message' => 'Income not found'
                ], 400);
            }

            if($request->hasFile('receipt_file')){
                $file = $request->file('receipt_file')[$key];
                $name = uniqid().'.'.strtolower($file->getClientOriginalExtension());
                $path = $request->file('receipt_file')[$key]->storeAs(
                    'income-file',
                    $name,
                    'public'
                );

                if (Storage::disk('public')->exists($income->receipt_file)) {
                    Storage::delete($income->receipt_file);
                }

            }else{
                $path = $income->receipt_file;
            }

            $income->category_id   = $category_id;
            $income->client_id     = $request->client_id[$key];
            $income->created_by    = Auth::id();
            $income->receipt_file  = $path;
            $income->income_amount = $request->income_amount[$key];
            $income->save();
        }

        if ($income->id > 0)
            return response()->json([
                'success' => true
            ], 200);
        else
            return response()->json([
                'success' => false,
                'message' => 'Income can not be updated'
            ], 500);
    }

    public function destroy(Request $request){
        $income = Income::find($request->income_id);
        

        if (!$income) {
            return response()->json([
                'success' => false,
                'message' => 'Income not found'
            ], 404);
        }

        if (Storage::disk('public')->exists($income->receipt_file)) {
            Storage::delete($income->receipt_file);
        }


        if ($income->delete()) {
            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Income can not be deleted'
            ], 500);
        }
    }
}
