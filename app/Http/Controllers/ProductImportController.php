<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;

class ProductImportController extends Controller
{
    public function show()
    {
        return view('products.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        Excel::import(new ProductsImport, $request->file('file'));

        return redirect()->route('products.index')->with('success', 'Productos importados correctamente.');
    }
}

?>
