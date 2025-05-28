<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Nauja kategorija</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block">Pavadinimas</label>
                <input name="name" value="{{ old('name') }}" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block">Tipas</label>
                <select name="type" class="w-full border p-2 rounded" required>
                    <option value="income" {{ old('type') === 'income' ? 'selected' : '' }}>Pajamos</option>
                    <option value="expense" {{ old('type') === 'expense' ? 'selected' : '' }}>Išlaidos</option>
                </select>
            </div>

            <button class="bg-blue-500 text-white px-4 py-2 rounded">Sukurti</button>
        </form>
    </div>
</x-app-layout>
