<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Invoice;
use App\Models\InvoiceHistory;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceSent;

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
        $validate = Validator::make($request->all(), [
            'client_id'        => 'required|exists:users,id',
            'sending_type'     => 'required',
            'sending_date'     => 'required|date',
            'recurring_period' => 'required_unless:sending_type,one_time|numeric',
            'amount'           => 'required|numeric',
        ]);
        if($validate->fails())
            return response()->json(['error' => $validate->errors()], 422);

        $invoice                   = new Invoice();
        $invoice->created_by       = Auth::id();
        $invoice->client_id        = $request->client_id;
        $invoice->company_id       = Auth::user()->company_id;
        $invoice->sending_type     = $request->sending_type;
        $invoice->sending_date     = Carbon::parse($request->sending_date);//->format("Y-m-d H:i:s");
        if($request->sending_type == 'one_time'){
            $invoice->recurring_period = 0;
        }else{
            $invoice->recurring_period = $request->recurring_period;
        }
        $invoice->save();

        if($invoice){
            $invoice_hostory                    = new InvoiceHistory();
            $invoice_hostory->client_id         = $request->client_id;
            $invoice_hostory->invoice_id        = $invoice->id;
            $invoice_hostory->is_paid           = false;
            $invoice_hostory->amount            = $request->amount;
            $invoice_hostory->last_mailing_time = Carbon::now();
            $invoice_hostory->mailing_count     = 1;
            $invoice_hostory->save();

            $client = User::find($request->client_id);

            Mail::to($client->email)->send(new InvoiceSent($invoice));
        }else{
            $invoice->delete();
        }

        if($invoice->id > 0)
            return response()->json([
                'success' => true,
                'data' => $invoice->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Invoice not added'
            ], 500);
    }

    public function update(Request $request){
        $validate = Validator::make($request->all(), [
            'invoice_id'        => 'required|exists:invoices,id',
            'client_id'        => 'required|exists:users,id',
            'sending_type'     => 'required',
            'sending_date'     => 'required|date',
            'recurring_period' => 'required|numeric',
            'amount'           => 'required|numeric',
        ]);
        if($validate->fails())
            return response()->json(['error' => $validate->errors()], 422);

        $invoice                   = Invoice::find($request->invoice_id);
        if(!$invoice){
            return response()->json(['error' => 'Invoice Not Found'], 404);
        }

        $invoice->created_by       = Auth::id();
        $invoice->client_id        = $request->client_id;
        $invoice->company_id       = Auth::user()->company_id;
        $invoice->sending_type     = $request->sending_type;
        $invoice->sending_date     = Carbon::parse($request->sending_date);//->format("Y-m-d H:i:s");
        $invoice->recurring_period = $request->recurring_period;
        $invoice->save();

        if($invoice){
            $invoice_hostory             = InvoiceHistory::where('invoice_id', $invoice->id)->first();
            $invoice_hostory->client_id  = $request->client_id;
            $invoice_hostory->invoice_id = $invoice->id;
            $invoice_hostory->amount     = $request->amount;
            $invoice_hostory->save();
        }else{
            $invoice->delete();
        }

        if($invoice->id > 0)
            return response()->json([
                'success' => true,
                'data' => $invoice->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Invoice not added'
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

    public function PaidInvoice(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'invoice_id' => 'required|exists:invoices,id'
        ]);

        if($validate->fails())
            return response()->json(['error' => $validate->errors()], 422);

        $invoice_hostory = InvoiceHistory::where('invoice_id', $request->invoice_id)->first();
        if (!$invoice_hostory) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found'
            ], 404);
        }
    }
}
