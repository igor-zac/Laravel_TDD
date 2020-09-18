<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['amount', 'project_id', 'user_id', 'isValid'];

    protected $casts = [
        'amount' => 'integer',
        'user_id' => 'integer',
        'project_id' => 'integer',
        'isValid' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }
}
