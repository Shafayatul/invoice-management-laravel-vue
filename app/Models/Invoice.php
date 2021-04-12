<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;
use App\Models\Company;
use App\Models\Income;
class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'invoices';

    protected $fillable = ['created_by', 'client_id', 'company_id', 'sending_type', 'sending_date', 'recurring_period', 'deleted_at'];
    
    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function client(){
        return $this->belongsTo(User::class, 'client_id')->withTrashed();
    }

    public function companies(){
        return $this->belongsTo(Company::class, 'company_id')->withTrashed();
    }
    
    public function incomes(){
        return $this->hasMany(Income::class, 'invoice_id')->withTrashed();
    }
}
