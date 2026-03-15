<x-layout>
    <h1 class="text-2xl font-bold mb-4">Корисници који чекају одобрење</h1>

    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="py-2 px-4 border">Име</th>
                <th class="py-2 px-4 border">Е-маил</th>
                <th class="py-2 px-4 border">Акција</th>
            </tr>
        </thead>
{{-- 
<tbody>
    @foreach($pendingUsers as $user)
                <tr>
                    <td class="py-2 px-4 border">{{ $user->name }}</td>
                    <td class="py-2 px-4 border">{{ $user->email }}</td>
                    <td class="py-2 px-4 border">
                        <form method="POST" action="/admin/users/{{ $user->id }}/approve">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-green-500 text-white px-4 py-1 rounded">
                                Одобри
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        --}}
</x-layout>