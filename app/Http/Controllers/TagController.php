<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{


    public function __invoke(Tag $tag){

      abort_unless($tag->approved, 404);

        //books for this tags 
      $data = [

         'books' => $tag->books,  //proslijedi sve knjige povezane sa ovim tagom

      ];
                
        return view('results', $data );  // pass all books associated with this tag    

 }


 public function show($tag){
    $tag = Tag::where('approved', true)->where('name', $tag)->firstOrFail();
    return view('tags.show', ['tag' => $tag]);
 }      

 public function tags(){
   $tags = Tag::where('approved', true)->get();
    return view('tags', ['tags' => $tags]);
 }


 public function searchTag(Request $request)
 {
     $tags = Tag::query()
     ->with(['books'])
   ->where('approved', true)
     ->where('name', 'LIKE', '%'.request('q').'%')
     ->get();

     

     
     return view('results-tag', ['tags' => $tags] );  // pass throught list of books
     
     // return $books; for view search results in JSON 
 }

}