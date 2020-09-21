<?php

namespace App\Models;

use App\Events\PaymentRegistered;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Payment extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = ['amount', 'user_id', 'donation_id'];

    protected $casts = [
        'amount' => 'integer',
        'donation_id' => 'integer'
    ];

    protected $dispatchesEvents = [
        'created' => PaymentRegistered::class
    ];

    public function donation()
    {
        return $this->belongsTo('App\Models\Donation');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
