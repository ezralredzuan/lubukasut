<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;


    protected $table = 'events';
    protected $primaryKey = 'EventID'; // Set EventID as the primary key
    public $timestamps = true;

    public $incrementing = false; // Prevent Laravel from auto-incrementing
    protected $keyType = 'integer'; // Ensure EventID is treated as an integer

    protected $fillable = [
        'EventID',
        'Title',
        'Status',
        'StaffID',
        'created_at',
        'updated_at',
    ];

    // Boot method to generate a random EventID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            $event->EventID = rand(100, 999); // Generate a random number between 100 and 999
        });
    }
}
