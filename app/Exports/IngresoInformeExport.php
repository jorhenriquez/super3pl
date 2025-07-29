<?php

namespace App\Exports;

use App\Models\Ingreso;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;

class IngresoInformeExport implements FromView, WithStyles, ShouldAutoSize
{
    protected $ingreso;

    public function __construct(Ingreso $ingreso)
    {
        $this->ingreso = $ingreso;
    }

    public function view(): View
    {
        $lineas = $this->ingreso->lineas->map(function($linea) {
            return [
                'codigo' => $linea->product->codigo,
                'descripcion' => $linea->product->descripcion,
                'cantidad_total' => $linea->cantidad_total,
                'cantidad_valida' => $linea->cantidad_valida,
                'faltante' => max(0, $linea->cantidad_total - $linea->cantidad_valida),
            ];
        });

        return view('exports.ingreso_informe', [
            'ingreso' => $this->ingreso,
            'lineas' => $lineas,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Cabeceras en negrita y color gris claro
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD'] // Azul grisáceo
            ],
        ]);

        // Bordes en toda la tabla
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:E{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Autoajuste de altura de las filas
        foreach (range(1, $lastRow) as $row) {
            $sheet->getRowDimension($row)->setRowHeight(-1);
        }

        return [];
    }
}
