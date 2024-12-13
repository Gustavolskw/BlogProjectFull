<?php

namespace App\Http\Controllers;

use App\Interfaces\CommentRepositoryInterface;
use App\Models\comments;
use App\Traits\HttpResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{

    use HttpResponse;

    private CommentRepositoryInterface $commentRepositoryInterface;

    public function __construct(CommentRepositoryInterface $commentRepositoryInterface)
    {
        $this->commentRepositoryInterface = $commentRepositoryInterface;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comentario' => ['required', 'string', 'max:100', 'min:3'],
            'topico' => ['required', 'numeric', 'exists:threads,id']
        ]);
        if ($validator->fails()) {
            return $this->errorResponse("Erro de Validação!", 422, $validator->errors());
        }
        $valData = $validator->validated();
        $user = Auth::user();

        $newComment = new comments([
            'comment' => $valData['comentario']
        ]);
        $newComment->user()->associate($user->id);
        $newComment->thread()->associate($valData['topico']);

        try {
            $comentarioFeito = $this->commentRepositoryInterface->storeComment($newComment);
            return $this->successResponse("Sucesso!", 200, $comentarioFeito);
        } catch (Exception $e) {
            return $this->errorResponse("Erro ao Registar comentario", 400, (object) ['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'comentario' => ['required', 'string', 'max:100', 'min:3'],
        ]);
        if ($validator->fails()) {
            return $this->errorResponse("Erro de Validação!", 422, $validator->errors());
        }

        $idValidator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric', 'exists:comments,id']
        ]);

        if ($idValidator->fails()) {
            return $this->errorResponse("Erro de Validação de Id", 422, $idValidator->errors());
        }
        $validateData = $validator->validated();
        $valId = $idValidator->validated();
        try {
            $oldComment = $this->commentRepositoryInterface->getCommentById($valId['id']);
        } catch (Exception $e) {
            return $this->errorResponse("Erro ao Buscar Antigo Comentario!", 400, (object) ['error' => $e->getMessage()]);
        }
        $user = Auth::user();
        if ($user->accessController->access_level != 2) {
            if ($oldComment->user->id != $user->id) {
                return $this->errorResponse("Usuario não pode alterar o Comentario", 403, (object) []);
            }
        }
        $updtComment = new comments([
            'comment' => $validateData['comentario']
        ]);

        try {
            $responseUpdte = $this->commentRepositoryInterface->updateComment($updtComment, $oldComment);
            return $this->successResponse("Sucesso ao Editar Comentario!", 200, $responseUpdte);
        } catch (Exception $e) {
            return $this->errorResponse("Erro ao Alterar o comentario", 400, (object) ['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric', 'exists:comments,id']
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Erro de Validação!', 422, $validator->errors());
        }
        $user = Auth::user();
        try {
            $comment = $this->commentRepositoryInterface->getCommentById($id);
        } catch (Exception $e) {
            return $this->errorResponse("Erro ao buscar Comentario do Banco de Dados!", 400, (object) ['error' => $e->getMessage()]);
        }
        if ($user->accessController->access_level != 2) {
            if ($user->id != $comment->user->id) {
                return $this->errorResponse("Usuario não é o Dono do comentario!", 403, (object) []);
            }
        }
        try {
            $this->commentRepositoryInterface->destroyComment($comment);
            return $this->successResponse("Sucesso ao Excluir Comentario!", 200, (object) []);
        } catch (Exception $e) {
            return $this->errorResponse("Erro ao Excluir Comentario", 400, (object) ['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric', 'exists:comments,id']
        ]);
        if ($validator->fails()) {
            return $this->errorResponse("Erro de Validação!", 422, $validator->errors());
        }

        $validatedId = $validator->validated();
        try {
            return $this->successResponse("Sucesso!", 200, $this->commentRepositoryInterface->showById($validatedId['id']));
        } catch (Exception $e) {
            return $this->errorResponse("Erro ao buscar comentario!", 400, (object) ['error' => $e->getMessage()]);
        }
    }

    public function showByThreadId($threadId)
    {
        $validatorId  = Validator::make(['threadId' => $threadId], [
            'threadId' => ['required', 'numeric', 'exists:threads,id'],
        ]);

        if ($validatorId->fails()) {
            return $this->errorResponse("Erro de Validação", 422, $validatorId->errors());
        }

        try {
            $comments = $this->commentRepositoryInterface->showByThread($threadId);
            return $this->successResponse("Sucesso!", 200, $comments);
        } catch (Exception $e) {
            return $this->errorResponse("Erro ao Buscar Commentarios em um tópico!", 400, (object) ['error' => $e->getMessage()]);
        }
    }
}