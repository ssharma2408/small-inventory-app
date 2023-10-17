<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpensePaymentMaster extends Model
{
    use SoftDeletes, HasFactory;
	
	public $table = 'expense_payment_master';

    protected $dates = [        
        'created_at',
        'updated_at',
		'deleted_at',
    ];
	
	protected $fillable = [
        'supplier_id',
        'invoice_number',
        'expense_total',
        'expense_paid',
        'expense_pending',
        'payment_status',
        'created_at',
        'updated_at',
		'deleted_at',
    ];
	
	protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
	
	 public function getDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

}
