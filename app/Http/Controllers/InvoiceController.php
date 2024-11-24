<?php

namespace App\Http\Controllers;

use App\Jobs\SendInvoiceEmail;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function showInvoice() {
        $invoice = Invoice::all();

        return Inertia::render('Invoice', [
            'invoice' => $invoice
        ]);
    }

    public function createInvoice(Request $request) {
        $fields = $request->validate([
            'client_name' => ['required', 'max:255'],
            'email' => ['required', 'email'],
            'bill' => ['required', 'max:255'],
        ]);

        Invoice::create($fields);

        return redirect()->route('invoice')->with('success', 'Invoice created successfully!');
    }

    public function downloadPDF($id) {
        $invoice = Invoice::findOrFail($id);

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice
        ]);

        return $pdf->download('invoice' . $invoice->id . '.pdf');
    }

    public function sendInvoice($id) {
        $invoice = Invoice::findOrFail($id);
        SendInvoiceEmail::dispatch($invoice)
            ->delay(now()->addSeconds(30));

        return redirect()->back()->with('succces', 'Invoice will be sent shortly.');
    }

    public function editInvoice(Request $request, $id) {
        $request->validate([
            'client_name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'bill' => ['required', 'max:255'],
        ]);

        $invoice = Invoice::findOrFail($id);

        $invoice->update([
            'client_name' => $request->input('client_name'),
            'email' => $request->input('email'),
            'bill' => $request->input('bill'),
        ]);

        return redirect()->route('invoice')->with('success', "Invoice updated successfully!");
    }

    public function deleteInvoice($id) {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('invoice')->with('success', 'Invoice deleted successfully!');
    }
}
