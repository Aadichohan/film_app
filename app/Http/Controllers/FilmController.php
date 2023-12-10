<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;

class FilmController extends Controller
{
    //

    public function index()
    {
        $movies = Film::all();
        return response()->json($movies);
    }

    // public function rate(Request $request, Film $movie)
    public function rate(Request $request)
    {
        // dd($request->id);
        $request->validate([
            // 'id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $user = $request->user();
        // dd($user);
        $user->rating()->updateOrCreate(
            ['movie_id' => $request->id],
            [
                'rating' => $request->input('rating'),
                'comment' => $request->input('comment'),
            ]
        );

        return response()->json(['message' => 'Rating and comment submitted successfully']);
    }
}
