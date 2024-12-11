<?php

namespace App\Http\Controllers;

use App\Models\Subject;


class AttendanceSessionController extends Controller
{
    public function index()
    {
        return view("admin.session.index", ['subjects' => Subject::all()]);
    }
}
