<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Order extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public $table = 'orders';
	
	protected $casts = [
		'due_date' => 'datetime',
		'order_date' => 'datetime',
	];
	
	/* protected $appends = [
        'delivery_pic',
    ]; */

    protected $dates = [
        'order_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
	
	public const DISCOUNT_TYPE_RADIO = [
        '0' => 'Fixed',
        '1' => 'Percentage',
    ];

    protected $fillable = [
        'sales_manager_id',
        'customer_id',
        'order_total_without_tax',
        'order_tax',
        'order_total',
        'comments',
        'delivery_note',
        'customer_sign',
        'status',
		'discount_type',
		'extra_discount',
		'delivery_agent_id',
		'delivery_pic',
		'order_date',
		'due_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const STATUS_SELECT = [
        '0' => 'Declined',
        '1' => 'Completed',        
        '3' => 'Review',
        '4' => 'Accepted',
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
	
	public function payment()
    {
        return $this->belongsTo(OrderPaymentMaster::class, 'id', 'order_number');
    }
	
	public function delivery_agent()
    {
        return $this->belongsTo(User::class, 'delivery_agent_id');
    }
	
	/*  public function getDeliveryPicAttribute()
    {
        return $this->getMedia('delivery_pic')->last();
    } */
}
