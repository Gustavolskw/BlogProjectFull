<?php

namespace App\Repository;

use App\Interfaces\CommentLikesRepositoryInterface;
use App\Models\commentLikes;
use App\Models\comments;

class CommentLikesRepository implements CommentLikesRepositoryInterface
{
    public function saveCommentLike($newcommentLike)
    {
        $newcommentLike->save();
    }

    public function deleteCommentLike($commentLikes)
    {
        $commentLikes->delete();
    }

    public function getCommentLikeById($id)
    {
        return commentLikes::find($id);
    }

    public function getByCommentId($commentId)
    {
        return comments::find($commentId)->commentLikes;
    }
}