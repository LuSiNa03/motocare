<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);

        // Pastikan invoice ini milik user yang sedang login
        $service = $invoice->service;
        if ($service->booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('customer.invoice', compact('invoice'));
    }

    public function pay($id)
    {
        $invoice = Invoice::findOrFail($id);

        $service = $invoice->service;
        if ($service->booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Simulasi pembayaran
        $invoice->status = 'lunas';
        $invoice->payment_method = 'Simulasi Transfer / E-Wallet';
        $invoice->save();

        return redirect()->route('dashboard')->with('success', 'Pembayaran Invoice ' . $invoice->invoice_number . ' berhasil! Anda mendapatkan MotoPoints.');
    }

    public function submitReview(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $service = $invoice->service;

        if ($service->booking->user_id !== Auth::id() || $invoice->status !== 'lunas') {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $service->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
