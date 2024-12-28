<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ShortUrl;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{

    public function index()
    {
        return view('short_url.index');
    }


    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'long_url' => 'required|url|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $longUrl = $request->input('long_url');

        $existing = ShortUrl::where('long_url', $longUrl)->first();
        if ($existing) {
            return redirect()->back()->with('success', "Short URL: " . url($existing->short_code));
        }


        do {
            $shortCode = Str::random(6);
        } while (ShortUrl::where('short_code', $shortCode)->exists());

        $shortUrl = ShortUrl::create([
            'long_url' => $longUrl,
            'short_code' => $shortCode,
        ]);

        return redirect()->back()->with('success', url($shortUrl->short_code));

    }


    public function redirect($shortCode)
    {
        $shortUrl = ShortUrl::where('short_code', $shortCode)->first();

        if (!$shortUrl) {
            return abort(404, 'Short URL not found');
        }

        return redirect($shortUrl->long_url);
    }


}

