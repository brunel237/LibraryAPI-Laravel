<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\BookCopy;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::all();

        return response()->json(["books" => $books]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request)
    {
        DB::transaction(function() use ($request){
            $book = Book::create($request->toArray());
            $book->author()->attach($request['authors']);
            $bc = BookCopy::create([
                'book_id' => $book->id,
                'stock_quantity' => $request['stock_quantity'],
            ]);
        });

        return response()->json(["message" => "Book created successfully"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::with('author:name')->find($id);

        if (! $book){
            return response()->json(["message" => "Book not found"], 404);

        }

        return response()->json(["book" => $book]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookRequest $request, $id)
    {
        $book = Book::find($id);

        if (! $book){
            return response()->json(["message" => "Book not found"], 404);
        }

        $book->update($request->toArray());

        return response()->json(["message" => "Book updated successfully"]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (! $book){
            return response()->json(["message" => "Book not found"], 404);
        }

        return DB::transaction(function() use ($book) {
            try{
                $book->author()->detach();
                $book->bookCopy()->delete();
                $book->delete();
                return response()->json(["message" => "Book deleted successfully"]);
            }
            catch (Exception $e){
                return response()->json(["message" => "Error Deleting Book => ".$e], 401);
            }
        });

    }
}
