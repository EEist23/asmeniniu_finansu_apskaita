<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kategorijos</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <a href="{{ route('categories.create') }}" class="mb-4 inline-block bg-blue-500 text-black px-4 py-2 rounded">+ Nauja kategorija</a>

        <table class="table-auto w-full bg-white shadow rounded">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">Pavadinimas</th>
                    <th class="px-4 py-2">Tipas</th>
                    <th class="px-4 py-2">Veiksmai</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                <tr>
                    <td class="border px-4 py-2">{{ $category->name }}</td>
                    <td class="border px-4 py-2">{{ ucfirst($category->type) }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('categories.edit', $category) }}" class="text-blue-600">Redaguoti</a> |
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Ar tikrai ištrinti?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600">Ištrinti</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
