<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'customers';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
	
	 public const PAYMENT_TERMS_SELECT = [
        '0' => '15 Days',
        '1' => '30 Days',
        '2' => '45 Days',
        '4' => '60 Days',
    ];

    protected $fillable = [
        'contact_name',
        'name',
        'address',
        'phone_number',
        'email',
        'pincode',
        'company_name',
        'payment_terms',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
