<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Inventory extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public $table = 'inventories';

    protected $appends = [
        'po_file',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const DISCOUNT_TYPE_RADIO = [
        '0' => 'Fixed',
        '1' => 'Percentage',
    ];

    public const DAYS_PAYABLE_OUTSTANDING_SELECT = [
        '0' => '15 Days',
        '1' => '30 Days',
        '2' => '45 Days',
        '4' => '60 Days',
    ];

    protected $fillable = [
        'supplier_id',        
        'discount_type',
        'discount',        
        'final_price',		
		'invoice_number',
		'days_payable_outstanding',
		'due_date',
		'expense_total',
		'expense_tax',
		'image_url',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function getPoFileAttribute()
    {
        return $this->getMedia('po_file')->last();
    }

	public function payment()
    {
        return $this->belongsTo(ExpensePaymentMaster::class, 'id');
    }
}
