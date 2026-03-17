<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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

        return view('admin.dashboard', compact('pendingUsers'));
     }

    public function approve(User $user)
    {
        // Постављамо на true и чувамо
        $user->update(['approved' => 1]);

        return back()->with('status', 'Корисник је успjешно одобрен!');
    }

    public function reject(User $user)
    {
        // Бришемо корисника ако га одбијемо
        $user->delete();

        return back()->with('status', 'Регистрација је одбијена и обрисана.');
    }
}
