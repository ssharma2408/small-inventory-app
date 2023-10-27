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

    public const BOX_OR_UNIT_RADIO = [
        '0' => 'Box',
        '1' => 'Unit',
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
        'product_id',
        'box_or_unit',
        'stock',
        'purchase_price',
        'discount_type',
        'discount',
        'tax_id',
        'final_price',
		'category_id',
		'sub_category_id',
		'invoice_number',
		'days_payable_outstanding',
		'due_date',
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

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

    public function getPoFileAttribute()
    {
        return $this->getMedia('po_file')->last();
    }
	
	public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
	
	public function sub_category()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }
	
	public function payment()
    {
        return $this->belongsTo(ExpensePaymentMaster::class, 'id');
    }
}
