<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Services\FileStorage\FileStorageService;
use App\Services\Firebase\FCMService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AdminPostService
{
    public function __construct(
        private FileStorageService $fileStorageService,
    ) {
    }

    public function create_post($request)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = User::whereNotNull('device_token')->pluck('device_token')->all();

        $serverKey = 'AAAAqvUgKEk:APA91bERMVD4kBYlmZUPGJSnI4PachyGnrP49cvVFUtcrxpaL83S9z4J0lQkEIuc4wTkLYgvUS9nxZkW2s0L_dROdIOZgvBSRgPy8PdSQszojziJ7Xp6gk1abnk2YjqY3CbB8MDQ2MPC';

        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $request->article_title,
                "body" => $request->short_words,
            ]
        ];
        dd($data);
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        // dd($result);

        if (is_null($request->cover_image)) {
            return Post::create([
                "admin_id" => $request->admin_id,
                "user_id" => $request->user_id,
                "category_id" => $request->category_id,
                "cover_image" => null,
                "article_title" => $request->article_title,
                "body_text_image" => $request->body_text_image,
                "short_words" => $request->short_words
            ]);
        } else {
            return Post::create([
                "admin_id" => $request->admin_id,
                "user_id" => $request->user_id,
                "category_id" => $request->category_id,
                "cover_image" => $this->fileStorageService->upload(\config('filesystems.folders.profiles'), $request->cover_image),
                "article_title" => $request->article_title,
                "body_text_image" => $request->body_text_image,
                "short_words" => $request->short_words
            ]);
        }
    }

    public function view_post($request)
    {
        $date = $request->date;
        return Post::with('category', 'admin')->when($date, function ($query, $date) {
            $query->whereDate('created_at', $date);
        })->latest()->get();
    }

    public function delete_post($type)
    {
        $post = Post::find($type);
        $post->likes()->delete();
        $post->delete();
        return true;
    }

    public function update_post($type, $request)
    {
        $post = Post::find($type);
        $post->admin_id = $request->admin_id;
        $post->category_id = $request->category_id;

        if ($request->cover_image) {
            $post->cover_image = $this->fileStorageService->upload(\config('filesystems.folders.profiles'), $request->cover_image);
        }

        $post->article_title = $request->article_title;
        $post->body_text_image = $request->body_text_image;
        $post->short_words = $request->short_words;
        $post->update();
        return $post;
    }

    public function total_posts()
    {
        return Post::count();
    }

    public function total_readers()
    {
        return DB::table('post_reads')->count();
    }

    public function like_lists($type)
    {
        $count = Like::with('users')->where('likeable_type', Post::class)->where('likeable_id', $type)->count();
        $users = Like::with('users')->where('likeable_type', Post::class)->where('likeable_id', $type)->latest()->get();
        return ["count" => $count, "like_lists" => $users];
    }

    public function read_lists($type)
    {
        $count = DB::table('post_reads')->where('post_id', $type)->count();
        $reads = DB::table('post_reads')->where('post_id', $type)->get();
        foreach ($reads as $read) {
            $user = User::query()->where("id", $read->user_id)->first();
            $read->user = $user;
        }
        return ["count" => $count, "reads" => $reads];
    }
}
