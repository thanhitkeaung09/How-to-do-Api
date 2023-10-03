<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\RateData;
use App\Models\Rate;

class RateService
{
    public function ratestore($request)
    {
        $exits = Rate::query()->where("user_id", auth()->id())->exists();
        $validated = RateData::fromRequest($request->validated());

        if ($exits) {
            $rate_data = $validated->toArray();
            $rate = Rate::query()->where("user_id", auth()->id())->first();
            $rate->rate = $rate_data['rate'];
            $rate->description = $rate_data['description'];
            $rate->update();
            return "updated";
        }
        return Rate::create($validated->toArray());
    }
}
