<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

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

        $user = $this->getUser($request->user_id);

        return response()->json([
            'success' => true,
            'data' => $user->toArray()
        ], 200);
    }

    protected function getUser($user_id){
        $user = User::with(['expenses', 'company', 'createdInvoices', 'clientInvoices', 'createdIncomes', 'clientIncomes'])->find($user_id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found '
            ], 404);
        }

        return $user;
    }

    public function store(Request $request) {

        $validation = Validator::make($request->all(), [
            'name'       => 'required|string',
            'phone'      => 'required|string',
            'email'      => 'required|unique:users,email',
            'role'       => 'required|string|max:10',
            'password'   => 'required_unless:role,client|confirmed',
            'company_id' => 'required|exists:companies,id',
        ]);
        if($validation->fails())
            return response()->json(['errors' => $validation->errors()], 422);

        $user             = new User();
        $user->name       = $request->name;
        $user->phone      = $request->phone;
        $user->email      = $request->email;
        if($request->role == 'client'){
            $user->password   = Hash::make(Str::random(8));
        }else{
            $user->password   = Hash::make($request->password);
        }
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
            'phone'      => 'required|string',
            'email'      => 'required|unique:users,email,'.$request->user_id,
            'password'   => 'bail|confirmed|min:8',
            'role'       => 'required|string',
            'user_id'    => 'required|exists:users,id',
            'company_id' => 'required|exists:companies,id',
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

        $user->name       = $request->name;
        $user->phone      = $request->phone;
        $user->email      = $request->email;
        if($user->role != 'client'){
            $user->password   = Hash::make($request->password);
        }
        $user->role       = $request->role;
        $user->company_id = $request->company_id;
        $user->save();
 
        if ($user->id > 0)
            return response()->json([
                'success' => true,
                'data' => $user->toArray()
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

    public function loginUserInfo(Request $request){
        $user = $this->getUser(Auth::id());
        
        return response()->json([
            'success' => true,
            'data' => $user->toArray()
        ], 200);
    }

    public function BlockOrUnblockUser($id)
    {
        $user = User::find($id);
        if(!$user)
            return response()->json(['success' => false,'message' => 'User not found'], 404);
        
        if($user->is_active == 1){
            $user->is_active = false;
        }else{
            $user->is_active = true;
        }

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

    public function AssignCompany(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'user_id'    => 'required|exists:users,id',
            'company_id' => 'required|exists:companies,id',
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

    public function UpdatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'old_password'      => ['required', 'string', 'min:8'],
            'new_password'      => ['required', 'string', 'min:8'],
            'confirm_password'  => ['required', 'string', 'min:8'],
        ]);
        if ($validator->fails()) {
            return response()->json([ 'error'=> $validator->errors() ]);
        }

        $old_password       = $request->old_password;
        $new_password       = $request->new_password;
        $confirm_password   = $request->confirm_password;
        
        
        if($new_password == $confirm_password){
            $current_password = Auth::user()->password;
            if(Hash::check($old_password, $current_password))
            {
                $id             = Auth::user()->id;
                $user           = User::findOrFail($id);
                $user->password = Hash::make($new_password);
                $user->save(); 
                return response()->json(['status'=>'Passowrd Updated!', 'code' => 201], 201);
            }else{
                return response()->json([
                    'status' => 'Password Not Correct!',
                ], 404);
            }
        }else{
            return response()->json([
                'status' => 'New Password and Confirm password not matching!',
            ], 404);
        }
    }
}
