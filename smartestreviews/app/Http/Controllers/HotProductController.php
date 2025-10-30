<?php

namespace App\Http\Controllers;

use App\Models\HotProduct;
use Illuminate\Http\Request;

class HotProductController extends Controller
{
    /**
     * Display all hot products.
     */
    public function index(Request $request)
    {
        $hotProducts = HotProduct::active()
            ->ordered()
            ->paginate(20);

        return view('hot-products.index', compact('hotProducts'));
    }
}
