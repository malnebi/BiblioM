<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Дохватамо кориснике који нису одобрени, заједно са подацима о њиховој библиотеци
        //$pendingUsers = User::where('approved', false)->with('library')->get();
        $pendingUsers = User::where('approved', 0)->with('library')->get();
        $pendingTags = Tag::where('approved', false)->with(['suggestedBook', 'suggestedBy'])->orderBy('name')->get();

        return view('admin.dashboard', compact('pendingUsers', 'pendingTags'));
     }

    public function approve(User $user)
    {
        // Постављамо на true и чувамо
        $user->update(['approved' => 1]);

        return back()->with('status', 'Корисник је успjешно одобрен!');
    }

    public function updateRole(Request $request, User $user)
    {
        abort_if($user->approved, 404);

        $attributes = $request->validate([
            'role_type' => ['required', Rule::in(array_keys(User::$schoolRoles))],
            'role_details' => ['required_if:role_type,Друго', 'nullable', 'string', 'max:255'],
        ]);

        $user->update($attributes);

        return back()->with('status', 'Улога корисника је исправљена.');
    }

    public function reject(User $user)
    {
        // Бришемо корисника ако га одбијемо
        $user->delete();

        return back()->with('status', 'Регистрација је одбијена и обрисана.');
    }

    public function approveTag(Tag $tag)
    {
        abort_if($tag->approved, 404);

        $tag->update(['approved' => true]);
        if ($tag->book_id) {
            $tag->books()->syncWithoutDetaching([$tag->book_id]);
        }

        return back()->with('status', 'Ознака је одобрена.');
    }

    public function rejectTag(Tag $tag)
    {
        abort_if($tag->approved, 404);

        $tag->delete();

        return back()->with('status', 'Предлог ознаке је одбијен.');
    }
}
