<?php

namespace App\Interfaces;

use App\Models\comments;

interface CommentRepositoryInterface
{

    public function storeComment(comments $comments);
    public function updateComment(comments $updatedComment, comments $oldComment);
    public function destroyComment(comments $comment);
    public function showById($id);

    public function getCommentById($id);
    public function showByThread($threadId);
}
