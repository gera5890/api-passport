<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        //Se debe de hashear siempre despues de crear al usuario para mayor seguridad, es la mejor practica
        $user['password'] = Hash::make($request->password);
        return response()->json([
            'user' => $user,
            200
        ]);
    }
}
