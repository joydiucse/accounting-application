<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'logo',
        'phone',
        'email',
        'website',
    ];

    /**
     * Get the company profile (singleton pattern).
     */
    public static function getProfile()
    {
        return static::first() ?? static::create([
            'name' => 'Your Company Name',
            'address' => 'Your Company Address',
        ]);
    }
}