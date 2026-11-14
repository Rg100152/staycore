<?php
namespace StayCore;

use Dompdf\Dompdf;
use Dompdf\Options;

class InvoicePDF {
    public function generate($invoiceData, $charges, $guest, $room, $outputPath = null) {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $html = $this->buildHtml($invoiceData, $charges, $guest, $room);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        if ($outputPath) {
            file_put_contents($outputPath, $dompdf->output());
            return ['success' => true, 'path' => $outputPath];
        }

        return ['success' => true, 'pdf' => $dompdf->output()];
    }

    private function buildHtml($inv, $charges, $guest, $room) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                body { font-family: "DejaVu Sans", sans-serif; background: #f8fafc; color: #1e293b; padding: 40px; }
                .invoice { max-width: 800px; margin: auto; background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.1); }
                .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; }
                .logo { width: 48px; height: 48px; background: #dc2626; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: bold; font-size: 20px; }
                .brand { font-size: 24px; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; }
                .brand span { color: #dc2626; }
                .inv-details { text-align: right; }
                .inv-number { font-size: 20px; font-weight: 700; color: #dc2626; }
                .inv-date { color: #64748b; font-size: 14px; }
                .address-block { display: flex; justify-content: space-between; margin-bottom: 32px; }
                .addr { flex: 1; }
                .addr h4 { text-transform: uppercase; font-size: 11px; letter-spacing: 1px; color: #94a3b8; margin-bottom: 8px; }
                .addr p { margin: 4px 0; font-size: 14px; color: #334155; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 32px; }
                th { text-align: left; padding: 12px 8px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; border-bottom: 1px solid #e2e8f0; }
                td { padding: 14px 8px; font-size: 14px; color: #334155; border-bottom: 1px solid #f1f5f9; }
                td.amount { text-align: right; }
                td.qty { text-align: center; width: 80px; }
                .total-row { font-weight: 700; font-size: 16px; color: #0f172a; border-top: 2px solid #e2e8f0; }
                .total { color: #dc2626; }
                .footer { margin-top: 48px; padding-top: 24px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 12px; color: #94a3b8; }
                .badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
                .badge-paid { background: #dcfce7; color: #166534; }
                .badge-pending { background: #fef3c7; color: #92400e; }
            </style>
        </head>
        <body>
            <div class="invoice">
                <div class="header">
                    <div class="logo">S</div>
                    <div>
                        <div class="brand">Stay<span>Core</span> Hotel</div>
                        <div style="color:#64748b; font-size:13px;">Luxury Hospitality Management</div>
                    </div>
                    <div class="inv-details">
                        <div class="inv-number">Invoice #' . htmlspecialchars($inv['id']) . '</div>
                        <div class="inv-date">' . date('M d, Y', strtotime($inv['created_at'])) . '</div>
                    </div>
                </div>

                <div class="address-block">
                    <div class="addr">
                        <h4>Bill To</h4>
                        <p>' . htmlspecialchars($guest['name']) . '</p>
                        <p>' . htmlspecialchars($guest['email']) . '</p>
                    </div>
                    <div class="addr" style="text-align:right">
                        <h4>Room</h4>
                        <p>' . htmlspecialchars($room['room_number']) . ' — ' . htmlspecialchars($room['type']) . '</p>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="qty">Qty</th>
                            <th class="amount">Amount (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        ' . $this->buildChargeRows($charges) . '
                        <tr class="total-row">
                            <td>Tourist Tax (12%)</td>
                            <td class="qty">1</td>
                            <td class="amount">₹' . number_format($inv['tax_amount'], 2) . '</td>
                        </tr>
                    </tbody>
                </table>

                <div style="display:flex; justify-content:flex-end;">
                    <div style="text-align:right;">
                        <div style="color:#64748b; font-size:13px;">Total Paid</div>
                        <div style="font-size:24px; font-weight:800; color:#dc2626;">₹' . number_format($inv['total_amount'], 2) . '</div>
                    </div>
                </div>

                <div class="footer">
                    StayCore Hotel Management System — Odoo-Style Suite &copy; 2026<br>
                    This is a computer-generated invoice.
                </div>
            </div>
        </body>
        </html>';
    }

    private function buildChargeRows($charges) {
        $rows = '';
        foreach ($charges as $c) {
            $rows .= '<tr>
                <td>' . htmlspecialchars($c['label'] ?? 'Item') . '</td>
                <td class="qty">' . ($c['qty'] ?? 1) . '</td>
                <td class="amount">₹' . number_format($c['amount'] ?? 0, 2) . '</td>
            </tr>';
        }
        return $rows;
    }
}