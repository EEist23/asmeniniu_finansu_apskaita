<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Visos transakcijos</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4">
        <a href="{{ route('transactions.create') }}" class="mb-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            + Nauja transakcija
        </a>

        @if ($transactions->isEmpty())
            <p class="text-gray-600">Transakcijų nėra.</p>
        @else
            <table class="w-full bg-white shadow rounded">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Data</th>
                        <th class="px-4 py-2 text-left">Kategorija</th>
                        <th class="px-4 py-2 text-left">Suma</th>
                        <th class="px-4 py-2 text-left">Pastaba</th>
                        <th class="px-4 py-2 text-left">Veiksmai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $transaction->date }}</td>
                            <td class="px-4 py-2">{{ $transaction->category->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ number_format($transaction->amount, 2) }} €</td>
                            <td class="px-4 py-2">{{ $transaction->note ?? '-' }}</td>
                            <td class="px-4 py-2 space-x-2">
                                <a href="{{ route('transactions.edit', $transaction) }}" class="text-blue-600 hover:underline">Redaguoti</a>
                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Ar tikrai ištrinti?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Ištrinti</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>
