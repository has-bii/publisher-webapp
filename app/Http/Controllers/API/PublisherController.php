<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use App\Models\Publisher;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreatePublisherRequest;
use App\Http\Requests\UpdatePublisherRequest;

class PublisherController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 100);

        $publisherQuery = Publisher::with(['users' => function ($query) {
            $query->where('role_id', 2);
        }]);

        if ($id) {
            $publisher = $publisherQuery->find($id);

            if ($publisher) {
                return ResponseFormatter::success($publisher, 'Publisher found');
            }

            return ResponseFormatter::error('Publisher not found', 404);
        }

        $publishers = $publisherQuery;

        if ($name) {
            $publishers->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success($publishers->paginate($limit), 'Publishers found');
    }

    public function create(CreatePublisherRequest $request)
    {
        try {

            $publisher = Publisher::create([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if (!$publisher) {
                throw new Exception('Company not created');
            }

            $user = User::find(Auth::id());
            $user->publishers()->attach($publisher->id);

            // Load users at company
            $publisher->load('users');

            return ResponseFormatter::success($publisher, 'Publisher created');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function update(UpdatePublisherRequest $request, $id)
    {
        try {

            $publisher = Publisher::find($id);

            if (!$publisher) {
                throw new Exception('Publisher not found');
            }

            $publisher->update([
                'name' => isset($request->name) ? $request->name : $publisher->name,
                'email' => isset($request->email) ? $request->email : $publisher->email,
            ]);

            return ResponseFormatter::success($publisher, 'Publisher updated');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage());
        }
    }
}
