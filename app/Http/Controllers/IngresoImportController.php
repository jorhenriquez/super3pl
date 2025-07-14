<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IngresoImportController extends Controller
{
    public function show()
    {
        return view('ingresos.import');
    }

    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        // Handle the file import logic here
        // For example, you can use a service or a job to process the file

        // Redirect back with success message
        return redirect()->route('ingresos.index')->with('success', 'File imported successfully.');
    }
}
