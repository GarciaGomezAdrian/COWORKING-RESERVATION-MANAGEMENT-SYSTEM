<?php
function generarFacturaPDF($reserva, $guardarEnArchivo = false, $filePath = '') {
    require_once __DIR__ . '/../libs/fpdf/fpdf.php';
    require_once __DIR__ . '/../models/Cupon.php';
    global $pdo;

    $dias = (strtotime($reserva['fecha_fin']) - strtotime($reserva['fecha_inicio'])) / 86400 + 1;
    $total = $reserva['precio_dia'] * $dias;

    $pdf = new FPDF();
    $pdf->AddPage();

    function utf8($text) {
        return iconv('UTF-8', 'windows-1252//TRANSLIT', $text);
    }

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, utf8('Factura de Reserva'), 0, 1, 'C');

    $pdf->SetFont('Arial', '', 12);
    $pdf->Ln(5);
    $pdf->Cell(0, 10, utf8('Fecha de emisión: ' . date('d/m/Y')), 0, 1);

    $pdf->Ln(5);
    $pdf->Cell(0, 10, utf8('Cliente: ' . $_SESSION['usuario']['nombre_completo']), 0, 1);
    $pdf->Cell(0, 10, utf8('Email: ' . $_SESSION['usuario']['email']), 0, 1);

    $pdf->Ln(5);
    $pdf->Cell(0, 10, utf8('Espacio: ' . $reserva['nombre']), 0, 1);
    $pdf->Cell(0, 10, utf8('Ubicación: ' . $reserva['ubicacion']), 0, 1);
    $pdf->Cell(0, 10, utf8('Fecha inicio: ' . $reserva['fecha_inicio']), 0, 1);
    $pdf->Cell(0, 10, utf8('Fecha fin: ' . $reserva['fecha_fin']), 0, 1);
    $pdf->Cell(0, 10, utf8('Duración: ' . $dias . ' día(s)'), 0, 1);
    $pdf->Cell(0, 10, utf8('Precio por día: ' . number_format($reserva['precio_dia'], 2) . ' €'), 0, 1);

    $pdf->Ln(5);

    $cuponModel = new Cupon($pdo);
    $cupon = $cuponModel->obtenerUltimoCuponValido($_SESSION['usuario']['id']);

    if ($cupon) {
        $descuento = $total * ($cupon['descuento'] / 100);
        $total_descuento = $total - $descuento;

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Resumen del Cupón:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, utf8('Código: ' . $cupon['codigo']), 0, 1);
        $pdf->Cell(0, 10, utf8('Descuento: ' . $cupon['descuento'] . '%'), 0, 1);
        $pdf->Cell(0, 10, utf8('Descuento aplicado: -' . number_format($descuento, 2) . ' €'), 0, 1);

        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, utf8('Total con descuento: ' . number_format($total_descuento, 2) . ' €'), 0, 1);
    } else {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, utf8('Total: ' . number_format($total, 2) . ' €'), 0, 1);
    }

    if ($guardarEnArchivo && $filePath) {
        $pdf->Output('F', $filePath);
    } else {
        $pdf->Output('D', 'Factura_Reserva_' . $reserva['id'] . '.pdf');
    }
}
?>
