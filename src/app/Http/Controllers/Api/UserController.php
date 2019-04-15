<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * @return UserResource
     */
    public function index(): UserResource
    {
        $user = auth()->user();

        $data = new UserResource($user);

        return $data->withSensitiveData();
    }

    /**
     * @param UpdateUserRequest $request
     * @return UserResource
     */
    public function update(UpdateUserRequest $request): UserResource
    {
        $user = $request->user();

        $data = [];

        if ($request->get('email', '')) {
            $data['email'] = $request->get('email');
        }

        if ($request->get('first_name', '')) {
            $data['first_name'] = $request->get('first_name');
        }

        if ($request->get('last_name', '')) {
            $data['last_name'] = $request->get('last_name');
        }

        if (!$request->has('without_password')) {
            $data['password'] = bcrypt($request->get('password'));
        }

        $user->update($data);

        $data = new UserResource($user);

        return $data->withSensitiveData();
    }

}
