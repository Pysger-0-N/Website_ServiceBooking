<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryModel;
use App\Models\ServiceModel;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = ServiceModel::with('category')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $categories = CategoryModel::all();
        return view('admin.services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price'  => 'required|numeric|min:0',
            'unit'        => 'required|string',
        ]);

        ServiceModel::create($request->only('category_id', 'name', 'description', 'base_price', 'unit'));
        return redirect()->route('admin.services.index')->with('success', 'Service berhasil ditambahkan!');
    }

    public function edit(ServiceModel $service)
    {
        $categories = CategoryModel::all();
        return view('admin.services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, ServiceModel $service)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price'  => 'required|numeric|min:0',
            'unit'        => 'required|string',
        ]);

        $service->update($request->only('category_id', 'name', 'description', 'base_price', 'unit'));
        return redirect()->route('admin.services.index')->with('success', 'Service berhasil diupdate!');
    }

    public function destroy(ServiceModel $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service berhasil dihapus!');
    }
}
