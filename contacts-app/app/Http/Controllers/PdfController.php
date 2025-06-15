<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use PDF;

class PDFController extends Controller
{
    public function generate()
    {
        $transactions = Transaction::where('user_id', auth()->id())->with('category')->get();

        $pdf = PDF::loadView('pdf.transactions', compact('transactions'));
        return $pdf->download('finansu-ataskaita.pdf');
    }
}
