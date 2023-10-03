<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\AppVersionData;
use App\Http\Requests\AppVersionRequest;
use App\Models\AppVersion;

class AppVersionService
{
    public function get()
    {
        return AppVersion::query()->first();
    }
    public function update($request)
    {
        $version = AppVersionData::fromRequest($request->validated())->toArray();
        $original_version = AppVersion::query()->first();
        $original_version->version = $version['version'];
        $original_version->build_number = $version['build_number'];
        $original_version->android_link = $version['android_link'];
        $original_version->ios_link = $version['ios_link'];
        $original_version->is_force_update = $version['is_force_update'];
        $original_version->update();
        return $original_version;
    }
}
