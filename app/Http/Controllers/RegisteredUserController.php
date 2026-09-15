<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;


class RegisteredUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.register', ['roles' => User::$schoolRoles]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // 1. Прво валидирамо основне податке корисника
        $userAttributes = $request->validate([
            'name' => ['required'],
            'last_name' => ['required'],
            'role_type' => ['required', \Illuminate\Validation\Rule::in(array_keys(User::$schoolRoles))],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(6)],
            'user_photo' => ['nullable', \Illuminate\Validation\Rules\File::types(['png', 'jpg', 'webp'])],
        ]);

        // 2. Валидирамо податке за библиотеку
        $libraryAttributes = $request->validate([
            'library' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', \Illuminate\Validation\Rules\File::types(['png', 'jpg', 'webp'])],
        ]);

        // 3. ОДЛУКА: Које поље за детаље користимо?
        // Ако је изабран Ученик, узимамо вредност из селекта, иначе из инпута
        $details = ($request->role_type === 'Ученик')
            ? $request->role_details_select
            : $request->role_details_input;

        // 4. Обрада фотографије корисника
        $userPhotoPath = $request->hasFile('user_photo')
            ? $request->user_photo->store('user_photos', 'public')
            : null;

        // 5. Креирамо корисника ручно мапирајући поља
        $user = User::create([
            'name' => $userAttributes['name'],
            'last_name' => $userAttributes['last_name'],
            'email' => $userAttributes['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($userAttributes['password']),
            'role_type' => $userAttributes['role_type'],
            'role_details' => $details, // Ово смо извукли из корака 3
            'approved' => 0, // Корисник није одобрен по дефолту
            'remember_token' => \Illuminate\Support\Str::random(10),
            'user_photo' => $userPhotoPath,
        ]);

        // 6. Обрада логотипа
        $logoPath = $request->hasFile('logo')
            ? $request->logo->store('logos', 'public')
            : null;

        // 7. Креирање библиотеке повезане са корисником
        $user->library()->create([
            'owner_id' => $user->id,
            'name' => $libraryAttributes['library'] ?: 'Моја библиотека',
            'logo' => $logoPath,
        ]);

        // 7. Преусмеравање са поруком
        return redirect('/login')->with('message', 'Хвала за регистрацију! Сачекајте одобрење администратора.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
