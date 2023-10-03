<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\AdResource;
use App\Models\CustomAd;
use App\Services\FileStorage\FileStorageService;

class AdminAdService
{
    public function __construct(
        public FileStorageService $fileStorageService
    ) {
    }
    public function get_ads()
    {
        return AdResource::collection(CustomAd::query()->latest("id")->get());
    }

    public function single_ads($type)
    {
        $ad = CustomAd::find($type);
        return new AdResource($ad);
    }

    public function create_ads($request)
    {
        $path = $this->fileStorageService->upload(
            config('filesystems.folders.icons'),
            $request->ad_image
        );
        CustomAd::create([
            "admin_id" => auth()->id(),
            "ad_image" => $path,
            "ad_link" => $request->ad_link,
            "is_active" => $request->is_active
        ]);
        return "Ad is created successfully";
    }

    public function delete_ads($type)
    {
        $ad = CustomAd::find($type);
        $ad->delete();
        return "Ad is deleted successfully";
    }

    public function update_ads($type, $request)
    {
        $ad = CustomAd::find($type);
        $ad->admin_id = auth()->id();
        if ($request->ad_image) {
            $path = $this->fileStorageService->upload(
                config('filesystems.folders.icons'),
                $request->ad_image
            );
            $ad->ad_image = $path;
        }
        $ad->ad_link = $request->ad_link;
        $ad->is_active = $request->is_active === 'true';
        $ad->update();
        return "Ad is updated Successfully";
    }
}
