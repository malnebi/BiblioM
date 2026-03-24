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
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $user->ownlibrary->name ?? 'Није унесено' }}
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
</x-layout>
