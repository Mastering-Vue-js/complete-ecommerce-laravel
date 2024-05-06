<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'status',
        'payment_method',
        'payment_status',
        'name',
        'phone',
        'email',
        'line1',
        'line2',
        'city',
        'country',
        'notes'
    ];
}
