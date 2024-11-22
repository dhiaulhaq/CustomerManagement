<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'photo',
        'email',
        'phone',
        'gender',
        'birth_date',
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
