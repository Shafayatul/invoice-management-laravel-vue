<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Invoice;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class InvoiceController extends Controller {
    
    protected $data_per_page = 10;

    public function __construct(Request $request){
        if(isset($request->per_page))
            $this->data_per_page = $request->per_page;
    }

    public function index(Request $request) {
        $invoices = Invoice::with(['createdBy', 'client', 'companies', 'incomes'])->paginate($this->data_per_page);
        return response()->json([
            'invoices' => $invoices,
            'status'    => 'success',
            'code'      => 200
        ], 200);
    }
     
    public function show(Request $request)
    {
        $invoice = Invoice::with(['createdBy', 'client', 'companies', 'incomes'])->find($request->invoice_id);
        
        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found '
            ], 404);
        }
 
        return response()->json([
            'success' => true,
            'data' => $invoice->toArray()
        ], 200);
    }

    public function store(Request $request) {
        $a = Validator::make($request->all(), [
            'created_by'       => 'required|exists:users,id',
            'client_id'        => 'required|exists:users,id',
            'company_id'       => 'required|exists:companies,id',
            'sending_type'     => 'required',
            'sending_date'     => 'required',
            'recurring_period' => 'required'
        ]);
        if($a->fails())
            return response()->json(['errors' => $a->errors()], 422);

        $invoice                   = new Invoice();
        $invoice->created_by       = $request->name;
        $invoice->client_id        = $request->client_id;
        $invoice->company_id       = $request->company_id;
        $invoice->sending_type     = $request->sending_type;
        $invoice->sending_date     = Carbon::parse($request->sending_date);//->format("Y-m-d H:i:s");
        $invoice->recurring_period = $request->recurring_period;
        $invoice->save();

        // if($invoice->id > 0)
        //     return response()->json([
        //         'success' => true,
        //         'data' => $invoice->toArray()
        //     ], 201);
        // else
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Invoice not added'
        //     ], 500);
    }

    public function update(Request $request){
        $this->validate($request, [
            'created_by'       => 'required|exists:users,id',
            'client_id'        => 'required|exists:users,id',
            'company_id'       => 'required|exists:companies,id',
            'sending_type'     => 'required',
            'sending_date'     => 'required',
            'recurring_period' => 'required'
        ]);
        $invoice = Invoice::find($request->invoice_id);
 
        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found'
            ], 404);
        }

        $updated = $invoice->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ], 200);
        else
            return response()->json([
                'success' => false,
                'message' => 'Invoice can not be updated'
            ], 500);
    }
    
    public function destroy(Request $request){
        $invoice = Invoice::find($request->invoice_id);

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found'
            ], 404);
        }
        if ($invoice->delete()) {
            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invoice can not be deleted'
            ], 500);
        }
    }
}
