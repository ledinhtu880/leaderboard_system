<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Member;

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
    /* public function memberCalendar()
    {
        $memberId = auth()->user()->member->id;
        $lessons = CourseRegistration::where('member_id', $memberId)
            ->with([
                'subject',
                'subject.schedules',
                'subject.schedules.teacher',
                'subject.schedules.room'
            ])->get();
        return view('member.calendar', compact('lessons'));
    } */
    public function memberCalendar()
    {
        $memberId = auth()->user()->member->id;
        /* $courseRegistrationIds = CourseRegistration::where('member_id', $memberId)->pluck('id');
        // Thay đổi câu truy vấn ở đây
        $lessons = Lesson::whereIn('schedule_id', $courseRegistrationIds)  // Thay 'whereHas' bằng 'whereIn'
            ->with([
                'schedule',
                'schedule.subject',
                'schedule.room'
            ])
            ->get();
        dd($lessons); */
        $lessons = DB::table('lessons AS l')
            ->join('schedules AS s', 'l.schedule_id', '=', 's.id')
            ->join('subjects AS sj', 's.subject_id', '=', 'sj.id')
            ->join('rooms AS r', 's.room_id', '=', 'r.id')
            ->join('course_registrations AS cr', 'sj.id', '=', 'cr.subject_id')
            ->join('members AS m', 'cr.member_id', '=', 'm.id')
            ->where('m.id', $memberId)
            ->select('sj.subject_name', 'r.name as room_name', 'l.lesson_date', 'l.start_time', 'l.end_time', 'l.week_index')
            ->get();

        return view('member.calendar', compact('lessons'));
    }
    public function memberAttendance()
    {
        return view('member.attendance');
    }
}
