<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Transakcijos peržiūra</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto bg-white p-6 shadow rounded">
        <p><strong>Kategorija:</strong> {{ $transaction->category->name }} ({{ $transaction->category->type }})</p>
        <p><strong>Suma:</strong> {{ number_format($transaction->amount, 2) }} €</p>
        <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}</p>
        <p><strong>Pastaba:</strong> {{ $transaction->note ?? 'Nėra' }}</p>
        <p><strong>Sukurta:</strong> {{ $transaction->created_at->format('Y-m-d H:i') }}</p>

        <div class="mt-4">
            <a href="{{ route('transactions.edit', $transaction) }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded mr-2">Redaguoti</a>

            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline-block" onsubmit="return confirm('Ar tikrai ištrinti?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Ištrinti</button>
            </form>
        </div>
    </div>
</x-app-layout>
