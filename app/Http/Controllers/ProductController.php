<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('descripcion', 'like', "%{$search}%")
                ->orWhere('codigo', 'like', "%{$search}%")
                ->orWhere('ean', 'like', "%{$search}%");
            });
        }

        // Ordenar para que los productos sin EAN aparezcan primero
        $query->orderByRaw('CASE WHEN ean IS NULL OR ean = "" THEN 0 ELSE 1 END');

        $productos = $query->paginate(10)->withQueryString();

        return view('products.index', compact('productos'));
    }


    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'ean' => 'nullable|string|max:50',
            'codigo' => 'nullable|string|max:50',
            'peso' => 'nullable|numeric',
            'volumen' => 'nullable|numeric',
            'cantidad_palet' => 'nullable|integer',
        ]);

        Product::create($request->only(['descripcion', 'ean', 'codigo','peso', 'volumen', 'cantidad_palet']));

        return redirect()->route('products.create')->with('success', 'Producto creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $producto = Product::findOrFail($id);
        return view('products.edit', compact('producto'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'codigo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'ean' => 'nullable|string|max:255',
            'peso' => 'nullable|numeric',
            'volumen' => 'nullable|numeric',
            'cantidad_palet' => 'nullable|integer',
        ]);

        $producto = Product::findOrFail($id);
        $producto->codigo = $request->codigo;
        $producto->descripcion = $request->descripcion;
        $producto->ean = $request->ean;
        $producto->peso = $request->peso;
        $producto->volumen = $request->volumen;
        $producto->cantidad_palet = $request->cantidad_palet; 
        $producto->save();

        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Producto eliminado correctamente');
    }
}
