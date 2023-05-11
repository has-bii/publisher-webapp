<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateContentRequest;
use App\Http\Requests\UpdateContentRequest;
use App\Models\Content;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContentController extends Controller
{
    public function fetch(Request $request)
    {

        $name = $request->input('name');
        $price_below = $request->input('price_below');
        $price_above = $request->input('price_above');
        $type_id = $request->input('type_id');
        $genre_id = $request->input('genre_id');
        $author_id = $request->input('author_id');
        $genre_id = $request->input('genre_id');
        $status_id = $request->input('status_id');
        $publisher_id = $request->input('publisher_id');
        $published_date = $request->input('published_date');
        $limit = $request->input('limit', 100);

        $contents = Content::with('type', 'user_author', 'user_editor', 'publisher', 'genre', 'status');

        if ($name) {
            $contents->where('name', 'like', '%' . $name . '%');
        }

        if ($price_below) {
            $contents->where('price', '<=', $price_below);
        }

        if ($price_above) {
            $contents->where('price', '>=', $price_above);
        }

        if ($type_id) {
            $contents->where('type_id', $type_id);
        }

        if ($genre_id) {
            $contents->where('genre_id', $genre_id);
        }

        if ($status_id) {
            $contents->where('status_id', $status_id);
        }

        if ($author_id) {
            $contents->where('author_id', $author_id);
        }

        if ($publisher_id) {
            $contents->where('publisher_id', $publisher_id);
        }

        if ($published_date) {
            $contents->where('created_at', $published_date);
        }

        return ResponseFormatter::success($contents->paginate($limit), 'Contents found');
    }

    public function create(CreateContentRequest $request)
    {

        try {

            if ($request->hasFile('cover')) {
                $cover = $request->file('cover')->store('public/covers');
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file')->store('public/files');
            }

            $content = Content::create([
                'name' => $request->name,
                'cover' => isset($cover) ? $cover : '',
                'price' => null,
                'file' => $file,
                'type_id' => $request->type_id,
                'genre_id' => $request->genre_id,
                'editor_id' => null,
                'author_id' => Auth::id(),
                'publisher_id' => null,
                'status_id' => 1,
            ]);

            if (!$content) {
                throw new Exception('Content not created');
            }

            return ResponseFormatter::success($content, 'Content created');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage());
        }
    }

    public function update(UpdateContentRequest $request, $id)
    {
        try {

            $content = Content::find($id);

            if (!$content) {
                throw new Exception('Content not found');
            }

            if ($request->hasFile('cover')) {
                $cover = $request->file('cover')->store('public/covers');
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file')->store('public/files');
            }

            $content->update([
                'name' => isset($request->name) ? $request->name : $content->name,
                'cover' => isset($cover) ? $cover : $content->cover,
                'price' => isset($request->price) ? $request->price : $content->price,
                'file' => isset($file) ? $file : $content->file,
                'type_id' => isset($request->type_id) ? $request->type_id : $content->type_id,
                'genre_id' => isset($request->genre_id) ? $request->genre_id : $content->genre_id,
                'editor_id' => isset($request->editor_id) ? $request->editor_id : $content->editor_id,
                'author_id' => isset($request->author_id) ? $request->author_id : $content->author_id,
                'publisher_id' => isset($request->publisher_id) ? $request->publisher_id : $content->publisher_id,
                'status_id' => isset($request->status_id) ? $request->status_id : $content->status_id,
            ]);

            return ResponseFormatter::success($content, 'content updated');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage());
        }
    }
}
