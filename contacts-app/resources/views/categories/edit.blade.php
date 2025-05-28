<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Redaguoti kategoriją</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block">Pavadinimas</label>
                <input name="name" value="{{ old('name', $category->name) }}" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block">Tipas</label>
                <select name="type" class="w-full border p-2 rounded" required>
                    <option value="income" {{ old('type', $category->type) === 'income' ? 'selected' : '' }}>Pajamos</option>
                    <option value="expense" {{ old('type', $category->type) === 'expense' ? 'selected' : '' }}>Išlaidos</option>
                </select>
            </div>

            <button class="bg-green-500 text-black px-4 py-2 rounded">Atnaujinti</button>
        </form>
    </div>
</x-app-layout>
