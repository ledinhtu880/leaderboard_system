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
    public function memberManagement()
    {
        $members = Member::all();
        return view('admin.member.dashboard', compact('members'));
    }

    // - Hàm liên quan đến người dùng
    public function memberProfile() {}
}
