<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


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
            'numbering_mode' => ['required', Rule::in(['manual', 'automatic'])],
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

        // 6. Обрада логотипа
        $logoPath = $request->hasFile('logo')
            ? $request->logo->store('logos', 'public')
            : null;

        $libraryName = trim($libraryAttributes['library'] ?? '');

        DB::transaction(function () use ($userAttributes, $details, $userPhotoPath, $libraryName, $logoPath, $libraryAttributes) {
            // Креирамо корисника и библиотеку атомски, да не остане недовршен налог.
            $user = User::create([
                'name' => $userAttributes['name'],
                'last_name' => $userAttributes['last_name'],
                'email' => $userAttributes['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($userAttributes['password']),
                'role_type' => $userAttributes['role_type'],
                'role_details' => $details,
                'approved' => 0,
                'remember_token' => \Illuminate\Support\Str::random(10),
                'user_photo' => $userPhotoPath,
            ]);

            $user->library()->create([
                'owner_id' => $user->id,
                'name' => $libraryName !== ''
                    ? $libraryName
                    : 'Библиотека ' . trim($userAttributes['name']),
                'logo' => $logoPath,
                'numbering_mode' => $libraryAttributes['numbering_mode'],
            ]);
        });

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
