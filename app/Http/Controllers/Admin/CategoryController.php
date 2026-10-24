<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryModel;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = CategoryModel::withCount('services')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        CategoryModel::create($request->only('name', 'description'));
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(CategoryModel $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, CategoryModel $category)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($request->only('name', 'description'));
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy(CategoryModel $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
