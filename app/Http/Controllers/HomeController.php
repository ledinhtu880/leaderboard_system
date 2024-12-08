<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\MemberSchedule;
use App\Models\Member;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        return view("index");
    }

    // - Hàm liên quan đến quản trị viên
    public function memberManagement()
    {
        $members = Member::all();
        return view('admin.member.dashboard', compact('members'));
    }

    // - Hàm liên quan đến người dùng
    public function memberProfile()
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->first();
        return view('member.profile', compact('member'));
    }
    public function memberCalendar()
    {
        $memberId = auth()->user()->member->id;
        $schedules = MemberSchedule::where('member_id', $memberId)
            ->with(['schedule' => function ($query) {
                $query->with(['subject', 'teacher', 'room']);
            }])
            ->get();
        return view('member.calendar', compact('schedules'));
    }
    public function memberAttendance()
    {
        return view('member.attendance');
    }
}
