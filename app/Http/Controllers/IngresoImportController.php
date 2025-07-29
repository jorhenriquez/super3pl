<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use App\Models\Product;
use App\Models\LineaIngreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Imports\LineasIngresoDesdeExcelImport;

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
            $path = $file->store('temp', 'local');
            $fullPath = Storage::disk('local')->path($path);

            if (!Storage::disk('local')->exists($path)) {
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

            return view('ingresos.preview', compact('data', 'path'));
        } else {
            return redirect()->route('ingresos.index')->with('error', 'No existe archivo.');
        }
    }

    public function processExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|string',
            'sheet' => 'required|integer',
            'map' => 'required|array',
        ]);

        // Ruta al archivo en storage/app/temp
        $filePath = Storage::disk('local')->path($request->input('file'));

        if (!file_exists($filePath)) {
            return back()->with('error', 'No se encontró el archivo a procesar.');
        }

        $sheetIndex = (int) $request->input('sheet');
        $map = $request->input('map');

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getSheet($sheetIndex);
        $rows = $sheet->toArray();

        $importData = [];
        $ingreso = null;

        foreach (array_slice($rows, 1) as $row) {
            $referencia = $row[$map['referencia']] ?? null;
            $codigo = $row[$map['codigo']] ?? null;
            $cantidad = isset($map['cantidad']) && isset($row[$map['cantidad']])
                ? (int) $row[$map['cantidad']]
                : 0;

            if ($referencia && $codigo) {

                // Crear el Ingreso solo en la primera iteración
                if (is_null($ingreso)) {
                    $ingreso = Ingreso::create([
                        'referencia' => $referencia,  // usamos la primera referencia válida
                        'user_id' => null,
                        'estado_pedido_id' => 1, // estado por defecto
                    ]);
                }

                // Buscar el producto
                $product = Product::where('codigo', $codigo)->first();

                if ($product) {
                    $importData[] = [
                        'ingreso_id' => $ingreso->id,
                        'product_id' => $product->id,
                        'cantidad_total' => $cantidad,
                    ];
                }
            }
        }

        // Guardar las líneas si existen
        if (!empty($importData)) {
            LineaIngreso::insert($importData);
        }


        // Opcional: eliminar el archivo temporal
        Storage::disk('local')->delete($request->input('file'));

        return redirect()->route('ingresos.index')->with('status', 'Importación completada');
    }


}
