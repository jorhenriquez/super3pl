<?php

namespace App\Http\Controllers;

use App\Models\Inventory;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::with('product')->paginate(20);
        return view('inventories.index', compact('inventories'));
    }
}
