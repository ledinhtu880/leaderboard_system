<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function statistics()
    {
        // HelperController::forceRefreshChart();
        $data = HelperController::getDataFromSuperset();
        $members = HelperController::rankingStudent($data);
        usort($members, function ($a, $b) {
            return $a['STT'] - $b['STT'];
        });

        return view('statistics', ['members' => $members]);
    }
    public function leaderboard()
    {
        // HelperController::forceRefreshChart();
        $data = HelperController::getDataFromSuperset();
        $members = HelperController::rankingStudent($data);

        return view('leaderboard', [
            'members' => $members
        ]);
    }
    public function memberProfile()
    {
        // HelperController::forceRefreshChart();
        $member = [
            'msv' => Session::get('username'),
            'name' => Session::get('name'),
            'phone' => Session::get('phone'),
            'email' => Session::get('email'),
            'birthdate' => Session::get('birthdate'),
            'class' => Session::get('class'),
            'gpa4' => Session::get('gpa4'),
            'gpa10' => Session::get('gpa10'),
        ];
        $listMark = HelperController::getMarkByStudentId(Session::get('msv'));
        $listMark['ranking'] = HelperController::getRankingById(Session::get('msv'));

        // Convert member to array, merge with listMark, then convert back to object
        $mergedData = array_merge($member, $listMark);
        $member = (object)$mergedData;
        return view('profile', compact('member'));
    }
}
