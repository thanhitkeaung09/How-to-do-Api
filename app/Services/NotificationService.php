<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NotificationService
{
    public function notiList()
    {
        // return "some";
        $admins = Auth::user()->admins->map(fn ($admin) => $admin->id)->toArray();
        $posts = Post::with('postRead')
            ->whereIn("admin_id", $admins)
            ->latest()
            ->paginate(10);
        foreach ($posts as $post) {
            if ($post->postRead->count() > 0) {
                $post->is_read = true;
            }
            $post->short_words = Str::limit(strip_tags($post->body_text_image), 100);
            unset($post->usersSave, $post->postRead, $post->admin_id, $post->user_id, $post->category_id, $post->body_text_image);
        }
        return $posts->getCollection();
    }
}
