<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    /**
     * The validation rules
     *
     * @var array $rules
     */
    protected $rules = [
        
        'email'      => ['required', 'email'],
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array $dates
     */
    public $dates = ['deleted_at'];

    

    /**
     * The attributes that are mass assignable.
     *
     * @var array $fillable
     */
    protected $fillable = [
        
        'email',
        'merchant',
        'amount'
        
    ];

    /**
     * The users associated with the account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }

    
    

    /**
     * Payment gateways associated with an account
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function account_payment_gateways()
    {
        return $this->hasMany(\App\Models\AccountPaymentGateway::class);
    }

   
    
    public function getGatewayConfigVal($gateway_id, $key)
    {
        $gateway = $this->getGateway($gateway_id);

        if($gateway && is_array($gateway->config)) {
            return isset($gateway->config[$key]) ? $gateway->config[$key] : false;
        }

        return false;
    }



    

