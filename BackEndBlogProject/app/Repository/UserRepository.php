<?php

namespace App\Repository;

use App\Http\Resources\UserResponse;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{

    /**
     * @return Collection|AnonymousResourceCollection
     */
    public function getAllUsers(): Collection|AnonymousResourceCollection
    {
        $users = User::all();
        if($users->count() == 0 ){
            return collect();
        }else{
            return UserResponse::collection($users);
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserById($id): mixed
    {
        $user = User::where('id', $id)->first();
        if($user === null){
            return collect();
        }else{
            return new UserResponse($user);
        }
    }

    /**
     * @param $email
     * @return mixed
     */
    public function getUserByEmail($email): mixed
    {
        $user = User::where('email', 'like', $email)->first();
        if($user === null){
            return null;
        }else{
            return $user;
        }
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function createUser(User $user): mixed
    {
        $user->save();
        return new UserResponse($user);
    }

    /**
     * @param User $user
     * @param int $id
     * @return mixed
     */
    public function updateUser(User $user, int $id)
    {
        $userToEdit = User::find($id);
        if($user->name){
            $userToEdit->name = $user->name;
        }
        if($user->password){
            $userToEdit->password = $user->password;
        }
        if($user->email){
            $userToEdit->email = $user->email;
        }
        if($user->accessController()){
            $userToEdit->accessController()->associate($user->accessController());
        }
        $userToEdit->save();
        return new UserResponse($userToEdit);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteUser($id):void
    {
        $user = User::find('id', $id);
        $user->ativo = false;
    }


}
