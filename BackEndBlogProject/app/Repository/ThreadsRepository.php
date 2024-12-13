<?php

namespace App\Repository;

use App\Http\Resources\ThreadsResponse;
use App\Http\Resources\ThreadWithPosts;
use App\Interfaces\ThreadsRepositoryInterface;
use App\Models\Threads;
use Illuminate\Pagination\LengthAwarePaginator;

class ThreadsRepository implements ThreadsRepositoryInterface
{

    /**
     * @return mixed
     */
    public function allThreads()
    {
        $threads = Threads::paginate(15);

        return (object)[
            'dados' => $threads->count() ? ThreadsResponse::collection($threads) : collect(),
            'total' => $threads->total(),
            'current_page' => $threads->currentPage(),
            'last_page' => $threads->lastPage(),
            'per_page' => $threads->count()

        ];
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getThread($id)
    {
        $thread = Threads::find($id);
        return $thread != null ? new ThreadWithPosts($thread) : null;
    }



    /**
     * @param array $data
     * @return mixed
     */
    public function createThread(Threads $thread)
    {
        $thread->save();
        return new ThreadsResponse($thread);
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function updateThread($id, Threads $reciver)
    {
        $thread = Threads::find($id);
        $thread->title = $reciver->title;
        $thread->description = $reciver->description;
        $thread->thread_img = $reciver->thread_img;
        $thread->save();

        return $thread;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteThread($id)
    {
        Threads::find($id)->delete();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function latestsThreads($offset)
    {
        $latestsThreads = Threads::where('thread_img', "!=", '')
            ->orderBy('created_at', 'DESC')
            ->limit($offset)->get();

        return ThreadsResponse::collection($latestsThreads);
    }

    public function getSingleThread($id)
    {
        $thread = Threads::find($id);
        return $thread != null ? $thread : null;
    }
}
