<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseItem extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'expense_items';
	
	/* public const BOX_OR_UNIT_RADIO = [
        '0' => 'Box',
        '1' => 'Unit',
    ]; */

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'product_id',
		'is_box',
		'stock',
		'purchase_price',
		'tax_id',
		'category_id',
		'sub_category_id',
		'expense_id',
        'created_at',
        'updated_at',
        'deleted_at',        
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }
	
	public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
	
	public function sub_category()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }
}
