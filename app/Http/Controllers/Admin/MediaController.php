<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;


class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($path)
    {
        if (!Storage::exists($path)) abort(404);

        $file = Storage::get($path);
        $type = Storage::mimeType($path);
        return Response::make($file, 200)->header("Content-Type", $type);
    }
}
