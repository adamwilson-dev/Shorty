<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Url extends Model
{
    use HasFactory;

    // Define the fillable attributes
    protected $fillable = [
        'long_url',
        'short_url',
        'user_id',
        'total_visits',
        'expiry_date',
        'status',
    ];

    public function getFullShortUrl() {
        return Request::getScheme() . '://' . Request::getHost() . '/' . $this->short_url;
    }
}
