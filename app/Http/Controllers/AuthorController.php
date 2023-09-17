<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorRequest;
use App\Models\Author;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authors = Author::all();
        return response()->json(["authors" => $authors]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthorRequest $request)
    {
        $author = Author::create($request->toArray());
        return response()->json(["message" => "Author created successfully"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $author = Author::with('book:title')->find($id);

        if (! $author){
            return response()->json(["message" => "Author not found"], 404);

        }

        return response()->json(["author" => $author]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AuthorRequest $request, $id)
    {
        $author = Author::find($id);

        if (! $author){
            return response()->json(["message" => "Author not found"], 404);
        }

        $author->update($request->toArray());

        return response()->json(["message" => "Author updated successfully"]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $author = Author::find($id);

        if (! $author){
            return response()->json(["message" => "Author not found"], 404);
        }

        return DB::transaction(function() use ($author) {
            try{
                $author->book()->detach();
                $author->delete();
                return response()->json(["message" => "Author deleted successfully"]);
            }
            catch (Exception $e){
                return response()->json(["message" => "Error Deleting Author => ".$e], 401);
            }
        });
    }
}
