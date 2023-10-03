<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\CommentEvent;
use App\Events\CommentLikeEvent;
use App\Events\LikeEvent;
use App\Models\Comment;
use App\Models\Post;
use http\Env\Request;
use Illuminate\Support\Facades\DB;

class CommentService
{

    public function commentStore($request)
    {
        // $post = Post::find($request->post_id);
        $comment = Comment::create([
            "user_id" => auth()->id(),
            "post_id" => $request->post_id,
            "text" => $request->text
        ]);
        $comment_count = Comment::query()->where("post_id",$request->post_id)->count();
        event(new CommentEvent(["id"=>$request->post_id,"comment_count"=>$comment_count,"type"=>"post_add_comment"]));
        return $comment;
    }

    public function commentLikeOrNot($request, $type)
    {
        $check = $this->checkExists($type, 'likes', 'likeable_id');
        $comment = Comment::withCount('likes')->find($type);
        // return $comment->likes_count;
        if ($check) {
            $comment->likes()->delete(["user_id" => auth()->id()]);
            $status = false;
        }
        else{
            $comment->likes()->create(["user_id" => auth()->id()]);
            $status = true;
        }

        event(new CommentLikeEvent(["id"=>$comment->id,
        "comment_like_count"=>$comment->likes()->count(),
        "post_id"=>$comment->post_id,"type"=>"post_comment_like"]));

        return $status;
    }

    public function checkExists($value, $table, $table_id)
    {
        return DB::table($table)
            ->where('user_id', auth()->id())
            ->where($table_id, $value)
            ->where('likeable_type', Comment::class)
            ->exists();
    }
}
