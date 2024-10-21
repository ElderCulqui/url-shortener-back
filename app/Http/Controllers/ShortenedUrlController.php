<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShortenedUrlRequest;
use App\Models\ShortenedUrl;
use AshAllenDesign\ShortURL\Classes\Builder;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShortenedUrlController extends Controller
{
    public function index(Request $request)
    {
        $shortUrls = ShortURL::paginate($request->get('per_page', 10));

        return response()->json($shortUrls);
    }

    public function store(ShortenedUrlRequest $request)
    {
        $data = $request->validated();
        $shortUrl = app(Builder::class)->destinationUrl($data['original_url'])->make();

        return response()->json($shortUrl, JsonResponse::HTTP_CREATED);
    }
}
