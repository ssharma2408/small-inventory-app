<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public $table = 'products';

    protected $appends = [
        'product_image',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'maximum_selling_price',
        'selling_price',
        'stock',
		'box_size',
        'category_id',
        'sub_category_id',
        'image_url',
		'tax_id',
		'description_website',
		'description_invoice',
		'show_fe',
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

    public function getProductImageAttribute()
    {
        $file = $this->getMedia('product_image')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }
	
	public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
	
	public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }
	
	public function sub_category()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }
	
	public function tax_details(){
        return $this->tax();
    }
}
