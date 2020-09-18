<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'donation_id'];

    protected $casts = [
      'amount' => 'integer',
      'donation_id' => 'integer'
    ];

    public function donation()
    {
        return $this->belongsTo('App\Models\Donation');
    }
}
