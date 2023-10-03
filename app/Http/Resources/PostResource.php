<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "admin_id" => $this->admin_id,
            "user_id" => $this->user_id,
            "category_id" => $this->category_id,
            "cover_image" => $this->cover_image,
            "article_title" => $this->article_title,
            "created_at" => Carbon::parse($this->created_at)->format('M d, Y'),
            "updated_at" => $this->updated_at,
            "short_words" => $this->short_words,
            "deleted_at" => $this->deleted_at,
            "post_read_count" => $this->post_read_count,
            "comments_count" => $this->comments_count,
            "likes_count" => $this->likes_count,
            "users_share_count" => $this->users_share_count,
            "is_save" => $this->is_save,
            "is_like" => $this->is_like,
            "type" => $this->type,
            "admin" => $this->admin,
            "category" => $this->category,
            // "timestamp" => $this->timestamp
        ];
    }
}
