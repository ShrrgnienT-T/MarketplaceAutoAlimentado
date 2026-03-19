<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Product::class);

        $products = Product::query()
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }
}
