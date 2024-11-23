<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Member;

class GroupController extends Controller
{
    public function createGroups()
    {
        // Gọi Python script và nhận kết quả
        $pythonScript = base_path('python/cluster_groups.py');
        $output = shell_exec("python $pythonScript");
        $result = json_decode($output, true);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 400);
        }

        // Lưu kết quả vào database
        DB::transaction(function () use ($result) {
            // Xóa các suggested groups cũ
            GroupMember::where('status', 'suggested')->delete();

            // Lưu các nhóm được đề xuất
            foreach ($result['suggested_groups'] as $groupName => $groupData) {
                $group = Group::firstOrCreate(['name' => $groupName]);

                foreach ($groupData['members'] as $memberData) {
                    // Tìm điểm tương thích từ compatibility_scores
                    $compatibilityScore = collect($result['compatibility_scores'])
                        ->where('member_id', $memberData['id'])
                        ->first()['scores'][0]['score'];

                    GroupMember::create([
                        'group_id' => $group->id,
                        'member_id' => $memberData['id'],
                        'compatibility_score' => $compatibilityScore,
                        'status' => 'suggested'
                    ]);
                }
            }
        });

        return response()->json(['message' => 'Clustering completed successfully']);
    }

    public function adminDashboard()
    {
        $groups = Group::with(['members' => function ($query) {
            $query->with('user')->orderBy('compatibility_score', 'desc');
        }])->get();

        return view('admin.groups.index', compact('groups'));
    }

    public function userDashboard()
    {
        $user = auth()->user();
        $member = Member::where('user_id', $user->id)->first();

        if (!$member) {
            return view('user.no-member-profile');
        }

        $suggestedGroups = GroupMember::with('group')
            ->where('member_id', $member->id)
            ->where('status', 'suggested')
            ->orderBy('compatibility_score', 'desc')
            ->get();

        return view('user.dashboard', compact('member', 'suggestedGroups'));
    }
}
