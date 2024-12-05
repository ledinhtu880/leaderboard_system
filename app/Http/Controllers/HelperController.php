<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelperController extends Controller
{
    private function calculateGroupStats($groupMembers)
    {
        return [
            'avg_gpa' => $groupMembers->avg('member.gpa'),
        ];
    }
}
