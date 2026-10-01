<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Library;
use App\Models\Loan;
use App\Models\Book;
use App\Models\Tag;
use App\Models\ProfileChangeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::latest()->where('approved', 1)->get();
        $library = Library::all();

        return view('users.index', [
            'users' => $user,
            'libraries' => $library,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            abort(404);
        }

        $user_photo = $user->user_photo;

        $reservedLoans = Loan::where([['user_id', '=', $id], ['library_id', '=', Auth::user()->ownLibrary->id], ['active', '=', null], ['description', '=', 'rezervisano']])->get();
        $inProgressLoans = Loan::where([['user_id', '=', $id], ['active', '=', '1']])
            ->where(function ($query) {
                $query->where('description', '=', null)
                    ->orWhere('description', '=', 'rezervisano');
            })->get();
        $activeLoans = Loan::where([['user_id', '=', $id], ['library_id', '=', Auth::user()->ownLibrary->id], ['active', '=', '1'], ['description', '=', 'potpisano']])->get();

        $returnInProgress = Loan::where('user_id', '=', $id)
            ->where(function ($query) {
                $query->where([['description', '=', null], ['active', '=', '1']]) // potvrđeno vraćanje od strane člana
                    ->orWhere([['description', '=', 'potpisano'], ['active', '=', '0']]); // potvrđeno vraćanje od strane biblioteke
            })->get();

        $overLoans = Loan::where([['user_id', '=', $id], ['active', '=', '0'], ['description', '=', null]])->get();

        $activeLoansCount = $activeLoans->count();
        $overLoansCount = $overLoans->count();
        $allLoansCount = Loan::where('user_id', '=', $id)->count();


        $loans = Loan::where('library_id', Auth::user()->ownLibrary->id)
            ->orderBy('created_at', 'asc')
            ->with(['user', 'book', 'library'])
            ->paginate(20);


        // ИД корисника на чијој смо страници 
        $targetUserId = $user->id;

        $books = Book::where('library_id', Auth::user()->ownLibrary->id)
            ->where('loan', 0) // Слободне књиге
            ->whereDoesntHave('loans', function ($query) use ($targetUserId) {
                $query->where('description', 'rezervisano')
                    ->where('user_id', $targetUserId);
            })
            ->get();


        $data = [
            'user' => $user,
            'user_photo' => $user_photo,
            'reservedLoans' => $reservedLoans,
            'inProgressLoans' => $inProgressLoans,
            'returnInProgress' => $returnInProgress,
            'activeLoans' => $activeLoans,
            'activeLoansCount' => $activeLoansCount,
            'overLoans' => $overLoans,
            'overLoansCount' => $overLoansCount,
            'allLoansCount' => $allLoansCount,
            'loans' => $loans,
            'books' => $books,
        ];

        return view('users.user', $data);
    }

    /**
     * Display the specified resource. Приказ улогованог корисника
     */
    public function showLoggedUser($id)
    {

        if ($id == Auth::user()->id) {
            $user = User::find($id);

            $reservedLoans = Loan::where([['user_id', '=', $id], ['active', '=', null], ['description', '=', 'rezervisano']])->get();

            $inProgressLoans = Loan::where([['user_id', '=', $id], ['active', '=', '1']])
                ->where(function ($query) {
                    $query->where('description', '=', null)->orWhere('description', '=', 'rezervisano');
                })->get();

            $returnInProgress = Loan::where('user_id', '=', $id)
                ->where(function ($query) {
                    $query->where([['description', '=', null], ['active', '=', '1']])
                        ->orWhere([['description', '=', 'potpisano'], ['active', '=', '0']]);
                })->get();

            $activeLoans = Loan::where([['user_id', '=', $id], ['active', '=', '1'], ['description', '=', 'potpisano']])->get();
            $overLoans = Loan::where([['user_id', '=', $id], ['active', '=', '0'], ['description', '=', null]])->get();

            $loans = Loan::where('user_id', $id)
                ->orderBy('created_at', 'asc')
                ->with(['user', 'book', 'library'])
                ->paginate(20);

            $overLoansCount = $overLoans->count();
            $loansNumber = $activeLoans->count();
            } else {
            abort(404);
        }

        $data = [
            'user' => $user,
            'reservedLoans' => $reservedLoans,
            'inProgressLoans' => $inProgressLoans,
            'returnInProgress' => $returnInProgress,
            'activeLoans' => $activeLoans,
            'overLoans' => $overLoans,
            'loansNumber' => $loansNumber,
            'overLoansCount' => $overLoansCount,
            'loans' => $loans,


        ];
        return view('users.loggedUser', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(?string $id = null)
    {
        $user = User::findOrFail($id ?? Auth::id());
        abort_unless($user->is(Auth::user()), 403);

        $pendingChange = ProfileChangeRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();
        $library = $user->ownLibrary;

        $roles = User::$schoolRoles;

        return view('users.edit', compact('user', 'pendingChange', 'library', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ?string $id = null)
    {
        $user = User::findOrFail($id ?? Auth::id());
        abort_unless($user->is(Auth::user()), 403);

        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'user_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'role_type' => ['nullable', Rule::in(array_keys(User::$schoolRoles))],
            'role_details' => ['nullable', 'string', 'max:255', 'required_if:role_type,Ученик,Радник школе,Друго'],
        ];
        $library = $user->ownLibrary;

        if ($library) {
            $rules['library_name'] = ['required', 'string', 'max:255'];
            $rules['library_logo'] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];
        }

        $attributes = $request->validate($rules);

        $nameChanged = $attributes['name'] !== $user->name
            || $attributes['last_name'] !== $user->last_name;
        $photoChanged = $request->hasFile('user_photo');
        $roleTypeWasProvided = isset($attributes['role_type']);
        $roleType = $roleTypeWasProvided ? $attributes['role_type'] : $user->role_type;
        $roleDetails = array_key_exists('role_details', $attributes)
            ? $attributes['role_details']
            : ($roleType !== $user->role_type ? null : $user->role_details);
        $roleChanged = $roleType !== $user->role_type || $roleDetails !== $user->role_details;
        $libraryNameChanged = $library && $attributes['library_name'] !== $library->name;
        $libraryLogoChanged = $library && $request->hasFile('library_logo');

        if (! $nameChanged && ! $photoChanged && ! $roleChanged && ! $libraryNameChanged && ! $libraryLogoChanged) {
            $message = ProfileChangeRequest::where('user_id', $user->id)
                ->where('status', 'pending')
                ->exists()
                    ? 'Ваш захтјев за промјену профила већ чека администраторско одобрење.'
                    : 'Нема промјена за слање.';

            return redirect()->route('settings.edit')->with('status', $message);
        }

        $pendingChange = ProfileChangeRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();
        $requestedPhoto = $pendingChange?->requested_photo;
        $requestedLibraryLogo = $pendingChange?->requested_library_logo;

        if ($photoChanged) {
            $requestedPhoto = $request->file('user_photo')->store('profile-change-requests', 'public');
        }

        if ($libraryLogoChanged) {
            $requestedLibraryLogo = $request->file('library_logo')->store('library-change-requests', 'public');
        }

        ProfileChangeRequest::updateOrCreate(
            ['user_id' => $user->id, 'status' => 'pending'],
            [
                'requested_name' => $nameChanged ? $attributes['name'] : $pendingChange?->requested_name,
                'requested_last_name' => $nameChanged ? $attributes['last_name'] : $pendingChange?->requested_last_name,
                'requested_photo' => $requestedPhoto,
                'requested_role_type' => $roleChanged ? $roleType : $pendingChange?->requested_role_type,
                'requested_role_details' => $roleChanged ? $roleDetails : $pendingChange?->requested_role_details,
                'library_id' => $library?->id ?? $pendingChange?->library_id,
                'requested_library_name' => $libraryNameChanged ? $attributes['library_name'] : $pendingChange?->requested_library_name,
                'requested_library_logo' => $requestedLibraryLogo,
            ]
        );

        if ($photoChanged && $pendingChange?->requested_photo) {
            Storage::disk('public')->delete($pendingChange->requested_photo);
        }

        if ($libraryLogoChanged && $pendingChange?->requested_library_logo) {
            Storage::disk('public')->delete($pendingChange->requested_library_logo);
        }

        return redirect()->route('settings.edit')->with(
            'status',
            'Захтјев је послат. Измјене профила и библиотеке биће примјењене након администраторског одобрења.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    /**
     * Display client data.
     */
}
