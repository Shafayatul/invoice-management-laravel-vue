<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
class CompanyController extends Controller {
    
    protected $data_per_page = 10;

    public function __construct(Request $request){
        if(isset($request->per_page))
            $this->data_per_page = $request->per_page;
    }

    public function index(Request $request) {
        $companies = Company::with(['users', 'invoices', 'expenses'])->paginate($this->data_per_page);
        return response()->json([
            'companies' => $companies,
            'status'    => 'success',
            'code'      => 200
        ], 200);
    }
     
    public function show(Request $request)
    {
        $company = Company::with(['users', 'invoices', 'expenses'])->find($request->company_id);
        
        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found '
            ], 404);
        }
 
        return response()->json([
            'success' => true,
            'data' => $company->toArray()
        ], 200);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required'
        ]);
 
        $company = new Company();
        $company->name = $request->name;
        $company->address = $request->address;
        $company->save();

        if($company->id > 0)
            return response()->json([
                'success' => true,
                'data' => $company->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Company not added'
            ], 500);
    }

    public function update(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required'
        ]);
        $company = Company::find($request->company_id);
 
        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found'
            ], 404);
        }

        $updated = $company->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ], 200);
        else
            return response()->json([
                'success' => false,
                'message' => 'Company can not be updated'
            ], 500);
    }
    
    public function destroy(Request $request){
        $company = Company::find($request->company_id);

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found'
            ], 404);
        }

        $company->users()->delete();
        $company->invoices()->delete();
        $company->expenses()->delete();

        if ($company->delete()) {
            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Company can not be deleted'
            ], 500);
        }
    }
}
