<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendInvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Invoice;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SendInvoiceEmail implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pdfPath = 'public/invoices/invoice_' . $this->invoice->id . '.pdf';

        try {
            $pdf = Pdf::loadView('pdf.invoice', ['invoice' => $this->invoice]);
            Storage::put($pdfPath, $pdf->output());

            Mail::to($this->invoice->email)
                ->send(new SendInvoiceMail($this->invoice, storage_path('app/' . $pdfPath)));

            $this->invoice->update(['status' => 'Sent']);
        } catch (Exception $e) {
            Log::error("Failed to send invoice email: " . $e->getMessage());

            $this->invoice->update(['status' => 'Failed']);
        } finally {
            Storage::delete($pdfPath);
        }
    }
}
