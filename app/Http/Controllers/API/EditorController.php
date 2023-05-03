<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class EditorController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 100);

        $editors = User::query()->where('role_id', '=', 2);

        if ($id) {
            $editor = $editors->find($id);

            if ($editor) {

                return ResponseFormatter::success($editor, 'Editor found');
            }
        }

        if ($name) {
            $editors->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success($editors->paginate($limit), 'Editors found');
    }
}
