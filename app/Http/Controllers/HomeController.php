<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Topic;

class HomeController extends Controller
{
    public function index()
    {
        return view("index");
    }

    // - Hàm liên quan đến quản trị viên
    public function memberManagement(Request $request)
    {
        $members = Member::all();
        return view('admin.member.dashboard', compact('members'));
    }
    public function topicManagement()
    {
        $topics = Topic::with('members.member')->get();
        $members = Member::all();
        return view('admin.topic.index', compact('topics', 'members'));
    }

    // - Hàm liên quan đến người dùng
    public function userTopic()
    {
        $user = Auth::user();
        $topics = Topic::all();
        $selectedTopic = DB::table('member_topics')->where('member_id', $user->member->id)->value('topic_id');
        return view('user.topic', compact('topics', 'selectedTopic'));
    }
}
