<?php

namespace App\Http\Controllers;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{

    use HttpResponse;

    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", 422, $validator->errors());
        }
        $newUser = new User([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);
        $newUser->accessController()->associate(3);
        try {
            $userSave = $this->userRepository->createUser($newUser);
            return $this->successResponse("Usuario Registrado!", 201, $userSave);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422, (object)[]);
        }
    }

    public function storeAdmin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => ['required', 'numeric', 'exists:access_controls,id_access_type']
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", 422, $validator->errors());
        }
        $validatedData = $validator->validated();

        $newUser = new User([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($validatedData['password']),
        ]);
        $newUser->accessController()->associate($validatedData['role']);
        try {
            $userSave = $this->userRepository->createUser($newUser);
            return $this->successResponse("Usuario Registrado!", 201, $userSave);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422, (object)[]);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $users = $this->userRepository->getAllUsers();
            return $this->successResponse("Sucesso!", 200, $users);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422, (object)[]);
        }
    }

    public function show(int $id): JsonResponse
    {

        $validatorId  = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric', 'exists:users,id'],
        ]);
        if ($validatorId->fails()) {
            return $this->errorResponse('validation error', 422, $validatorId->errors());
        }

        try {
            return $this->successResponse("Sucesso!", 200, $this->userRepository->getUserById($id));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400, (object)[]);
        }
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email',],
            'password' => ['required', 'min:5']
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Erro de Validação", 422, $validator->errors());
        }
        try {
            $user = $this->userRepository->getUserByEmail($request->get('email'));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422, (object)[]);
        }
        try {
            if (!$user || !Hash::check($request->get('password'), $user->password)) {
                return $this->errorResponse("Credenciais incorretas", 401, (object)[]);
            }


            $user->tokens()->delete();

            $userTokens = Redis::keys("user:token:*");
            foreach ($userTokens as $userTokenKey) {
                $cleanKey = str_replace('laravel_database_', '', $userTokenKey);

                // Get the token data from Redis using the cleaned key
                $tokenData = Redis::get($cleanKey);

                //$tokenData = Redis::get("user:token:{$userTokenKey}");

                if ($tokenData && json_decode($tokenData, true)['id'] === $user->id) {
                    Redis::del($cleanKey);
                }
            }

            $abilities = [];
            if ($user->accessController->access_level == 0) {
                $abilities = [(string) 0, (string) 0, (string) 0];
            } else {

                for ($i = 0; $i <= $user->accessController->access_level; $i++) {
                    $abilities[] = (string)$i;
                }
            }
            $token = $user->createToken($user->name . uniqid(), $abilities, now()->addHours(12));

            // Armazena os dados do usuário no Redis, usando o token como chave
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role_name' => $user->accessController->access_name,
                'role' => $user->accessController->access_level,
            ];

            // Salva os dados no Redis com expiração de 12 horas (mesma validade do token)
            Redis::setex("user:token:{$token->plainTextToken}", 43200, json_encode($userData));


            return $this->authSuccessResponse("User Logged In with success!", 200, (object)[], $token->plainTextToken);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400, (object)[]);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'newName' => 'string',
            'email' => 'required|email',
            'newEmail' => 'email',
            'password' => 'required',
            'newPassword' => 'string',
            'role' => ['required', 'numeric', 'exists:access_controls,id_access_type']
        ]);
        $validatorId  = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric', 'exists:users,id'],
        ]);
        if ($validatorId->fails()) {
            return $this->errorResponse('validation error', 422, $validatorId->errors());
        }
        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", 422, $validator->errors());
        }
        $updUser  = new User([
            'name' => $request->get('newName') ? $request->get('newName') : null,
            'email' => $request->get('newEmail') ? $request->get('newEmail') : null,
            'password' => Hash::make($request->get('newPassword')) ? $request->get('newPassword') : null,
        ]);
        if ($request->get('role') != null) {
            $updUser->accessController()->associate($request->get('role'));
        }
        try {
            $response  = $this->userRepository->updateUser($updUser, $id);
            return $this->successResponse("Usuario Actualizado!", 200, $response);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422, (object)[]);
        }
    }

    public function delete($id): JsonResponse
    {
        $validatorId  = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric', 'exists:users,id'],
        ]);
        if ($validatorId->fails()) {
            return $this->errorResponse('validation error', 422, $validatorId->errors());
        }
        try {
            $this->userRepository->deleteUser($id);
            return $this->successResponse("Usuario inativado!", 200, (object)[]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422, (object)[]);
        }
    }

    public function destroy(Request $request): JsonResponse
    {
        if (!$request->user()) {
            return $this->errorResponse("Usuario não encontrado na sessão!", 404, (object)[]);
        }
        $request->user()->tokens()->delete();
        return $this->successResponse("Logout realizado!", 200, (object)[]);
    }

    public function userSess(Request $request): JsonResponse
    {
        // Extrair o token do cabeçalho de autorização
        $token = $request->bearerToken();

        // Verificar se o token foi fornecido
        if (!$token) {
            return $this->errorResponse('Token não fornecido.', 401, (object) []);
        }

        // Buscar os dados do usuário no Redis usando o token
        $userData = Redis::get("user:token:{$token}");

        // Verificar se os dados do usuário foram encontrados
        if (!$userData) {
            return $this->errorResponse('Token inválido ou expirado.', 401, (object) []);
        }

        // Decodificar os dados do usuário de JSON para um array ou objeto
        $userData = json_decode($userData, true);

        // Retornar os dados do usuário
        return $this->successResponse("Sucesso", 200, (object)$userData);
    }
}