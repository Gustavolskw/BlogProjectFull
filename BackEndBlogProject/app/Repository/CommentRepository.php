<?php

namespace App\Repository;

use App\Http\Resources\CommentResponse;
use App\Interfaces\CommentRepositoryInterface;
use App\Models\comments;
use App\Models\Threads;

class CommentRepository implements CommentRepositoryInterface
{
    /**
     * @param comments $comments
     * @return mixed
     */
    public function storeComment(comments $comments)
    {
        $comments->save();
        return new CommentResponse($comments);
    }

    /**
     * @param comments $updatedComment
     * @param int $id
     * @return CommentResponse
     */
    public function updateComment(comments $updatedComment, comments  $oldComment)
    {
        $oldComment->comment = $updatedComment->comment;
        $oldComment->save();
        return new CommentResponse($oldComment);
    }

    /**
     * @param comments $comment
     * @return void
     */
    public function destroyComment(comments $comment)
    {
        $comment->delete();
    }



    /**
     * @param $id
     * @return CommentResponse
     */
    public function getCommentById($id)
    {
        return comments::find($id);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function showById($id)
    {
        return new CommentResponse(comments::find($id));
    }

    /**
     * @param $threadId
     * @return mixed
     */
    public function showByThread($threadId)
    {
        $comments = Threads::find($threadId)->threadComments;

        return CommentResponse::collection($comments);
    }
}
