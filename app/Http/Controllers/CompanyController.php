<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CompanyController extends Controller
{
    protected $data_per_page = 10;

    public function __construct(Request $request){
        if(isset($request->data_per_page))
            $this->data_per_page = $request->data_per_page;
    }

    public function index(Request $request) {
        $companies = Company::paginate($this->data_per_page);
        return response()->json([
            'companies' => $companies,
            'status'    => 'success',
            'code'      => 200
        ], 200);
    }
     
    public function show(Request $request)
    {
        $company = Company::find($request->company_id);
 
        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found '
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $company->toArray()
        ], 400);
    }
}
