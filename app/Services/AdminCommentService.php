<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Comment;

class AdminCommentService
{
    public function view_all_comments($type)
    {
        $count = Comment::query()->with('users')->where("post_id",$type)->count();
        $comments = Comment::query()->with('users')->where("post_id",$type)->get();

        return ["count"=>$count,"comments"=>$comments];
    }

    public function delete_comment($type)
    {
        $comment = Comment::find($type);
        $comment->likes()->delete();
        $comment->delete();
        return true;
    }

    public function update_comment($type , $request)
    {
        $comment = Comment::find($type);
        $comment->update(['text'=>$request->text]);
        return $comment;
    }
}

