<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Company;
use App\Models\Income;
class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = ['created_by', 'client_id', 'company_id', 'sending_type', 'sending_date', 'recurring_period', 'deleted_at'];
    
    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function client(){
        return $this->belongsTo(User::class, 'client_id');
    }

    public function companies(){
        return $this->belongsTo(Company::class, 'company_id');
    }
    
    public function incomes(){
        return $this->hasMany(Income::class, 'invoice_id');
    }
}
