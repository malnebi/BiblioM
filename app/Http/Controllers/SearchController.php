<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Job;

class SearchController extends Controller
{
    
    public function __invoke()
    {
        $jobs = Job::query()
        ->with(['employer', 'tags'])
        ->where('title', 'LIKE', '%'.request('q').'%')
        ->get();

        // return $jobs; // view search results in JSON 

        return view('results', ['jobs' => $jobs] );  // pass throught list of jobs

    }
}
