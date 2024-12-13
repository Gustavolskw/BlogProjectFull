<?php

use App\Http\Controllers\CommentLikesController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\ThreadsController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminRoutesMiddleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;



Route::fallback(function () {
    return response(null, 404);
});


Route::post('/register/user', [UserController::class, 'store']);

Route::post('/login', [UserController::class, 'login']);


Route::post('/register/admin', [UserController::class, 'storeAdmin']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/auth/user', [UserController::class, 'userSess']);

    Route::delete('/logout', [UserController::class, 'destroy']);
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/comments/add', [CommentsController::class, 'store']);
    Route::put('/comments/update/{id}', [CommentsController::class, 'update']);
    Route::delete('/comments/delete/{id}', [CommentsController::class, 'destroy']);
    Route::delete('comments/likes/comment/{id}/dislike', [CommentLikesController::class, 'destroy']);
    Route::post('comments/likes/comment/{commentId}/like', [CommentsController::class, 'store']);










    Route::group(['middleware' => ['check-abilities:1,2']], function () {

        Route::post('/threads/add', [ThreadsController::class, 'store']);
        Route::delete('/threads/delete/{id}', [ThreadsController::class, 'destroy']);

        //destivar apos testes
        Route::post('/threads/update/{id}', [ThreadsController::class, 'update']);
        //
        Route::put('/threads/update/{id}', [ThreadsController::class, 'update']);
        Route::get('comments/list/{id}', [CommentsController::class, 'show']);
        Route::post('comments/like/comment/{commentId}', [CommentsController::class, 'showByCommentId']);
    });

    Route::group(['middleware' => ['check-abilities:2']], function () {

        Route::delete('/user/{id}', [UserController::class, 'delete']);
        Route::get('comments/list/all', [CommentsController::class, 'index']);
    });
});

Route::get('/threads/list', [ThreadsController::class, 'index'])->name('threads-list');
Route::get('/threads/list/{offset}', [ThreadsController::class, 'showlatest'])->name('threads-list');
Route::get('/threads/{id}', [ThreadsController::class, 'show']);
Route::get('/threads/img/{image}', [ThreadsController::class, 'imageGet']);
Route::get('/comments/thread/{threadId}', [CommentsController::class, 'showByThreadId']);
