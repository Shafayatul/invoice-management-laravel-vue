<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;
use App\Models\PaymentCategory;
use App\Models\Invoice;
class Income extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'incomes';

    protected $fillable = ['created_by', 'client_id', 'category_id', 'invoice_id', 'income_amount', 'income_type', 'income_details', 'receipt_file', 'deleted_at'];
    
    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }
    
    public function client(){
        return $this->belongsTo(User::class, 'client_id')->withTrashed();
    }
    
    public function category(){
        return $this->belongsTo(PaymentCategory::class, 'category_id')->withTrashed();
    }
    
    public function invoice(){
        return $this->belongsTo(User::class, 'invoice_id')->withTrashed();
    }
}
