<?php

namespace App\Services;

use App\Models\User;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Voucher;
use Illuminate\Support\Str;
use App\Notifications\NewUserWelcomeNotification;
use League\CommonMark\CommonMarkConverter;
use Illuminate\Http\JsonResponse;
use App\Serializers\ItemSerializer;


class UserService
{

    public function uploadAvatar(Request $request): ?string
    {
        // Avatar upload and update user
        return ($request->hasFile('avatar'))
            ? $request->file('avatar')->store('avatars')
            : NULL;
    }

    public function createUser(array $userData)
    {
        // Create user
        return User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
            'avatar' => $userData['avatar']
        ]);
    }

    // public function createItem(Request $request)
    // {
    //     // $this->validator($request);
    //     $converter = new CommonMarkConverter(['html_input' => 'escape', 'allow_unsafe_links' => false]);
    //     $item = Item::create([
    //         'name' => $request->get('name'),
    //         'price' => $request->get('price'),
    //         'url' => $request->get('url'),
    //         'description' => $converter->convert($request->get('description'))->getContent(),
    //     ]);
    //     // $item = Item::create($request);
    //     $serializer = new ItemSerializer($item);

    //     return new JsonResponse(['item' => $serializer->getData()]);
    // }

    public function createVoucherForUser(int $userId): string
    {
        $voucher = Voucher::create([
            'code' => Str::random(8),
            'discount_percent' => 10,
            'user_id' => $userId
        ]);

        return $voucher->code;
    }

    public function sendWelcomeEmail(User $user)
    {
        $voucherCode = $this->createVoucherForUser($user->id);
        $user->notify(new NewUserWelcomeNotification($voucherCode));
    }

    // public function validator(Request $request)
    // {
    //     $this->validate($request, [
    //         'name' => 'required|string|max:255',
    //         'price' => 'required|numeric',
    //         'url' => 'required|url',
    //         'description' => 'required|string',
    //     ]);
    // }
}
