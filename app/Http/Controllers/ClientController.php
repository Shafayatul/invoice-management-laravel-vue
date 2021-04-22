<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    protected $data_per_page = 10;

    public function __construct(Request $request){
        if(isset($request->per_page))
            $this->data_per_page = $request->per_page;
    }

    public function index(Request $request) {
        if(Auth::user()->role == 'admin'){
            $clients = User::with(['expenses', 'company', 'createdInvoices', 'clientInvoices', 'createdIncomes', 'clientIncomes'])->where('role', 'client')->where('company_id', Auth::user()->company_id)->simplePaginate($this->data_per_page);
        }else{
            $clients = User::with(['expenses', 'company', 'createdInvoices', 'clientInvoices', 'createdIncomes', 'clientIncomes'])->where('role', 'client')->simplePaginate($this->data_per_page);
        }
        return response()->json([
            'clients' => $clients,
            'status'    => 'success',
            'code'      => 200
        ], 200);
    }

    public function show(Request $request)
    {
        $client = User::with(['expenses', 'company', 'createdInvoices', 'clientInvoices', 'createdIncomes', 'clientIncomes'])->find($request->client_id);
        
        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found '
            ], 404);
        }
 
        return response()->json([
            'success' => true,
            'data' => $client->toArray()
        ], 200);
    }

    public function store(Request $request) {

        $validation = Validator::make($request->all(), [
            'name'       => 'required|string',
            'phone'      => 'required|string',
            'email'      => 'required|unique:users,email',
        ]);
        if($validation->fails())
            return response()->json(['errors' => $validation->errors()], 422);

        $client             = new User();
        $client->name       = $request->name;
        $client->phone      = $request->phone;
        $client->email      = $request->email;
        $client->password   = Hash::make(Str::random(8));
        $client->role       = 'client';
        $client->company_id = Auth::user()->company_id;
        

        if($client->save())
            return response()->json([
                'success' => true,
                'data' => $client->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'User not added'
            ], 500);
    }

    public function update(Request $request) {
        $validation = Validator::make($request->all(), [
            'name'       => 'required|string',
            'phone'      => 'required|string',
            'email'      => 'required|unique:users,email,'.$request->client_id,
            'client_id'  => 'required|exists:users,id',
        ]);

        if($validation->fails())
            return response()->json(['errors' => $validation->errors()], 422);

        $client = User::find($request->client_id);

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found'
            ], 404);
        }

        $client->name       = $request->name;
        $client->phone      = $request->phone;
        $client->email      = $request->email;
        $client->company_id = Auth::user()->company_id;
        $client->save();
    
        if ($client->id > 0)
            return response()->json([
                'success' => true,
                'data' => $client->toArray()
            ], 200);
        else
            return response()->json([
                'success' => false,
                'message' => 'Client can not be updated'
            ], 500);
    }

    public function destroy(Request $request){
        $client = User::find($request->client_id);

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found'
            ], 404);
        }

        if ($client->delete()) {
            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Client can not be deleted'
            ], 500);
        }
    }

    public function ClientData()
    {
        $clients = User::where('role', 'client')->select('name', 'id')->get()->toArray();
        return response()->json([
            'clients' => $clients,
            'status'    => 'success',
            'code'      => 200
        ], 200);
    }
}
