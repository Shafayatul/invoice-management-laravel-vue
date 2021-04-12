<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Expense;
use App\Models\Income;
class PaymentCategory extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = 'expenses';

    protected $fillable = ['name', 'type', 'details', 'deleted_at'];
    
    public function incomes(){
        return $this->hasMany(Income::class, 'category_id')->withTrashed();
    }
    
    public function expenses(){
        return $this->hasMany(Expense::class, 'category_id')->withTrashed();
    }
}
