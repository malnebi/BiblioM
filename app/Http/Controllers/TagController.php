<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{


    public function __invoke(Tag $tag){

        //jobs for this tags 

        
        
        return view('results', ['jobs' => $tag->jobs] );  // pass all jobs associated with this tag    }

 }
}