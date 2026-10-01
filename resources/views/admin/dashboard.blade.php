<x-layout>
    <x-page-heading>Захтjеви за регистрацију</x-page-heading>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-800">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="mt-6 overflow-hidden bg-white shadow sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">корисник</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Улога</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Библиотека</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Опције</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pendingUsers as $user)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if ($user->user_photo)
                                    <a href="{{ asset('storage/' . $user->user_photo) }}" target="_blank"
                                        rel="noopener" title="Прикажи слику корисника">
                                        <img src="{{ asset('storage/' . $user->user_photo) }}"
                                            alt="Слика корисника {{ $user->name }}"
                                            class="h-12 w-12 rounded-full object-cover border border-gray-200">
                                    </a>
                                @else
                                    <img src="{{ Vite::asset('resources/images/user-placeholder.svg') }}" alt="Подразумијевана слика"
                                        class="h-12 w-12 rounded-full object-cover border border-gray-200">
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="space-y-2">
                                @csrf
                                @method('PATCH')
                                <select name="role_type" class="w-full rounded border-gray-300 text-sm" aria-label="Улога корисника">
                                    @foreach (array_keys(\App\Models\User::$schoolRoles) as $role)
                                        <option value="{{ $role }}" @selected($user->role_type === $role)>{{ $role }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="role_details" value="{{ $user->role_details }}"
                                    class="w-full rounded border-gray-300 text-sm" maxlength="255"
                                    placeholder="Детаљи или опис улоге" aria-label="Детаљи или опис улоге">
                                <button class="rounded bg-blue-600 px-3 py-1 text-sm font-semibold text-white hover:bg-blue-700">
                                    Сачувај улогу
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if ($user->ownLibrary?->logo)
                                <div class="flex items-center gap-3">
                                    <a href="{{ asset('storage/' . $user->ownLibrary->logo) }}" target="_blank"
                                        rel="noopener" title="Прикажи лого библиотеке">
                                        <img src="{{ asset('storage/' . $user->ownLibrary->logo) }}"
                                            alt="Лого библиотеке {{ $user->ownLibrary->name }}"
                                            class="h-12 w-12 rounded-md object-cover border border-gray-200">
                                    </a>
                                    <span>{{ $user->ownLibrary->name }}</span>
                                </div>
                            @else
                                <div class="flex items-center gap-3">
                                    <img src="{{ Vite::asset('resources/images/library-placeholder.svg') }}" alt="Подразумијевани лого"
                                        class="h-12 w-12 rounded-md object-cover border border-gray-200">
                                    <span>{{ $user->ownLibrary->name ?? 'Није унесено' }}</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <form method="POST" action="/admin/users/{{ $user->id }}/approve">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">
                                        Одобри
                                    </button>
                                </form>

                                <form method="POST" action="/admin/users/{{ $user->id }}/reject">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                        Одбиј
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Нема нових захтjева.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-page-heading>Захтјеви за измјену профила</x-page-heading>
    <div class="mt-6 overflow-hidden bg-white shadow sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Корисник</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Тренутни подаци</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Предложене измјене</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase text-gray-500">Опције</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse ($pendingProfileChanges as $change)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $change->user?->email ?? 'Кориснички налог је уклоњен' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $change->user?->user_photo ? asset('storage/' . $change->user->user_photo) : Vite::asset('resources/images/user-placeholder.svg') }}"
                                        alt="Тренутна фотографија" class="h-12 w-12 rounded-full border border-gray-200 object-cover">
                                    <div>
                                        <p class="text-sm text-gray-800">{{ $change->user?->name }} {{ $change->user?->last_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $change->user?->role_type ?? 'Улога није наведена' }}{{ $change->user?->role_details ? ' · ' . $change->user->role_details : '' }}</p>
                                    </div>
                                </div>
                                @if ($change->library)
                                    <div class="flex items-center gap-3 border-t border-gray-100 pt-3">
                                        <img src="{{ $change->library->logo ? asset('storage/' . $change->library->logo) : Vite::asset('resources/images/library-placeholder.svg') }}"
                                            alt="Тренутни логотип библиотеке" class="h-10 w-10 rounded-md border border-gray-200 object-cover">
                                        <span class="text-sm text-gray-700">{{ $change->library->name }}</span>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    @if ($change->requested_photo)
                                        <a href="{{ asset('storage/' . $change->requested_photo) }}" target="_blank" rel="noopener">
                                            <img src="{{ asset('storage/' . $change->requested_photo) }}" alt="Предложена фотографија"
                                                class="h-12 w-12 rounded-full border border-blue-300 object-cover">
                                        </a>
                                    @endif
                                    <span class="text-sm text-gray-800">
                                        {{ $change->requested_name ?? $change->user?->name }}
                                        {{ $change->requested_last_name ?? $change->user?->last_name }}
                                    </span>
                                </div>
                                @if ($change->requested_role_type !== null)
                                    <p class="border-t border-gray-100 pt-3 text-sm text-gray-700">
                                        Улога: {{ $change->requested_role_type }}
                                        @if ($change->requested_role_details)
                                            <span class="text-gray-500">· {{ $change->requested_role_details }}</span>
                                        @endif
                                    </p>
                                @endif
                                @if ($change->library)
                                    <div class="flex items-center gap-3 border-t border-gray-100 pt-3">
                                        @if ($change->requested_library_logo)
                                            <a href="{{ asset('storage/' . $change->requested_library_logo) }}" target="_blank" rel="noopener">
                                                <img src="{{ asset('storage/' . $change->requested_library_logo) }}" alt="Предложени логотип библиотеке"
                                                    class="h-10 w-10 rounded-md border border-blue-300 object-cover">
                                            </a>
                                        @endif
                                        <span class="text-sm text-gray-700">
                                            {{ $change->requested_library_name ?? $change->library->name }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <form method="POST" action="{{ route('admin.profile-changes.approve', $change) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="rounded bg-green-500 px-3 py-1 text-sm text-white hover:bg-green-600">Одобри</button>
                                </form>
                                <form method="POST" action="{{ route('admin.profile-changes.reject', $change) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded bg-red-500 px-3 py-1 text-sm text-white hover:bg-red-600">Одбиј</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Нема захтјева за измјену профила.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-page-heading>Приједлози нових ознака за књиге</x-page-heading>
    <div class="mt-6 overflow-hidden bg-white shadow sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ознака</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Књига</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Предложио</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Опције</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pendingTags as $tag)
                    <tr>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.tags.update-name', $tag) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="name" value="{{ $tag->name }}"
                                    class="w-full rounded border-gray-300 text-sm" maxlength="255"
                                    aria-label="Назив предложене ознаке">
                                <button class="rounded bg-blue-600 px-3 py-1 text-sm font-semibold text-white hover:bg-blue-700">
                                    Сачувај
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $tag->suggestedBook?->title ?? 'Књига је уклоњена' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $tag->suggestedBy?->name ?? 'Непознат корисник' }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <form method="POST" action="{{ route('admin.tags.approve', $tag) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">Одобри</button>
                                </form>
                                <form method="POST" action="{{ route('admin.tags.reject', $tag) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Одбиј</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Нема нових предлога ознака.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>
