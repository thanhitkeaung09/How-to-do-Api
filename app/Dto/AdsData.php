<?php

declare(strict_types=1);

namespace App\Dto;

use Illuminate\Http\UploadedFile;

class AdsData implements Dto
{
    public function __construct(
        public string $admin_id,
        public UploadedFile $ad_image,
        public string $ad_link,
        public string $is_active
    ) {
    }

    public static function of($data): AdsData
    {
        return new AdsData(
            admin_id: $data['admin_id'],
            ad_image: $data['ad_image'],
            ad_link: $data['ad_link'],
            is_active: $data['is_active']
        );
    }

    public function toArray(): array
    {
        return [
            "admin_id" => $this->admin_id,
            "ad_image" => $this->ad_image,
            "ad_link" => $this->ad_link,
            "is_active" => $this->is_active
        ];
    }
}
