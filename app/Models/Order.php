<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders'; // Table name

    protected $primaryKey = 'OrderID'; // Primary key

    public $timestamps = true; // Enable timestamps

    protected $fillable = [
        'ReferenceNo',
        'CustomerID',
        'Address',
        'City',
        'State',
        'PostalCode',
        'created_at',
        'DeliveryStatus',
        'ShippedDate'
    ];

    // Relationship with Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }

    // Relationship with Payment (Checking if order is paid)
    // public function payment()
    // {
    //     return $this->hasOne(Payment::class, 'OrderID', 'OrderID');
    // }
}