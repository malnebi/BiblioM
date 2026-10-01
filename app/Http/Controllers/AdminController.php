<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\User;
use App\Models\ProfileChangeRequest;
use Illuminate\Support\Facades\Storage;
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
        $pendingProfileChanges = ProfileChangeRequest::where('status', 'pending')
            ->with(['user', 'library'])
            ->latest()
            ->get();

        return view('admin.dashboard', compact('pendingUsers', 'pendingTags', 'pendingProfileChanges'));
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

    public function updateTagName(Request $request, Tag $tag)
    {
        abort_if($tag->approved, 404);

        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('tags', 'name')->ignore($tag->id)],
        ]);

        $tag->update($attributes);

        return back()->with('status', 'Назив ознаке је исправљен.');
    }

    public function rejectTag(Tag $tag)
    {
        abort_if($tag->approved, 404);

        $tag->delete();

        return back()->with('status', 'Предлог ознаке је одбијен.');
    }

    public function approveProfileChange(ProfileChangeRequest $profileChangeRequest)
    {
        abort_unless($profileChangeRequest->status === 'pending', 404);

        $user = $profileChangeRequest->user;
        $oldPhoto = $user->user_photo;
        $library = $profileChangeRequest->library;
        $oldLibraryLogo = $library?->logo;

        if ($profileChangeRequest->requested_name !== null) {
            $user->name = $profileChangeRequest->requested_name;
            $user->last_name = $profileChangeRequest->requested_last_name;
        }

        if ($profileChangeRequest->requested_photo !== null) {
            $user->user_photo = $profileChangeRequest->requested_photo;
        }

        $user->save();

        if ($library && $profileChangeRequest->requested_library_name !== null) {
            $library->name = $profileChangeRequest->requested_library_name;
        }

        if ($library && $profileChangeRequest->requested_library_logo !== null) {
            $library->logo = $profileChangeRequest->requested_library_logo;
        }

        $library?->save();
        $profileChangeRequest->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        if ($profileChangeRequest->requested_photo !== null && $oldPhoto) {
            Storage::disk('public')->delete($oldPhoto);
        }

        if ($profileChangeRequest->requested_library_logo !== null && $oldLibraryLogo) {
            Storage::disk('public')->delete($oldLibraryLogo);
        }

        return back()->with('status', 'Захтјев за измјену профила и библиотеке је одобрен.');
    }

    public function rejectProfileChange(ProfileChangeRequest $profileChangeRequest)
    {
        abort_unless($profileChangeRequest->status === 'pending', 404);

        $profileChangeRequest->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        if ($profileChangeRequest->requested_photo) {
            Storage::disk('public')->delete($profileChangeRequest->requested_photo);
        }

        if ($profileChangeRequest->requested_library_logo) {
            Storage::disk('public')->delete($profileChangeRequest->requested_library_logo);
        }

        return back()->with('status', 'Захтјев за измјену профила и библиотеке је одбијен.');
    }
}
