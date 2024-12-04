<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Topic;

class TopicController extends Controller
{
    // - User
    public function userTopic()
    {
        $user = Auth::user();
        $topics = Topic::all();
        $selectedTopic = DB::table('member_topics')->where('member_id', $user->member->id)->value('topic_id');
        return view('user.topic', compact('topics', 'selectedTopic'));
    }
    public function userStore(Request $request)
    {
        $user = Auth::user();

        $topicId = $request->input('topic_id');
        $existingRegistration = DB::table('member_topics')->where('member_id', $user->member->id)->first();
        if ($existingRegistration) {
            if ($existingRegistration->status === 'pending') {
                DB::table('member_topics')->where('member_id', $user->member->id)->update(['topic_id' => $topicId]);
                return response()->json(['status' => 'success', 'message' => 'Thay đổi chủ đề thành công']);
            } elseif ($existingRegistration->status === 'locked') {
                return response()->json(['status' => 'danger', 'message' => "Danh sách chủ đề đã bị khóa!\nKhông thể thay đổi"]);
            }
        }

        DB::table('member_topics')->insert([
            'member_id' => $user->member->id,
            'topic_id' => $topicId,
            'status' => 'pending'
        ]);

        return response()->json(['status' => 'success', 'message' => 'Chọn chủ đề thành công']);
    }

    // - Admin
    public function adminTopic()
    {
        $topics = Topic::with('members.member')->get();
        $members = Member::all();
        return view('admin.topic.index', compact('topics', 'members'));
    }
}
