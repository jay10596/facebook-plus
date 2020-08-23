<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;

use App\User;


class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return UserResource::collection($users);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(User $user)
    {
        return [new UserResource($user), new PostCollection($user->posts()->latest()->get())];
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->all());

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function destroy($id)
    {
        //
    }
}
