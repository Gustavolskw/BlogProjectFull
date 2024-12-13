<?php

namespace App\Http\Controllers;

use App\Interfaces\ThreadsRepositoryInterface;
use App\Models\Threads;
use App\Traits\HttpResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class ThreadsController extends Controller
{
    use HttpResponse;
    private ThreadsRepositoryInterface $threadsRepository;
    public function __construct(ThreadsRepositoryInterface $threadsRepository)
    {
        $this->threadsRepository = $threadsRepository;
    }

    public function index()
    {
        try {
            return $this->successResponse("Sucesso!", 200, $this->threadsRepository->allThreads());
        } catch (Exception $e) {
            return $this->errorResponse("Erro ao Realizar a Busca dos Tópicos", 400, (object) [$e->getMessage()]);
        }
    }

    public function show($id)
    {
        $validatorId  = Validator::make(['id' => (int)$id], [
            'id' => ['required', 'numeric'],
        ]);
        if ($validatorId->fails()) {
            return $this->errorResponse('validation error', 422, $validatorId->errors());
        }

        try {
            $thread = $this->threadsRepository->getThread($id);
            if ($thread == null) {
                return $this->errorResponse("Tópico de Id: $id inexistente!", 404, (object) []);
            }
            return $this->successResponse('Sucesso!', 200, $thread);
        } catch (Exception $e) {
            return $this->errorResponse('Erro ao Buscar Tópico', 400, (object) [$e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => ['required', 'string'],
            'descricao' => ['required', 'string', 'max:300', 'min:3'],
            'imagem' => ['nullable', 'file', 'image']
        ]);


        if ($validator->fails()) {
            return $this->errorResponse("Erro de Validação", 422, $validator->errors());
        }

        $data = $validator->validated();

        // Check if 'imagem' is present before accessing it
        if (isset($data['imagem'])) {

            $imgName = $this->saveImage($data['imagem']);
        }
        try {
            $newThread = new Threads([
                'title' => $data['titulo'],
                'description' => $data['descricao'],
                'thread_img' => $imgName ? $imgName : null
            ]);

            $user = Auth::user();
            $newThread->user()->associate($user->id);
        } catch (Exception $e) {
            return $this->errorResponse("Erro ao Criar Criar Thread!", 422, (object) [$e->getMessage()]);
        }
        try {
            $threadSave = $this->threadsRepository->createThread($newThread);
            return $this->successResponse("Tópico criado com sucesso!", 201, $threadSave);
        } catch (Exception $e) {
            $this->errorResponse("Erro ao Salvar Thread no banco de dados", 422, (object) [$e->getMessage()]);
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {

        $dataValidator = Validator::make($request->all(), [
            'titulo' => ['required', 'string', 'min:5', 'max:100'],
            'descricao' => ['required', 'string', 'max:300', 'min:3'],
            'imagem' => ['nullable', 'file', 'image']
        ]);

        if ($dataValidator->fails()) {
            return $this->errorResponse("Erro de Validação!", 422, $dataValidator->errors());
        }

        $validatorId  = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric', 'exists:threads,id'],
        ]);

        if ($validatorId->fails()) {
            return $this->errorResponse('validation error', 422, $validatorId->errors());
        }

        try {
            $threadOld = $this->threadsRepository->getSingleThread($id);
        } catch (Exception $e) {
            return $this->errorResponse("Erro ao Buscar Dados do Tópico!", 400, (object) ['error' => $e->getMessage()]);
        }

        $user = Auth::user();
        if ($user->accessController->access_level != 2) {
            if ($threadOld->user->id != $user->id) {
                return $this->errorResponse("Usuario não é o Dono desse Tópico!", 403, (object) []);
            }
        }
        if ($threadOld->thread_img != null) {
            try {
                $this->imageDelete($threadOld->thread_img);
            } catch (Exception $e) {
                return $this->errorResponse("Erro ao Excluir imagem do Tópico!", 400, (object) ['error' => $e->getMessage()]);
            }
        }
        $validatedData = $dataValidator->validated();
        try {
            $imgName  = $this->saveImage($validatedData['imagem']);
            $updateThread = new Threads([
                'title' => $validatedData['titulo'],
                'description' => $validatedData['descricao'],
                'thread_img' => $imgName
            ]);


            $threadReturn = $this->threadsRepository->updateThread($id, $updateThread);
            return $this->successResponse("Sucesso!", 200, $threadReturn);
        } catch (Exception $e) {
            return $this->errorResponse("Erro ao atualizar dados do Tópico!", 400, (object) ['error' => $e->getMessage()]);
        }
    }



    public function imageGet($image)
    {
        $validator  = Validator::make(['image' => $image], [
            'image' => ['required', 'string', 'exists:threads,thread_img'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Erro de Validação", 422, $validator->errors());
        }

        $validatedData = $validator->validated();

        try {
            // Caminho completo da imagem
            $imagePath = 'uploads/' . $validatedData['image'];
            if (Storage::disk('public')->exists($imagePath)) {
                // Retornar o arquivo para download
                return response()->file(storage_path("app/public/" . $imagePath));
            } else {
                return $this->errorResponse('Imagem não encontrada!', 404, (object) ['error' => 'Imagem não existe no servidor.']);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Erro ao Buscar Imagem!', 400, (object) ['error' => $e->getMessage()]);
        }
    }


    ///provavel desuso
    public function showlatest($offset)
    {
        $validator  = Validator::make(['offset' => $offset], [
            'offset' => ['required', 'numeric', 'max:6', 'min:3'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Erro de Validação", 422, $validator->errors());
        }
        try {
            return $this->successResponse("Sucesso!", 200, $this->threadsRepository->latestsThreads($offset));
        } catch (Exception $e) {
            return $this->errorResponse("Erro ao Buscar ultimos $offset threads!", 400, (object) ['error' => $e->getMessage()]);
        }
    }

    public function destroy($id): JsonResponse
    {
        $validatorId  = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric', 'exists:threads,id'],
        ]);

        if ($validatorId->fails()) {
            return $this->errorResponse('validation error', 422, $validatorId->errors());
        }
        try {
            $thread = $this->threadsRepository->getSingleThread($id);
            $this->imageDelete($thread->thread_img);
            $this->threadsRepository->deleteThread($id);
            return $this->successResponse("Sucesso ao Deletar Imagem", 202, (object) []);
        } catch (Exception $e) {
            return $this->errorResponse('Erro ao Excluir Tópico indicado!', 400, (object) ['error' => $e->getMessage()]);
        }
    }








    protected function saveImage($image): String
    {
        // Generate a unique name for the image (using uniqid and the original extension)
        $filename = $image->getClientOriginalName(); // get the file name
        $getfilenamewitoutext = pathinfo($filename, PATHINFO_FILENAME);

        $imgName = uniqid() . $getfilenamewitoutext . '.' . $image->getClientOriginalExtension();

        // Store the image in the 'uploads' directory (you can customize the path)
        $image->storeAs('uploads', $imgName, 'public');

        return $imgName;
    }

    protected function imageDelete($image)
    {
        // Caminho completo da imagem
        $imagePath = 'uploads/' . $image;
        if (Storage::disk('public')->exists($imagePath)) {
            // Deletar a imagem
            Storage::disk('public')->delete($imagePath);
            return true;
        } else {
            return false;
        }
    }
}
