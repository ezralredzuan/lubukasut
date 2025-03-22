<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands'; // Ensure it matches your database table name
    protected $primaryKey = 'BrandID'; // Adjust if needed
    public $timestamps = true; // Ensure timestamps are enabled if your table has created_at & updated_at
}
