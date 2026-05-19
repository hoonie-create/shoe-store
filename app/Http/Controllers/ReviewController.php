<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'comment' => 'required|min:5',
            'rating'  => 'required|integer|between:1,5' 
        ]);

        Review::create([
            'user_id'    => Auth::id(),
            'product_id' => $productId,
            'comment'    => $request->comment,
            'rating'     => $request->rating
        ]);

        return redirect()->back()->with('success', 'Ulasan dan rating berhasil disimpan!');
    }
}