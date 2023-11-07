<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditNoteLog extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'credit_note_log';

    protected $dates = [        
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'credit_order_id',
        'debit_order_id',
        'customer_id',
        'amount',
        'balance',        
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function credit_order_id()
    {
        return $this->belongsTo(Order::class, 'credit_order_id');
    }
	
	public function debit_order_id()
    {
        return $this->belongsTo(Order::class, 'debit_order_id');
    }
	
	public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
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
