<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UrlShortenerController extends Controller
{
    public function shorten(Request $request)
    {
        $request->validate([
            'long_url' => 'required|url',
        ]);

        $shortUrl = Str::random(6);
        die($shortUrl);

        // Here you would typically save the $shortUrl and the $request->long_url to the database.

        return redirect('/')->with('shortened_url', url($shortUrl));
    }
}
