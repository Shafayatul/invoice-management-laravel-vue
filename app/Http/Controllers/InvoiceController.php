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
use App\Models\Income;

class InvoiceController extends Controller {
    
    protected $data_per_page = 10;

    public function __construct(Request $request){
        if(isset($request->per_page))
            $this->data_per_page = $request->per_page;
    }

    public function index(Request $request) {
        if(Auth::user()->role == 'admin'){
            $invoices = Invoice::with(['createdBy', 'client', 'companies', 'incomes', 'invoiceHistory'])->where('company_id', Auth::user()->company_id)->paginate($this->data_per_page);
        }else{
            $invoices = Invoice::with(['createdBy', 'client', 'companies', 'incomes', 'invoiceHistory'])->paginate($this->data_per_page);
        }
        return response()->json([
            'invoices' => $invoices,
            'status'    => 'success',
            'code'      => 200
        ], 200);
    }
     
    public function show(Request $request)
    {
        $invoice = Invoice::with(['createdBy', 'client', 'companies', 'incomes', 'invoiceHistory'])->find($request->invoice_id);
        
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
        $invoice->sending_date     = Carbon::parse($request->sending_date);
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
            $invoice_hostory->last_mailing_time = $request->sending_date.' '.Carbon::now()->format("H:i:s");
            $invoice_hostory->mailing_count     = 0;
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

    public function update(Request $request){
        $validate = Validator::make($request->all(), [
            'invoice_id'        => 'required|exists:invoices,id',
            // 'client_id'        => 'required|exists:users,id',
            // 'sending_type'     => 'required',
            // 'sending_date'     => 'required|date',
            'recurring_period' => 'required|numeric',
            'amount'           => 'required|numeric',
        ]);
        if($validate->fails())
            return response()->json(['error' => $validate->errors()], 422);

        $invoice                   = Invoice::find($request->invoice_id);
        if(!$invoice){
            return response()->json(['error' => 'Invoice Not Found'], 404);
        }

        // $invoice->created_by       = Auth::id();
        // $invoice->client_id        = $request->client_id;
        // $invoice->company_id       = Auth::user()->company_id;
        // $invoice->sending_type     = $request->sending_type;
        // $invoice->sending_date     = Carbon::parse($request->sending_date);//->format("Y-m-d H:i:s");
        $invoice->recurring_period = $request->recurring_period;
        $invoice->save();

        if($invoice){
            $invoice_hostory             = InvoiceHistory::where('invoice_id', $invoice->id)->first();
            // $invoice_hostory->client_id  = $request->client_id;
            // $invoice_hostory->invoice_id = $invoice->id;
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
            'invoice_id' => 'required|exists:invoices,id',
            'category_id' => 'required|exists:payment_categories,id',
            'receipt_file' => 'mimes:png,jpg,jpeg,pdf'
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

        

        if($invoice_hostory->is_paid == false){
            if($request->hasFile('receipt_file')){
                $receipt_file = $request->file('receipt_file');
                $receipt_file_name = uniqid().strtolower($receipt_file->getClientOriginalExtension());
                $path = $request->file('receipt_file')->storeAs(
                    'expense-file',
                    $receipt_file_name,
                    'public'
                );
            }else{
                $path = null;
            }

            $income = new Income();
            $income->created_by = Auth::id();
            $income->client_id = $invoice_hostory->client_id;
            $income->category_id = $request->category_id;
            $income->invoice_id = $invoice_hostory->invoice_id;
            $income->income_amount = $invoice_hostory->amount;;
            $income->receipt_file = $path;
            $income->save();

            $invoice_hostory->is_paid = true;
            $invoice_hostory->save();

            if ($invoice_hostory->id > 0)
                return response()->json([
                    'success' => true
                ], 200);
            else
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice Not Paid'
                ], 500);
        }

    }

    public function InvoiceHistory(Request $request)
    {
        if(Auth::user()->role == 'admin'){
            $invoice_hostories = InvoiceHistory::with(['client', 'invoice' => function($invoice){
                $invoice->with(['createdBy', 'companies', 'incomes']);
            }])
            ->whereHas('invoice', function($invoice){
                $invoice->where('company_id', Auth::user()->company_id);
            })
            ->paginate($this->data_per_page);
        }else{
            $invoice_hostories = InvoiceHistory::with(['client', 'invoice' => function($invoice){
                $invoice->with(['createdBy', 'companies', 'incomes']);
            }])->paginate($this->data_per_page);
        }
        return response()->json([
            'invoice_hostories' => $invoice_hostories,
            'status'    => 'success',
            'code'      => 200
        ], 200);
    }
}
