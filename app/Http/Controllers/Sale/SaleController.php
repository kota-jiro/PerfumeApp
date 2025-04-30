<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        return view('sale.index');
    }
    public function create()
    {
        return view('sale.create');
    }   
    public function show($id)
    {
        return view('sale.show', ['id' => $id]);
    }
    public function edit($id)
    {
        return view('sale.edit', ['id' => $id]);
    }
    public function destroy($id)
    {
        // Logic to delete the sale
        return redirect()->route('sale.index')->with('success', 'Sale deleted successfully.');
    }
    public function store(Request $request)
    {
        // Logic to store the sale
        return redirect()->route('sale.index')->with('success', 'Sale created successfully.');
    }
    public function update(Request $request, $id)
    {
        // Logic to update the sale
        return redirect()->route('sale.index')->with('success', 'Sale updated successfully.');
    }
}
