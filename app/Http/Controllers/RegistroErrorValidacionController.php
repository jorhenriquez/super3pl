<?php

namespace App\Http\Controllers;

use App\Models\RegistroErrorValidacion;
use Illuminate\Http\Request;

class RegistroErrorValidacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $errores = RegistroErrorValidacion::with(['user', 'pedido'])
            ->latest()
            ->paginate(10);

        return view('errores.index', compact('errores'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RegistroErrorValidacion $registroErrorValidacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RegistroErrorValidacion $registroErrorValidacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RegistroErrorValidacion $registroErrorValidacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegistroErrorValidacion $registroErrorValidacion)
    {
        //
    }
}
