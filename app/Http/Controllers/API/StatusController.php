<?php

namespace App\Http\Controllers\API;

use App\Models\Status;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class StatusController extends Controller
{
    public function fetch(Request $request)
    {
        $limit = $request->input('limit', 20);

        $types = Status::query();

        return ResponseFormatter::success($types->paginate($limit), 'Types found');
    }
}
