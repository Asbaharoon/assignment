<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;
use App\Events\NewUserRegistered;
use Illuminate\Support\Facades\Auth;

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
