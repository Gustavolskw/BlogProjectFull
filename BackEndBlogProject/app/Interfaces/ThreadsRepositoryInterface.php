<?php

namespace App\Interfaces;

use App\Models\Threads;

interface ThreadsRepositoryInterface
{
    public function allThreads();
    public function getThread($id);

    public function getSingleThread($id);
    public function latestsThreads($offset);
    public function createThread(Threads $data);
    public function updateThread($id, Threads $data);
    public function deleteThread($id);
}