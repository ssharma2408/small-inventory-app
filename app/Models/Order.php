<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'orders';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'sales_manager_id',
        'customer_id',
        'order_total',
        'comments',
        'delivery_note',
        'customer_sign',
        'status',
        'extra_discount',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const STATUS_SELECT = [
        '0' => 'Declined',
        '1' => 'Completed',
        '2' => 'Pending',
        '3' => 'Awaiting Payment',
        '4' => 'Awaiting Shipment',
        '5' => 'Hold',
        '6' => 'Disputed',
        '7' => 'Review',
        '8' => 'Confirm',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function sales_manager()
    {
        return $this->belongsTo(User::class, 'sales_manager_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
	
	public function orderItems()
    {
        return $this->belongsToMany(OrderItem::class);
    }
}
