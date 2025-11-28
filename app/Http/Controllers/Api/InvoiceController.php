<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class InvoiceController extends Controller
{
  /**
   * Create MPDF instance with Arabic font support.
   */
  private function createMpdf(): Mpdf
  {
    $defaultConfig = (new ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];

    $defaultFontConfig = (new FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    // Add custom Arabic fonts
    $customFontDir = storage_path('fonts');

    $mpdf = new Mpdf([
      'mode' => 'utf-8',
      'format' => 'A4',
      'default_font_size' => 12,
      'default_font' => 'xbriyaz',
      'margin_left' => 15,
      'margin_right' => 15,
      'margin_top' => 16,
      'margin_bottom' => 16,
      'fontDir' => array_merge($fontDirs, [$customFontDir]),
      'fontdata' => $fontData + [
        'amiri' => [
          'R' => 'Amiri-Regular.ttf',
          'useOTL' => 0xFF,
          'useKashida' => 75,
        ],
        'notonaskharabic' => [
          'R' => 'NotoNaskhArabic-Regular.ttf',
          'useOTL' => 0xFF,
          'useKashida' => 75,
        ],
      ],
      'tempDir' => storage_path('app/mpdf-temp'),
      'autoArabic' => true,
      'autoLangToFont' => true,
    ]);

    $mpdf->SetDirectionality('rtl');

    return $mpdf;
  }

  /**
   * Download invoice PDF for an order (admin only).
   */
  public function download(int $id): Response
  {
    $order = Order::with(['items', 'customer'])->findOrFail($id);

    // Generate HTML
    $html = View::make('invoices.order-mpdf', compact('order'))->render();

    // Create PDF with mpdf
    $mpdf = $this->createMpdf();
    $mpdf->WriteHTML($html);

    $filename = "invoice-{$order->invoice_number}.pdf";

    return response($mpdf->Output($filename, 'S'), 200, [
      'Content-Type' => 'application/pdf',
      'Content-Disposition' => "attachment; filename=\"{$filename}\"",
    ]);
  }

  /**
   * Download invoice by invoice number (public).
   */
  public function downloadByInvoiceNumber(string $invoiceNumber): Response
  {
    $order = Order::with(['items', 'customer'])
      ->where('invoice_number', $invoiceNumber)
      ->firstOrFail();

    // Generate HTML
    $html = View::make('invoices.order-mpdf', compact('order'))->render();

    // Create PDF with mpdf
    $mpdf = $this->createMpdf();
    $mpdf->WriteHTML($html);

    $filename = "invoice-{$order->invoice_number}.pdf";

    return response($mpdf->Output($filename, 'S'), 200, [
      'Content-Type' => 'application/pdf',
      'Content-Disposition' => "attachment; filename=\"{$filename}\"",
    ]);
  }
}
