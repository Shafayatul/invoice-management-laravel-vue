<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;
use App\Models\PaymentCategory;
use App\Models\Company;

class Expense extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = 'expenses';

    protected $fillable = ['user_id', 'category_id', 'company_id', 'expense_date', 'expense_type', 'expense_amount', 'bills_file', 'deleted_at'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function category(){
        return $this->belongsTo(PaymentCategory::class, 'category_id')->withTrashed();
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id')->withTrashed();
    }

    // public function expenseDetails(){
    //     return $this->belongsTo(Company::class, 'company_id')->withTrashed();
    // }
}
