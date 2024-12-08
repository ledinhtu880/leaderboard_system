<?php

namespace App\Http\Controllers;

class AttendanceSessionController extends Controller
{
    public function index()
    {

        return view("admin.session.index");
    }
}
