<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Serializers\ItemSerializer;
use App\Serializers\ItemsSerializer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\CommonMark\CommonMarkConverter;
use Auth;

class StorageController extends Controller
{

    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    
        // 2. Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        // 3. Upload the avatar file and update the user
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar')->store('avatars');
            $user->update(['avatar' => $avatar]);
        }
    
        // 4. Login
        Auth::login($user);
    
        // 5. Generate a personal voucher
        $voucher = Voucher::create([
            'code' => Str::random(8),
            'discount_percent' => 10,
            'user_id' => $user->id
        ]);
    
        // 6. Send that voucher with a welcome email
        $user->notify(new NewUserWelcomeNotification($voucher->code));
    
        // 7. Notify administrators about the new user
        foreach (config('app.admin_emails') as $adminEmail) {
            Notification::route('mail', $adminEmail)
                ->notify(new NewUserAdminNotification($user));
        }
    
        return redirect()->route('dashboard');
    }
}