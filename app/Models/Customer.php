<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';
    protected $primaryKey = 'CustomerID'; // Ensure this matches your database
    public $timestamps = true; // Enables created_at and updated_at

    protected $fillable = [
        'Username', 'Password', 'FullName', 'NoPhone', 'Email', 'Address', 'City', 'State', 'PostalCode'
    ];

    protected $hidden = ['Password']; // Hide password field when retrieving data

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}