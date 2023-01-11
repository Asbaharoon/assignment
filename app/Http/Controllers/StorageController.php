<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;
use App\Events\NewUserRegistered;
use Auth;
use App\Models\User;


class StorageController extends Controller
{

    public function store(StoreUserRequest $request, UserService $userService)
    {
        $avatar = $userService->uploadAvatar($request);

        $user = $userService->createUser($request->validated() + ['avatar' => $avatar]);

        Auth::login($user);

        NewUserRegistered::dispatch($user);

        $userService->sendWelcomeEmail($user);

        return redirect()->route('dashboard');
    }
}
