<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10)->withQueryString();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'ean' => 'nullable|string|max:50',
            'codigo' => 'nullable|string|max:50',
        ]);

        User::create($request->only(['descripcion', 'ean', 'codigo']));

        return redirect()->route('products.create')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        // Paginar los pedidos del usuario
        $pedidos = $user->pedidos()->paginate(10); // o el número que quieras

        return view('users.show', compact('user', 'pedidos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'codigo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'ean' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($id);
        $user->codigo = $request->codigo;
        $user->descripcion = $request->descripcion;
        $user->ean = $request->ean;
        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $product)
    {
        $product->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado correctamente');
    }
}
