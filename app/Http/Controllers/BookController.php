<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class BookController extends Controller
{
    public function __construct(Request $request)
    {
        if (!$request->user()) {
            throw new UnauthorizedHttpException(challenge: 'Unauthorized');
        }

        $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE'];
        if (!in_array($request->method(), $allowedMethods)) {
            throw new MethodNotAllowedHttpException($allowedMethods);
        }
    }

    public function index(Request $request)
    {
        try {
            $query = $request->input('search');
            $books = Book::when($query, function ($queryBuilder) use ($query) {
                return $queryBuilder->where('author', 'LIKE', "%{$query}%")
                                    ->orWhere('genre', 'LIKE', "%{$query}%");
            })->paginate(10);

            if ($books->isEmpty() && $query) {
                return response()->json(['status' => 'error', 'message' => 'No available books matching your search.'], 404);
            }

            return response()->json(['status' => 'success', 'data' => $books], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to retrieve books.'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'author' => 'required|string',
                'published_date' => 'required|date',
                'genre' => 'nullable|string',
            ]);

            $book = Book::create($request->all());
            return response()->json(['status' => 'success', 'data' => $book], 201);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to create book.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $book = Book::findOrFail($id);
            return response()->json(['status' => 'success', 'data' => $book], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Book not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to retrieve book.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $book = Book::findOrFail($id);

            $validatedData = $request->validate([
                'title' => 'required|string',
                'author' => 'required|string',
                'published_date' => 'required|date',
                'genre' => 'nullable|string',
            ]);

            $book->update($validatedData);

            return response()->json(['status' => 'success', 'message' => 'Book updated successfully', 'data' => $book], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Book not found.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to update book.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $book = Book::findOrFail($id);
            $book->delete();
            return response()->json(['status' => 'success', 'message' => 'Book deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Book not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to delete book.'], 500);
        }
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }
}
