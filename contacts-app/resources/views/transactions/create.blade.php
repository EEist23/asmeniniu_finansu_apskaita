<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Nauja transakcija</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <form method="POST" action="{{ route('transactions.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block">Kategorija</label>
                <select name="category_id" class="w-full border p-2 rounded" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->type }})
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1">Tipas</label>
                <label class="inline-flex items-center mr-4">
                    <input type="radio" name="type" value="income" {{ old('type', 'income') == 'income' ? 'checked' : '' }}>
                    <span class="ml-2">Pajamos</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="type" value="expense" {{ old('type') == 'expense' ? 'checked' : '' }}>
                    <span class="ml-2">Išlaidos</span>
                </label>
                @error('type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block">Suma</label>
                <input type="number" step="0.01" name="amount" class="w-full border p-2 rounded" value="{{ old('amount') }}" required>
                @error('amount')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block">Data</label>
                <input type="date" name="date" class="w-full border p-2 rounded" value="{{ old('date', date('Y-m-d')) }}" required>
                @error('date')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block">Pastaba (nebūtina)</label>
                <textarea name="note" class="w-full border p-2 rounded">{{ old('note') }}</textarea>
                @error('note')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600 transition">Sukurti</button>
        </form>
    </div>
</x-app-layout>
