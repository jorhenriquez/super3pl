<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LineasIngresoDesdeExcelImport;
use PhpOffice\PhpSpreadsheet\IOFactory;

class IngresoImportController extends Controller
{
    public function show()
    {
        return view('ingresos.import');
    }

    public function import(Request $request)
    {

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        $lineasFile = $request->file('file');
        
        if (file_exists($lineasFile)) {
            Excel::import(new LineasIngresoDesdeExcelImport, $lineasFile);
            return redirect()->route('ingresos.index')->with('success', 'Ingresos importados correctamente.');
        } else {
            return redirect()->route('ingresos.index')->with('error', 'No existe archivo.');
        }
    }

    public function previewExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');

        if ($file) {
            // Guardar temporalmente el archivo
            $path = $file->store('temp'); // Ejemplo: temp/pRsa2RckQphygDxC.xlsx
            $fullPath = storage_path('app/' . $path); // Ruta absoluta

            if (!file_exists($fullPath)) {
                return redirect()->route('ingresos.index')->with('error', 'No existe archivo.');
            }

            // Leer las hojas y encabezados
            $spreadsheet = IOFactory::load($fullPath);

            $data = [];
            foreach ($spreadsheet->getWorksheetIterator() as $index => $sheet) {
                $highestColumn = $sheet->getHighestColumn();
                $headerRow = $sheet->rangeToArray('A1:' . $highestColumn . '1')[0];

                $data[] = [
                    'sheet_name' => $sheet->getTitle(),
                    'columns' => $headerRow,
                ];
            }

            return view('import.preview', compact('data', 'path'));
        } else {
            return redirect()->route('ingresos.index')->with('error', 'No existe archivo.');
        }
    }

    public function processExcel(Request $request)
    {
        $file = storage_path('app/' . $request->input('file'));
        $sheetIndex = $request->input('sheet');
        $map = $request->input('map');

        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getSheet($sheetIndex);
        $rows = $sheet->toArray();

        $importData = [];
        foreach (array_slice($rows, 1) as $row) {
            $importData[] = [
                'referencia' => $row[$map['referencia']],
                'codigo' => $row[$map['codigo']],
                'cantidad' => $row[$map['cantidad']],
            ];
        }

        // Aquí guardas en tu modelo
        // Ingreso::insert($importData);

        return back()->with('status', 'Importación completada');
    }

}
