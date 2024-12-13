<?php

namespace App\Http\Controllers;

use App\Interfaces\CommentLikesRepositoryInterface;
use App\Models\commentLikes;
use App\Traits\HttpResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentLikesController extends Controller
{
    use HttpResponse;

    private CommentLikesRepositoryInterface $commentLikesRepository;

    public function __construct(CommentLikesRepositoryInterface $commentLikesRepositoryInterface)
    {
        $this->commentLikesRepository = $commentLikesRepositoryInterface;
    }


    public function showByCommentId($commentId)
    {

        $validator = Validator::make(['commentId' => $commentId], [
            'commentId' => ['required', 'numeric', 'exists:comments,id']
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Erro de Validação!", 422, $validator->errors());
        }
        $validatedData = $validator->validated();

        try {
            return $this->successResponse("Sucesso!", 200, $this->commentLikesRepository->getByCommentId($validatedData['commentId']));
        } catch (Exception $e) {
            return $this->errorResponse("Erro ao excluir Comentário!", 400, (object)['error' => $e->getMessage()]);
        }
    }
    public function store($commentId)
    {
        $validator = Validator::make(['commentId' => $commentId], [
            'commentId' => ['required', 'numeric', 'exists:comments,id']
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Erro de Validação!", 422, $validator->errors());
        }
        $validatedData = $validator->validated();
        $user = Auth::user();

        $newComment = new commentLikes();
        $newComment->comment()->associate($validatedData['commentId']);
        $newComment->user()->associate($user->id);
        try {
            $this->commentLikesRepository->saveCommentLike($newComment);
            return $this->successResponse("Sucesso!", 201, (object)[]);
        } catch (Exception $e) {
            return $this->errorResponse("Erro ao Registar comentario!", 400, (object) ['error' => $e->getMessage()]);
        }
    }


    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric', 'exists:comment_likes,id']
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Erro de Validação!", 422, $validator->errors());
        }
        $validatedData = $validator->validated();
        $user = Auth::user();

        try {
            $commentLike = $this->commentLikesRepository->getCommentLikeById($validatedData['id']);
        } catch (Exception $e) {
            return $this->errorResponse("Erro ao Buscar Comentario!", 400, (object)['error' => $e->getMessage()]);
        }

        if ($user->accessController->access_level != 2) {
            if ($user->id != $commentLike->user->id) {
                return $this->errorResponse("Usuario não pode deletar este comentario!", 403, (object) []);
            }
        }

        try {
            $this->commentLikesRepository->deleteCommentLike($commentLike);
            return $this->successResponse("Sucesso!", 200, (object) []);
        } catch (Exception $e) {
            return $this->errorResponse("Erro ao excluir Comentário!", 400, (object)['error' => $e->getMessage()]);
        }
    }
}