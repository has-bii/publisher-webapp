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
use GuzzleHttp\Psr7\Response;

class PublisherController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $email = $request->input('email');
        $limit = $request->input('limit', 100);

        $publisherQuery = Publisher::with('users.role');

        if ($id) {
            $publisher = $publisherQuery->find($id);

            if ($publisher) {
                return ResponseFormatter::success($publisher, 'Publisher found');
            }

            return ResponseFormatter::error('Publisher not found', 404);
        }

        $publishers = $publisherQuery->orderBy('name', 'asc');

        if ($name) {
            $publishers->where('name', 'like', '%' . $name . '%');
        }

        if ($email) {
            $publishers->where('name', 'like', '%' . $email . '%');
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

    public function delete($id)
    {
        try {
            $publisher = Publisher::with('users')->find($id);

            if (!$publisher) {
                throw new Exception('Publisher not found!');
            }

            $publisher->delete();

            return ResponseFormatter::success('', 'Publisher deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        }
    }

    public function remove_member(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $publisher_id = $request->input('publisher_id');

            $user = User::find($user_id);

            $user->publishers()->detach($publisher_id);

            return ResponseFormatter::success('', 'Remove Member success');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        }
    }

    public function add_member(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $publisher_id = $request->input('publisher_id');

            $user = User::find($user_id);

            $user->publishers()->attach($publisher_id);

            return ResponseFormatter::success('', 'Add Member success');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        }
    }
}
