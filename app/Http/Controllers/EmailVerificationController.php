<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class EmailVerificationController extends Controller
{
    public function __invoke(string $id, string $hash): RedirectResponse
    {
        $user = User::findOrFail($id);

        abort_unless($user->approved, 403);
        abort_unless(hash_equals(sha1($user->getEmailForVerification()), $hash), 403);

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->route('login')->with('message', 'Мејл адреса је потврђена. Сада се можете пријавити.');
    }
}
