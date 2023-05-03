<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Models\Genre;
use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function fetch(Request $request)
    {
        $type_id = $request->input('type_id');
        $limit = $request->input('limit', 20);

        $genres = Genre::with('type');

        if ($type_id) {
            $genres->where('type_id', $type_id);
        }

        return ResponseFormatter::success($genres->paginate($limit), 'Genres found');
    }

    public function create(CreateGenreRequest $request)
    {
        try {

            $genre = Genre::create([
                'name' => $request->name,
                'type_id' => $request->type_id
            ]);

            if (!$genre) {
                throw new Exception('genre not created');
            }

            return ResponseFormatter::success($genre, 'Genre created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        }
    }

    public function update(UpdateGenreRequest $request, $id)
    {
        try {

            $genre = Genre::find($id);

            if (!$genre) {
                throw new Exception('Genre not found');
            }

            $genre->update([
                'name' => $request->name,
                'type_id' => $genre->type_id,
            ]);

            return ResponseFormatter::success($genre, 'Genre updated');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage());
        }
    }
}
