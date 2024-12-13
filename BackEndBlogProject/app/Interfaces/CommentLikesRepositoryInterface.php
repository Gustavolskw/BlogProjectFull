<?php

namespace App\Interfaces;

use App\Models\commentLikes;

interface CommentLikesRepositoryInterface
{
    public function saveCommentLike(commentLikes $newcommentLike);
    public function deleteCommentLike(commentLikes $commentLikes);

    public function getCommentLikeById($id);

    public function getByCommentId($commentId);
}