<?php

namespace App\Models;

use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'email',
        'status',
        'user_id',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Dynamic Balance Calculation: Total Receivables
    public function getBalanceAttribute()
    {
        // Total Credit Sales (where payment_type is credit)
        // Note: We might want all sales if we track partial payments, 
        // but the prompt implied "credit balance". 
        // Logic: (Sum of all CREDIT Sales Total) - (Sum of all Payments)
        $totalSales = $this->sales()->where('payment_type', 'credit')->sum('total_amount');
        $totalPayments = $this->payments()->sum('amount');

        return (float) $totalSales - (float) $totalPayments;
    }
}
