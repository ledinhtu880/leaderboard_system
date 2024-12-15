<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function statistics()
    {
        $members = HelperController::getDataFromSuperset();
        usort($members, function ($a, $b) {
            return $a['STT'] - $b['STT'];
        });
        return view('statistics', ['members' => $members]);
    }
    public function leaderboard()
    {
        $data = HelperController::getDataFromSuperset();
        $members = HelperController::rankingStudent($data);

        $firstPlace = $members[0] ?? null;
        $secondPlace = $members[1] ?? null;
        $thirdPlace = $members[2] ?? null;
        // $remainingMembers = array_slice($members, 3);

        return view('leaderboard', [
            'firstPlace' => $firstPlace,
            'secondPlace' => $secondPlace,
            'thirdPlace' => $thirdPlace,
            'members' => $members
        ]);
    }
    public function memberProfile()
    {
        $member = [
            'msv' => Session::get('username'),
            'name' => Session::get('name'),
            'phone' => Session::get('phone'),
            'email' => Session::get('email'),
            'birthdate' => Session::get('birthdate'),
            'class' => Session::get('class'),
            'gpa' => Session::get('gpa'),
        ];
        $listMark = HelperController::getMarkByStudentId(Session::get('username'));
        $listMark['ranking'] = HelperController::getRankingById(Session::get('username'));

        // Convert member to array, merge with listMark, then convert back to object
        $mergedData = array_merge($member, $listMark);
        $member = (object)$mergedData;
        return view('profile', compact('member'));
    }
}
