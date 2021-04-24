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

            $profit_ratio = (((float)$total_incomes-(float)$total_expenses)/(float)$total_incomes)*100;

            $last_month_unpaid_invoice = Invoice::where('company_id', Auth::user()->company_id)
                            ->whereHas('invoiceHistory', function($invoice){
                                $invoice->where('is_paid', 0);
                            })
                            ->whereBetween('created_at', [$from, $to])
                            ->groupBy('date')
                            ->get([DB::raw('Date(created_at) as date'),
                                DB::raw('COUNT(*) as "unpaid_invoices"')
                            ]);

            $last_month_paid_invoice = Invoice::where('company_id', Auth::user()->company_id)
                            ->whereHas('invoiceHistory', function($invoice){
                                $invoice->where('is_paid', 1);
                            })
                            ->whereBetween('created_at', [$from, $to])
                            ->groupBy('date')
                            ->get([DB::raw('Date(created_at) as date'),
                                DB::raw('COUNT(*) as "paid_invoices"')
                            ]);


            $last_month_incomes = Income::whereHas('createdBy', function($user){
                                $user->where('company_id', Auth::user()->company_id);
                            })
                            ->whereBetween('created_at', [$from, $to])
                            ->groupBy('date')
                            ->get([DB::raw('Date(created_at) as date'),
                                DB::raw('SUM(income_amount) as "incomes"')
                            ]);

            $last_month_expenses = Expense::where('company_id', Auth::user()->company_id)
                            ->whereBetween('created_at', [$from, $to])
                            ->groupBy('date')
                            ->get([DB::raw('Date(created_at) as date'),
                                DB::raw('SUM(expense_amount) as "expenses"')
                            ]);

            $unpaid_invoices_all = Invoice::with(['createdBy', 'client', 'companies', 'incomes', 'invoiceHistory'])->where('company_id', Auth::user()->company_id)->whereHas('invoiceHistory', function($invoice){
                $invoice->where('is_paid', 0);
            })->get();

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
                            ->groupBy('date')
                            ->get([DB::raw('Date(created_at) as date'),
                                DB::raw('COUNT(*) as "unpaid_invoices"')
                            ]);

            $last_month_paid_invoice = Invoice::whereHas('invoiceHistory', function($invoice){
                                $invoice->where('is_paid', 1);
                            })
                            ->whereBetween('created_at', [$from, $to])
                            ->groupBy('date')
                            ->get([DB::raw('Date(created_at) as date'),
                                DB::raw('COUNT(*) as "paid_invoices"')
                            ]);

            $last_month_incomes = Income::whereBetween('created_at', [$from, $to])
                            ->groupBy('date')
                            ->get([DB::raw('Date(created_at) as date'),
                                DB::raw('SUM(income_amount) as "incomes"')
                            ]);

            $last_month_expenses = Expense::whereBetween('created_at', [$from, $to])
                            ->groupBy('date')
                            ->get([DB::raw('Date(created_at) as date'),
                                DB::raw('SUM(expense_amount) as "expenses"')
                            ]);

            $unpaid_invoices_all = Invoice::with(['createdBy', 'client', 'companies', 'incomes', 'invoiceHistory'])->whereHas('invoiceHistory', function($invoice){
                $invoice->where('is_paid', 0);
            })->get();
        }

        return response()->json([
            'total_clients' => $total_clients,
            'unpaid_invoices' => $unpaid_invoices,
            'paid_invoices' => $paid_invoices,
            'profit_ratio' => (float) number_format($profit_ratio, 2),
            'last_month_unpaid_invoice' => $last_month_unpaid_invoice,
            'last_month_paid_invoice' => $last_month_paid_invoice,
            'last_month_incomes' => $last_month_incomes,
            'last_month_expenses' => $last_month_expenses,
            'unpaid_invoices_all' => $unpaid_invoices_all,
        ]);
    }
}
