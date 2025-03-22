<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiries extends Model
{
    use HasFactory;

    protected $table = 'inquiries';
    protected $primaryKey = 'InquiriesID'; // Make sure this matches your database column

    public $incrementing = false; // Since INQ0001 is a string, it's NOT auto-incrementing
    protected $keyType = 'string'; // Ensure it's treated as a string

    protected $fillable = ['InquiriesID', 'Email', 'InquiriesTitle', 'Description', 'DateCreated'];
}
