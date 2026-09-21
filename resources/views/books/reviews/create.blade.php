@extends('layouts.app')


@section('content')
    <h1 class="mb-10 text-2xl">Add Review for {{ $book->title }} </h1>

    <form action="{{ route('books.reviews.store', $book) }}" method="post">
        @csrf
        <div class="mb-4">
            <label for="review">Review</label>
            <textarea name="review" id="review" required class="input">{{ old('review') }}</textarea>
            @error('review')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <label for="rating">Rating</label>
        <select name="rating" id="rating" class="input mb-4" required>
            <option value="">Select a Rating</option>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        @error('rating')
            <p class="error">{{ $message }}</p>
        @enderror

        <button class="btn">Add Review</button>
    </form>
@endsection
