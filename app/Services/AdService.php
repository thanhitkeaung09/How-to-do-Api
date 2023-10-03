<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\AdResource;
use App\Models\CustomAd;

class AdService
{
    public function get_ads()
    {
        $ads = CustomAd::query()->where("is_active", true)->get();
        if ($ads->isEmpty()) {
            return null;
            // No records with the column being true
        } else {
            return $ads->random();
            // There are records with the column being true
        }
        // return new AdResource($ad);
    }
}
