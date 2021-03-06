<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Invoice;
use App\Models\User;

class InvoiceHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'invoice_histories';

    protected $fillable = ['client_id', 'invoice_id', 'is_paid', 'item_name', 'quantity', 'amount', 'last_mailing_time', 'mailing_count', 'deleted_at'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id')->withTrashed();
    }

    public function client(){
        return $this->belongsTo(User::class, 'client_id')->withTrashed();
    }
}
