<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;

class LandingController extends Controller
{
    public function index()
    {
        $categories = CategoryModel::withCount('services')->get();
        return view('landing', compact('categories'));
    }
}

