<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF; // pridėta PDF generavimui

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Gauti pasirinktus metus ir mėnesį, arba naudoti dabartinius
        $selectedYear = $request->input('year', now()->year);
        $selectedMonth = $request->input('month', now()->month);

        // Nustatyti mėnesio pradžią ir pabaigą
        $startDate = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate = (clone $startDate)->endOfMonth();

        // Gauti pasirinkto mėnesio transakcijas
        $transactions = Transaction::where('user_id', $userId)
            ->with('category')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderByDesc('date')
            ->get();

        // Pajamos, išlaidos, balansas
        $totalIncomeThisMonth = $transactions->where('type', 'income')->sum('amount');
        $totalExpensesThisMonth = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncomeThisMonth - $totalExpensesThisMonth;

        // Galimi metai pasirinkimui (pvz., paskutinių 5 metų intervalas)
        $years = range(now()->year - 100, now()->year + 5);

        // Mėnesių sąrašas
        $months = [
            1 => 'Sausis', 2 => 'Vasaris', 3 => 'Kovas',
            4 => 'Balandis', 5 => 'Gegužė', 6 => 'Birželis',
            7 => 'Liepa', 8 => 'Rugpjūtis', 9 => 'Rugsėjis',
            10 => 'Spalis', 11 => 'Lapkritis', 12 => 'Gruodis'
        ];

        return view('transactions.index', compact(
            'transactions',
            'totalIncomeThisMonth',
            'totalExpensesThisMonth',
            'balance',
            'selectedYear',
            'selectedMonth',
            'years',
            'months'
        ));
    }

    public function exportPdf(Request $request)
    {
        $userId = Auth::id();

        // Gauti pasirinktus metus ir mėnesį, arba naudoti dabartinius
        $selectedYear = $request->input('year', now()->year);
        $selectedMonth = $request->input('month', now()->month);

        // Nustatyti mėnesio pradžią ir pabaigą
        $startDate = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate = (clone $startDate)->endOfMonth();

        // Gauti pasirinkto mėnesio transakcijas
        $transactions = Transaction::where('user_id', $userId)
            ->with('category')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderByDesc('date')
            ->get();

        // Pajamos, išlaidos, balansas
        $totalIncomeThisMonth = $transactions->where('type', 'income')->sum('amount');
        $totalExpensesThisMonth = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncomeThisMonth - $totalExpensesThisMonth;

        // Sukuriame PDF iš view
        $pdf = PDF::loadView('transactions.pdf', [
            'transactions' => $transactions,
            'totalIncomeThisMonth' => $totalIncomeThisMonth,
            'totalExpensesThisMonth' => $totalExpensesThisMonth,
            'balance' => $balance,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
        ]);

        // Atsisiųsti PDF failą
        return $pdf->download("transactions_{$selectedYear}_{$selectedMonth}.pdf");
    }

    public function create()
    {
        $userId = Auth::id();

        $categories = Category::where(function ($query) use ($userId) {
            $query->whereNull('user_id')
                ->orWhere('user_id', $userId);
        })->get();

        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense',
            'date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        Transaction::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'date' => $request->date,
            'note' => $request->note,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transakcija sėkmingai sukurta.');
    }

    public function show(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);
        return view('transactions.show', compact('transaction'));
    }
   public function sendEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'year' => 'nullable|integer',
        'month' => 'nullable|integer',
    ]);

    $userId = Auth::id();

    $selectedYear = $request->input('year', now()->year);
    $selectedMonth = $request->input('month', now()->month);
    $email = $request->input('email');

    $startDate = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
    $endDate = (clone $startDate)->endOfMonth();

    $transactions = Transaction::where('user_id', $userId)
        ->with('category')
        ->whereBetween('date', [$startDate, $endDate])
        ->orderByDesc('date')
        ->get();

    $totalIncomeThisMonth = $transactions->where('type', 'income')->sum('amount');
    $totalExpensesThisMonth = $transactions->where('type', 'expense')->sum('amount');
    $balance = $totalIncomeThisMonth - $totalExpensesThisMonth;

    $pdf = PDF::loadView('transactions.pdf', [
        'transactions' => $transactions,
        'totalIncomeThisMonth' => $totalIncomeThisMonth,
        'totalExpensesThisMonth' => $totalExpensesThisMonth,
        'balance' => $balance,
        'selectedYear' => $selectedYear,
        'selectedMonth' => $selectedMonth,
    ]);

    \Mail::send([], [], function ($message) use ($email, $pdf, $selectedYear, $selectedMonth) {
        $message->to($email)
            ->subject("Transakcijų ataskaita už {$selectedYear}-{$selectedMonth}")
            ->attachData($pdf->output(), "transactions_{$selectedYear}_{$selectedMonth}.pdf")
            ->text('Pridedamas jūsų prašytas transakcijų PDF ataskaita.');
    });

    return redirect()->back()->with('success', 'PDF ataskaita išsiųsta į ' . $email);
}

    public function edit(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);
        $categories = Category::where('user_id', Auth::id())->get();
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense',
            'date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $transaction->update($request->only(['category_id', 'amount', 'type', 'date', 'note']));

        return redirect()->route('transactions.index')->with('success', 'Transakcija atnaujinta.');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transakcija ištrinta.');
    }

    private function authorizeTransaction(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Neturite prieigos prie šios transakcijos.');
        }
    }
}
