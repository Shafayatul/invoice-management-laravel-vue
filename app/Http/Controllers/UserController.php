<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use Carbon\Carbon;

class UserController extends Controller {

    protected $data_per_page = 10;

    public function __construct(Request $request) {
        if(isset($request->per_page))
            $this->data_per_page = $request->per_page;
    }

    public function index(Request $request) {
        $users = User::with(['expenses', 'company', 'createdInvoices', 'clientInvoices', 'createdIncomes', 'clientIncomes'])->paginate($this->data_per_page);
        return response()->json([
            'users' => $users,
            'status'    => 'success',
            'code'      => 200
        ], 200);
    }
     
    public function show(Request $request) {
        $user = User::with(['expenses', 'company', 'createdInvoices', 'clientInvoices', 'createdIncomes', 'clientIncomes'])->find($request->user_id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found '
            ], 404);
        }
 
        return response()->json([
            'success' => true,
            'data' => $user->toArray()
        ], 200);
    }

    public function store(Request $request) {
        $validation = Validator::make($request->all(), [
            'name'       => 'required|string',
            'phone'        => 'required|string',
            'email'       => 'required|unique:users,email',
            'password'     => 'required|confirmed',
            'role'     => 'required|string|max:10',
            'company_id'       => 'required|exists:companies,id',
        ]);
        if($validation->fails())
            return response()->json(['errors' => $validation->errors()], 422);

        $user             = new User();
        $user->name       = $request->name;
        $user->phone      = $request->phone;
        $user->email      = $request->email;
        $user->password   = Hash::make($request->password);
        $user->role       = $request->role;
        $user->company_id = $request->company_id;
        

        if($user->save())
            return response()->json([
                'success' => true,
                'data' => $user->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'User not added'
            ], 500);
    }

    public function update(Request $request){
        $validation = Validator::make($request->all(), [
            'name'       => 'required|string',
            'phone'        => 'required|string',
            'email'       => 'required|unique:users,email,'.$request->user_id,
            'password'     => 'required|confirmed',
            'role'     => 'required|string|max:10',
            'user_id'       => 'required|exists:users,id',
            'company_id'       => 'required|exists:companies,id',
        ]);
        if($validation->fails())
            return response()->json(['errors' => $validation->errors()], 422);

        $user = User::find($request->user_id);
 
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $updated = $user->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ], 200);
        else
            return response()->json([
                'success' => false,
                'message' => 'User can not be updated'
            ], 500);
    }
    
    public function destroy(Request $request){
        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        if ($user->delete()) {
            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User can not be deleted'
            ], 500);
        }
    }
}
