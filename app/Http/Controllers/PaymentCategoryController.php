<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentCategory;
use Illuminate\Support\Facades\Validator;

class PaymentCategoryController extends Controller
{
    protected $data_per_page = 10;

    public function __construct(Request $request){
        if(isset($request->per_page))
            $this->data_per_page = $request->per_page;
    }

    public function index(Request $request) {
        $payment_categories = PaymentCategory::with(['incomes', 'expenses'])->paginate($this->data_per_page);
        return response()->json([
            'payment_categories' => $payment_categories,
            'status'             => 'success',
            'code'               => 200
        ], 200);
    }

    public function show(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'payment_category_id' => 'required|exists:payment_categories,id',
        ]);

        if($validate->fails())
            return response()->json(['error'=> $validate->errors()], 422);

        
        $payment_category = PaymentCategory::with(['incomes', 'expenses'])->find($request->payment_category_id);
        
        if (!$payment_category) {
            return response()->json([
                'success' => false,
                'message' => 'Payment Category not found '
            ], 404);
        }
 
        return response()->json([
            'success' => true,
            'data' => $payment_category->toArray()
        ], 200);
    }

    public function store(Request $request) {
        $validate = Validator::make($request->all(), [
            'name'    => 'required',
            'type'    => 'required',
            'details' => 'required|string|max:255'
        ]);

        if($validate->fails())
            return response()->json(['error'=> $validate->errors()], 422);
        
        $payment_category          = new PaymentCategory();
        $payment_category->name    = $request->name;
        $payment_category->type    = $request->type;
        $payment_category->details = $request->details;
        $payment_category->save();
 
        if($payment_category->id > 0)
            return response()->json([
                'success' => true,
                'data' => $payment_category->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Payment Category not added'
            ], 500);
    }

    public function update(Request $request){
        $validate = Validator::make($request->all(), [
            'name'    => 'required',
            'type'    => 'required',
            'details' => 'required|string|max:255',
            'payment_category_id' => 'required|exists:payment_categories,id',
        ]);

        if($validate->fails())
            return response()->json(['error'=> $validate->errors()], 422);

        $payment_category = PaymentCategory::find($request->payment_category_id);
 
        if (!$payment_category) {
            return response()->json([
                'success' => false,
                'message' => 'Payment Category not found'
            ], 404);
        }

        $updated = $payment_category->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ], 200);
        else
            return response()->json([
                'success' => false,
                'message' => 'Payment Category can not be updated'
            ], 500);
    }

    public function destroy(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'payment_category_id' => 'required|exists:payment_categories,id',
        ]);

        if($validate->fails())
            return response()->json(['error'=> $validate->errors()], 422);


        $payment_category = PaymentCategory::find($request->payment_category_id);
        if(!$payment_category)
            return response()->json(['success' => false, 'message' => 'Payment Category not found'], 404);

        if ($payment_category->delete()) {
            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Payment Category can not be deleted'
            ], 500);
        }

    }
}
