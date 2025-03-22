<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'ProductID';
    public $timestamps = true;

    protected $fillable = [
        'ProductImage', 'Gender', 'Name', 'Size', 'Price', 'Description', 'BrandID', 'StaffID'
    ];
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'BrandID', 'BrandID');
    }
}