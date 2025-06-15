<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Transakcijų ataskaita {{ $selectedYear }} m. {{ $selectedMonth }} mėn.</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px;}
        th, td { border: 1px solid #000; padding: 6px; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Asmeninių finansų apskaita</h2>
    <h3>Transakcijos {{ $selectedYear }} m. {{ $selectedMonth }} mėn.</h3>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Kategorija</th>
                <th>Tipas</th>
                <th>Suma</th>
                <th>Pastabos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}</td>
                    <td>{{ $transaction->category ? $transaction->category->name : '-' }}</td>
                    <td>{{ ucfirst($transaction->type) }}</td>
                    <td>{{ number_format($transaction->amount, 2) }} €</td>
                    <td>{{ $transaction->note ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
