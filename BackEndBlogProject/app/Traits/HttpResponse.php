<?php

namespace App\Traits;

trait HttpResponse
{
    public function errorResponse(String $message, int $status, Object $errors ): \Illuminate\Http\JsonResponse
    {
    return response()->json([
        'message' => $message,
        'errors '=>$errors
    ], $status);
   }

    public function successResponse(String $message, int $status, Object $data): \Illuminate\Http\JsonResponse
    {
    return response()->json([
        'message' => $message,
        'data'=>$data
    ], $status);
   }

    public function authSuccessResponse(String  $message, int $status, Object $data, String $token): \Illuminate\Http\JsonResponse
    {
    return response()->json([
        'message' => $message,
        'data'=>$data,
        'token'=>$token
    ], $status);
   }

   public function authResourceDenied(String $message){
       return response()->json([
           'message' => $message,
           'error' => 'Não autorizado'
       ], 401);
   }


}
