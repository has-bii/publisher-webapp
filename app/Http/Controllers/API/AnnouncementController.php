<?php

namespace App\Http\Controllers\API;

use App\Models\Announcement;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function fetch(Request $request)
    {
        $limit = $request->input('limit', 100);

        // $announcements = Announcement::select('*', Announcement::raw('DATE_FORMAT(announcements.created_at, "%d-%m-%Y") as created_date'), Announcement::raw('SUBSTRING(body, 1, 350) as short_body',))->with('content.publisher')->orderBy('created_at', 'desc')->get();

        $announcements = Announcement::with('content.publisher')->with('content.author')->orderBy('created_at', 'desc');

        return ResponseFormatter::success($announcements->paginate($limit), 'Announcements found');
    }

    public function fetch_publisher(Request $request)
    {
        $title = $request->input('title');
        $content_name = $request->input('content_name');
        $publisher_id =  $request->input('publisher_id');
        $limit = $request->input('limit', 100);

        $announcements = Announcement::with('content');

        if ($publisher_id) {
            $announcements->where('publisher_id', $publisher_id);
        }

        if ($title) {
            $announcements->where('name', 'like', '%' . $title . '%');
        }

        if ($content_name) {
            $announcements->whereHas('content', function ($query) use ($content_name) {
                $query->where('name', 'like', '%' . $content_name . '%');
            });
        }

        return ResponseFormatter::success($announcements->paginate($limit), 'Announcements fetched');
    }

    public function create(CreateAnnouncementRequest $request)
    {

        try {

            $announcement = Announcement::create([
                'title' => $request->title,
                'body' => $request->body,
                'content_id' => $request->content_id,
                'publisher_id' => $request->publisher_id,
            ]);

            if (!$announcement) {
                throw new Exception('Announcement not created');
            }

            return ResponseFormatter::success($announcement, 'Announcement created');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage());
        }
    }

    public function update(UpdateAnnouncementRequest $request, $id)
    {

        try {
            $announcement = Announcement::find($id);

            if (!$announcement) {

                throw new Exception('Announcement not found');
            }

            $announcement->update([
                'title' => isset($request->title) ? $request->title : $announcement->title,
                'body' => isset($request->body) ? $request->body : $announcement->body,
                'content_id' => $announcement->content_id,
                'publisher_id' => $announcement->publisher_id,
            ]);

            return ResponseFormatter::success($announcement, 'Announcement has been updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $yesterday = Carbon::yesterday();

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
