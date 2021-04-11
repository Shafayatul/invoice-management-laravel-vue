<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\PaymentCategory;
use App\Models\Invoice;
class Income extends Model
{
    use HasFactory;
    
    protected $table = 'invoices';

    protected $fillable = ['created_by', 'client_id', 'category_id', 'invoice_id', 'name', 'type', 'details', 'receipt_file', 'deleted_at'];
    
    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function client(){
        return $this->belongsTo(User::class, 'client_id');
    }
    
    public function category(){
        return $this->belongsTo(User::class, 'category_id');
    }
    
    public function invoice(){
        return $this->belongsTo(User::class, 'invoice_id');
    }
}
