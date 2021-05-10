<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Income;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function DashboardData()
    {
        $to = Carbon::now();
        $from = Carbon::now()->subDays(30);

        for ($i = 0; $i < 30; $i++) {
            $dates[] = date("Y-m-d", strtotime(date('Y-m-d')." -$i days"));
        }


        if(Auth::user()->role == 'admin'){
            $total_clients = User::where('role', 'client')->where('company_id', Auth::user()->company_id)->count();

            $unpaid_invoices = Invoice::where('company_id', Auth::user()->company_id)->whereHas('invoiceHistory', function($invoice){
                $invoice->where('is_paid', 0);
            })->count();
            
            $paid_invoices = Invoice::where('company_id', Auth::user()->company_id)->whereHas('invoiceHistory', function($invoice){
                $invoice->where('is_paid', 1);
            })->count();

            $total_incomes = Income::whereHas('createdBy', function($user){
                $user->where('company_id', Auth::user()->company_id);
            })->sum('income_amount');

            $total_expenses = Expense::where('company_id', Auth::user()->company_id)->sum('expense_amount');

            $net_gross = ((float)$total_incomes-(float)$total_expenses);
            
            if($net_gross == 0.0 || $total_incomes == 0){
                $profit_ratio = 0;
            }else{
                $profit_ratio = (((float)$total_incomes-(float)$total_expenses)/(float)$total_incomes)*100;
            }

            
           

            $last_month_unpaid_invoice = Invoice::where('company_id', Auth::user()->company_id)
                            ->whereHas('invoiceHistory', function($invoice){
                                $invoice->where('is_paid', 0);
                            })
                            ->whereBetween('created_at', [$from, $to])
                            ->select('id', 'created_at')
                            ->get()
                            ->groupBy(function($date) {
                                return Carbon::parse($date->created_at)->format('Y-m-d');
                            });

            $last_month_unpaid_invoice_count = [];
            $last_month_unpaid_invoice_arr = [];
            foreach ($last_month_unpaid_invoice as $key => $value) {
                $last_month_unpaid_invoice_count[$key] = count($value);
            }
    
            foreach($dates as $key => $month){
                if(!empty($last_month_unpaid_invoice_count[$month])){
                    $last_month_unpaid_invoice_arr[$month] = $last_month_unpaid_invoice_count[$month];    
                }else{
                    $last_month_unpaid_invoice_arr[$month] = 0;    
                }
            }

            $last_month_paid_invoice = Invoice::where('company_id', Auth::user()->company_id)
                            ->whereHas('invoiceHistory', function($invoice){
                                $invoice->where('is_paid', 1);
                            })
                            ->whereBetween('created_at', [$from, $to])
                            ->select('id', 'created_at')
                            ->get()
                            ->groupBy(function($date) {
                                return Carbon::parse($date->created_at)->format('Y-m-d');
                            });

            $last_month_paid_invoice_count = [];
            $last_month_paid_invoice_arr = [];
            foreach ($last_month_paid_invoice as $key => $value) {
                $last_month_paid_invoice_count[$key] = count($value);
            }
    
            foreach($dates as $key => $month){
                if(!empty($last_month_paid_invoice_count[$month])){
                    $last_month_paid_invoice_arr[$month] = $last_month_paid_invoice_count[$month];    
                }else{
                    $last_month_paid_invoice_arr[$month] = 0;    
                }
            }


            $last_month_incomes = Income::whereHas('createdBy', function($user){
                                $user->where('company_id', Auth::user()->company_id);
                            })
                            ->whereBetween('created_at', [$from, $to])
                            ->select('id', 'created_at', 'income_amount')
                            ->get()
                            ->groupBy(function($date) {
                                return Carbon::parse($date->created_at)->format('Y-m-d');
                            });

            $last_month_incomes_count = [];
            $last_month_incomes_arr = [];
            foreach ($last_month_incomes as $key => $value) {
                $last_month_income_total = 0;
                foreach($value as $income){
                    $last_month_income_total+=(float)$income->income_amount;
                }
                $last_month_incomes_count[$key] = $last_month_income_total;
            }
    
            foreach($dates as $key => $month){
                if(!empty($last_month_incomes_count[$month])){
                    $last_month_incomes_arr[$month] = $last_month_incomes_count[$month];    
                }else{
                    $last_month_incomes_arr[$month] = 0;    
                }
            }

            $last_month_expenses = Expense::where('company_id', Auth::user()->company_id)
                            ->whereBetween('created_at', [$from, $to])
                            ->select('id', 'created_at', 'expense_amount')
                            ->get()
                            ->groupBy(function($date) {
                                return Carbon::parse($date->created_at)->format('Y-m-d');
                            });

            $last_month_expenses_count = [];
            $last_month_expenses_arr = [];
            foreach ($last_month_expenses as $key => $value) {
                $last_month_expense_total = 0;
                foreach($value as $income){
                    $last_month_expense_total+=(float)$income->expense_amount;
                }
                $last_month_expenses_count[$key] = $last_month_expense_total;
            }
    
            foreach($dates as $key => $month){
                if(!empty($last_month_expenses_count[$month])){
                    $last_month_expenses_arr[$month] = $last_month_expenses_count[$month];    
                }else{
                    $last_month_expenses_arr[$month] = 0;    
                }
            }

            $unpaid_invoices_all = Invoice::with(['createdBy', 'client', 'companies', 'incomes', 'invoiceHistory'])->whereHas('invoiceHistory', function($invoice){
                $invoice->where('is_paid', 0);
            })->where('company_id', Auth::user()->company_id)->latest()->get();

        }else{
            $total_clients = User::where('role', 'client')->count();

            $unpaid_invoices = Invoice::whereHas('invoiceHistory', function($invoice){
                $invoice->where('is_paid', 0);
            })->count();

            $paid_invoices = Invoice::whereHas('invoiceHistory', function($invoice){
                $invoice->where('is_paid', 1);
            })->count();

            $total_incomes = Income::sum('income_amount');

            $total_expenses = Expense::sum('expense_amount');

            $profit_ratio = (((float)$total_incomes-(float)$total_expenses)/(float)$total_incomes)*100;

            $last_month_unpaid_invoice = Invoice::whereHas('invoiceHistory', function($invoice){
                                $invoice->where('is_paid', 0);
                            })
                            ->whereBetween('created_at', [$from, $to])
                            ->select('id', 'created_at')
                            ->get()
                            ->groupBy(function($date) {
                                return Carbon::parse($date->created_at)->format('Y-m-d');
                            });

            $last_month_unpaid_invoice_count = [];
            $last_month_unpaid_invoice_arr = [];
            foreach ($last_month_unpaid_invoice as $key => $value) {
                $last_month_unpaid_invoice_count[$key] = count($value);
            }
    
            foreach($dates as $key => $month){
                if(!empty($last_month_unpaid_invoice_count[$month])){
                    $last_month_unpaid_invoice_arr[$month] = $last_month_unpaid_invoice_count[$month];    
                }else{
                    $last_month_unpaid_invoice_arr[$month] = 0;    
                }
            }

            $last_month_paid_invoice = Invoice::whereHas('invoiceHistory', function($invoice){
                                $invoice->where('is_paid', 1);
                            })
                            ->whereBetween('created_at', [$from, $to])
                            ->select('id', 'created_at')
                            ->get()
                            ->groupBy(function($date) {
                                return Carbon::parse($date->created_at)->format('Y-m-d');
                            });

            $last_month_paid_invoice_count = [];
            $last_month_paid_invoice_arr = [];
            foreach ($last_month_paid_invoice as $key => $value) {
                $last_month_paid_invoice_count[$key] = count($value);
            }
    
            foreach($dates as $key => $month){
                if(!empty($last_month_paid_invoice_count[$month])){
                    $last_month_paid_invoice_arr[$month] = $last_month_paid_invoice_count[$month];    
                }else{
                    $last_month_paid_invoice_arr[$month] = 0;    
                }
            }


            $last_month_incomes = Income::whereBetween('created_at', [$from, $to])
                            ->select('id', 'created_at', 'income_amount')
                            ->get()
                            ->groupBy(function($date) {
                                return Carbon::parse($date->created_at)->format('Y-m-d');
                            });


            $last_month_incomes_count = [];
            $last_month_incomes_arr = [];
            foreach ($last_month_incomes as $key => $value) {
                $last_month_income_total = 0;
                foreach($value as $income){
                    $last_month_income_total+=(float)$income->income_amount;
                }
                $last_month_incomes_count[$key] = $last_month_income_total;
            }
    
            foreach($dates as $key => $month){
                if(!empty($last_month_incomes_count[$month])){
                    $last_month_incomes_arr[$month] = $last_month_incomes_count[$month];    
                }else{
                    $last_month_incomes_arr[$month] = 0;    
                }
            }

            $last_month_expenses = Expense::whereBetween('created_at', [$from, $to])
                            ->select('id', 'created_at', 'expense_amount')
                            ->get()
                            ->groupBy(function($date) {
                                return Carbon::parse($date->created_at)->format('Y-m-d');
                            });

            $last_month_expenses_count = [];
            $last_month_expenses_arr = [];
            foreach ($last_month_expenses as $key => $value) {
                $last_month_expense_total = 0;
                foreach($value as $income){
                    $last_month_expense_total+=(float)$income->expense_amount;
                }
                $last_month_expenses_count[$key] = $last_month_expense_total;
            }
    
            foreach($dates as $key => $month){
                if(!empty($last_month_expenses_count[$month])){
                    $last_month_expenses_arr[$month] = $last_month_expenses_count[$month];    
                }else{
                    $last_month_expenses_arr[$month] = 0;    
                }
            }

            $unpaid_invoices_all = Invoice::with(['createdBy', 'client', 'companies', 'incomes', 'invoiceHistory'])->whereHas('invoiceHistory', function($invoice){
                $invoice->where('is_paid', 0);
            })->latest()->get();
        }

        return response()->json([
            'total_clients' => $total_clients,
            'unpaid_invoices' => $unpaid_invoices,
            'paid_invoices' => $paid_invoices,
            'profit_ratio' => (float) number_format($profit_ratio, 2),
            'last_month_unpaid_invoice_arr' => $last_month_unpaid_invoice_arr,
            'last_month_paid_invoice_arr' => $last_month_paid_invoice_arr,
            'last_month_incomes_arr' => $last_month_incomes_arr,
            'last_month_expenses_arr' => $last_month_expenses_arr,
            'unpaid_invoices_all' => $unpaid_invoices_all,
        ]);
    }
}
