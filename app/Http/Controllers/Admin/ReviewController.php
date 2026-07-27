<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReviewModel as Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['task', 'client', 'freelancer'])
            ->latest()
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review removed.');
    }
}
