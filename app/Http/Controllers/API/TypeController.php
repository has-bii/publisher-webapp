<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Type;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTypeRequest;
use App\Http\Requests\UpdateTypeRequest;
use App\Models\Genre;

class TypeController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 20);

        $types = Type::with('genre');

        if ($id) {
            $types->find($id);
        }

        return ResponseFormatter::success($types->paginate($limit), 'Types found');
    }

    public function create(CreateTypeRequest $request)
    {
        try {

            $type = Type::create([
                'name' => $request->name
            ]);

            if (!$type) {
                throw new Exception('type not created');
            }

            return ResponseFormatter::success($type, 'Type created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        }
    }

    public function update(UpdateTypeRequest $request, $id)
    {
        try {

            $type = Type::find($id);

            if (!$type) {
                throw new Exception('Type not found');
            }

            $type->update([
                'name' => $request->name,
            ]);

            return ResponseFormatter::success($type, 'Type updated');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage());
        }
    }

    public function delete($id)
    {

        try {

            if ($id) {

                $type = Type::find($id);

                if (!$type) {
                    throw new Exception('Type not found');
                }

                $genres = Genre::where('type_id', $id);

                $genres->delete();

                $type->delete();


                return ResponseFormatter::success('', 'Content deleted');
            }
        } catch (Exception $error) {

            return ResponseFormatter::error($error->getMessage());
        }
    }
}
