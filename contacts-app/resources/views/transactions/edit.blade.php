<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Redaguoti transakciją</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <form method="POST" action="{{ route('transactions.update', $transaction) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block">Kategorija</label>
                <select name="category_id" class="w-full border p-2 rounded" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $transaction->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->type }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block">Suma</label>
                <input type="number" step="0.01" name="amount" value="{{ old('amount', $transaction->amount) }}" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block">Data</label>
                <input type="date" name="date" value="{{ old('date', $transaction->date) }}" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block">Pastaba (nebūtina)</label>
                <textarea name="note" class="w-full border p-2 rounded">{{ old('note', $transaction->note) }}</textarea>
            </div>

            <button class="bg-green-500 text-black px-4 py-2 rounded">Atnaujinti</button>
        </form>
    </div>
</x-app-layout>
