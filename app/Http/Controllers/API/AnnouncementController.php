<?php

namespace App\Http\Controllers\API;

use App\Models\Announcement;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAnnouncementRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function fetch()
    {
        $today = Carbon::today();

        $announcements = Announcement::with('content')->whereDate('created_at', '>=', $today)->get();

        return ResponseFormatter::success($announcements, 'Announcements found');
    }

    public function create(CreateAnnouncementRequest $request)
    {

        try {

            $announcement = Announcement::create([
                'title' => $request->title,
                'body' => $request->body,
                'content_id' => $request->content_id,
            ]);

            if (!$announcement) {
                throw new Exception('Announcement not created');
            }

            return ResponseFormatter::success($announcement, 'Announcement created');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage());
        }
    }

    public function delete(Request $request)
    {

        try {
            $yesterday = Carbon::yesterday();
            $id = $request->input('id');

            if ($id) {
                Announcement::find($id)->delete();

                return ResponseFormatter::success('', 'Announcement deleted');
            }

            $announcements = Announcement::whereDate('created_at', '<=', $yesterday);

            if (!$announcements) {
                throw new Exception('Announcements not found');
            }

            $announcements->delete();

            return ResponseFormatter::success('', 'Announcements deleted');
        } catch (Exception $error) {
            return ResponseFormatter::success($error->getMessage());
        }
    }
}
