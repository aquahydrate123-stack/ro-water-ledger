<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\User;
use App\Models\Invoice; // Added this line
use Illuminate\Database\Eloquent\Factories\HasFactory; // Added this line
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory; // Added this line

    protected $fillable = [
        'customer_id',
        'sale_type',
        'payment_type',
        'quantity',
        'rate',
        'total_amount',
        'sale_date',
        'user_id',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'total_amount' => 'decimal:2',
        'quantity' => 'decimal:2',
        'rate' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

}
