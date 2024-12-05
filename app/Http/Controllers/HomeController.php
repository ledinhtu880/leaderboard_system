<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\GroupMember;
use App\Models\Member;
use App\Models\Topic;
use App\Models\Group;

class HomeController extends Controller
{
    public function index()
    {
        return view("index");
    }

    // - Hàm liên quan đến quản trị viên
    public function groupManagement()
    {
        try {
            if ((!Session::has("type") && !Session::has("message")) || Session::get('type') == 'info') {
                Session::flash('type', 'info');
                Session::flash('message', 'Danh sách nhóm');
            }
            $members = Member::with('groupMemberships')->get();

            $result = [];

            $groupedMembers = GroupMember::with('member')
                ->get()
                ->groupBy('group_id');

            foreach ($groupedMembers as $groupId => $groupMembers) {
                $validMembers = $groupMembers->filter(function ($groupMember) {
                    return $groupMember->member !== null;
                });

                if ($validMembers->isEmpty()) {
                    continue;
                }

                $groupStats = $this->calculateGroupStats($validMembers);

                $result["group_$groupId"] = [
                    'members' => $validMembers->map(function ($groupMember) {
                        return [
                            'id' => $groupMember->member->id,
                            'name' => $groupMember->member->name,
                            'gpa' => $groupMember->member->gpa,
                            'last_gpa' => $groupMember->member->last_gpa,
                        ];
                    })->toArray(),
                    'stats' => $groupStats
                ];
            }
            ksort($result);

            return view('admin.group.dashboard', compact('result'));
        } catch (\Exception $e) {
            // Log lỗi nếu cần
            Log::error('Group view error: ' . $e->getMessage());
        }
    }
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
