<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('email')) {
            $query->where('email', $request->input('email'));
        }


        $users = $query->paginate(10);

        return UserResource::collection($users);
    }

    public function store(UserRequest $request)
    {
        try{
            DB::beginTransaction();;
            $user = User::create($request->validated);
            $user['password'] = Hash::make($request->password);
            DB::commit();
            return UserResource::make($user);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'error' => 'Error al crear usuario', 'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(User $user)
    {
        $user = User::include()->findOrFail($user);
        return UserResource::make($user);
    }


   
    public function update(UserRequest $request, User $user)
    {
        try{
            DB::beginTransaction();
            $user->update($request->validated());
            DB::commit();
            return UserResource::make($user);   
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'error' => "Error al actualizar al usuario",
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }
}
