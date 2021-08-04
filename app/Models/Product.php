<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    public $fillable = [
        'name', 'cate_id', 'code_sale', 'status', 'price', 'amount', 'detail'
    ];

    function categories()
    {
        return $this->belongsTo('App\Models\category', 'cate_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag', 'product_id', 'tag_id');
    }
}