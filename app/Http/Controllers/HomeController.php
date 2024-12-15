<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Member;
use App\Models\Lesson;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $members = HelperController::getDataFromSuperset();
        usort($members, function ($a, $b) {
            return $a['STT'] - $b['STT'];
        });
        return view('index', ['members' => $members]);
    }
    public function leaderboard()
    {
        $data = HelperController::getDataFromSuperset();
        $members = HelperController::rankingStudent($data);

        $firstPlace = $members[0] ?? null;
        $secondPlace = $members[1] ?? null;
        $thirdPlace = $members[2] ?? null;
        $remainingMembers = array_slice($members, 3);

        return view('leaderboard', [
            'firstPlace' => $firstPlace,
            'secondPlace' => $secondPlace,
            'thirdPlace' => $thirdPlace,
            'remainingMembers' => $remainingMembers
        ]);
    }
    public function memberProfile()
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->first();
        $listMark = HelperController::getMarkByStudentId($user->username);
        $listMark['ranking'] = HelperController::getRankingById($user->username);

        // Convert member to array, merge with listMark, then convert back to object
        $memberArray = $member->toArray();
        $mergedData = array_merge($memberArray, $listMark);
        $member = (object)$mergedData;
        return view('profile', compact('member'));
    }
}
