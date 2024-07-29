<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\UtilityService;

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

    public function getFullShortUrl(UtilityService $utilityService) {
        return $utilityService->getHost() . '/' . $this->short_url;
    }
}
