<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $filter = $request->input('filter', '');
        $page = $request->input('page', 1);

        $books = Book::when(
            $title,
            fn ($query, $title) => $query->title($title)
        );

        $books = match($filter) {
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLast6Months(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLast6Months(),
            default => $books->latest()->withReviewsCount()->withAvgRating()
        };

        $cacheKey = "books:{$filter}:{$title}:page:{$page}";
        $payload = cache()->remember($cacheKey, 3600, fn () => $books->paginate(10)->toArray());
        $books = new LengthAwarePaginator(
            Book::hydrate($payload['data']),
            $payload['total'],
            $payload['per_page'],
            $payload['current_page'],
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );

        return view('books.index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $book = Book::withReviewsCount()->withAvgRating()->findOrFail($id);

        $cacheKey = 'book:' . $book->id;

        $reviews = cache()->remember($cacheKey, 3600,
            fn () => $book->reviews()->latest()->get()->toArray()
        );

        $book->setRelation('reviews', Review::hydrate($reviews));

        return view('books.show', ['book' => $book]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
