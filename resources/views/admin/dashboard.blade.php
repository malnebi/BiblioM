<x-layout>
    <x-page-heading>Захтjеви за регистрацију</x-page-heading>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="mt-6 overflow-hidden bg-white shadow sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">корисник</th>
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
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                            Нема нових захтjева.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-page-heading>Пријдлози нових ознака</x-page-heading>
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
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $tag->name }}</td>
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
