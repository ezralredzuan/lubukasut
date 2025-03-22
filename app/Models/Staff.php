<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';
    protected $primaryKey = 'StaffID';
    public $timestamps = true;

    protected $fillable = [
        'StaffName', 'Address', 'NoPhone', 'Email', 'HiredDate', 'Role', 'Username', 'Password', 'StaffPic'
    ];

    // Define relationship to Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'Role', 'RoleID');
    }
}