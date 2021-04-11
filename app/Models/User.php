<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

use App\Models\Company;
use App\Models\Expense;
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function expenses(){
        return $this->hasMany(Expense::class, 'user_id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function createdInvoices(){
        return $this->hasMany(Invoice::class, 'created_by');
    }

    public function clientInvoices(){
        return $this->hasMany(Invoice::class, 'client_id');
    }

    public function createdIncomes(){
        return $this->hasMany(Income::class, 'created_by');
    }

    public function clientIncomes(){
        return $this->hasMany(Income::class, 'client_id');
    }
}
