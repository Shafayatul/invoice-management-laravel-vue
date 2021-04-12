<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Expense;
class Company extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'companies';

    protected $fillable = ['name', 'address'];
    
    public function users(){
        return $this->hasMany(User::class, 'company_id')->withTrashed();
    }

    public function invoices(){
        return $this->hasMany(Invoice::class, 'company_id')->withTrashed();
    }

    public function expenses(){
        return $this->hasMany(Expense::class, 'company_id')->withTrashed();
    }
}
