<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Member;

class GroupController extends Controller
{
    public function clusterView()
    {
        return view('admin.cluster');
    }
    public function runCluster()
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
            foreach ($result as $groupName => $groupData) {
                $group = Group::firstOrCreate(['name' => $groupName]);

                foreach ($groupData['members'] as $memberData) {
                    GroupMember::create([
                        'group_id' => $group->id,
                        'member_id' => $memberData['id'],
                        'status' => 'suggested'
                    ]);
                }
            }
        });

        return response()->json($result);
    }
    public function groupDashboard()
    {
        try {
            if ((!Session::has("type") && !Session::has("message")) || Session::get('type') == 'info') {
                Session::flash('type', 'info');
                Session::flash('message', 'Danh sách nhóm');
            }
            $members = Member::with('groupMemberships')->get();

            $result = [];

            $groupedMembers = GroupMember::with('member')
                ->get()
                ->groupBy('group_id');

            foreach ($groupedMembers as $groupId => $groupMembers) {
                $validMembers = $groupMembers->filter(function ($groupMember) {
                    return $groupMember->member !== null;
                });

                if ($validMembers->isEmpty()) {
                    continue;
                }

                $groupStats = $this->calculateGroupStats($validMembers);

                $result["group_$groupId"] = [
                    'members' => $validMembers->map(function ($groupMember) {
                        return [
                            'id' => $groupMember->member->id,
                            'name' => $groupMember->member->name,
                            'gpa' => $groupMember->member->gpa,
                            'last_gpa' => $groupMember->member->last_gpa,
                            'final_score' => $groupMember->member->final_score,
                            'personality' => $groupMember->member->personality,
                            'hobby' => $groupMember->member->hobby
                        ];
                    })->toArray(),
                    'stats' => $groupStats
                ];
            }
            ksort($result);

            return view('admin.group.dashboard', compact('result'));
        } catch (\Exception $e) {
            // Log lỗi nếu cần
            Log::error('Group view error: ' . $e->getMessage());
        }
    }
    private function calculateGroupStats($groupMembers)
    {
        return [
            'avg_gpa' => $groupMembers->avg('member.gpa'),
            'avg_final_score' => $groupMembers->avg('member.final_score'),
            'hobbies' => $groupMembers->pluck('member.hobby')->unique()->values()->toArray()
        ];
    }
    public function userDashboard(Request $request)
    {
        if ((!Session::has("type") && !Session::has("message")) || Session::get('type') == 'info') {
            Session::flash('type', 'info');
            Session::flash('message', 'Danh sách thành viên');
        }

        $members = Member::all();
        return view('admin.user.dashboard', compact('members'));
    }
    public function updateGroups(Request $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->all() as $groupId => $memberIds) {
                DB::table('group_member')
                    ->whereIn('member_id', $memberIds)
                    ->update([
                        'group_id' => $groupId,
                        'updated_at' => now(),
                        'status' => 'confirmed'
                    ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Phân nhóm đã được cập nhật thành công'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'danger',
                'message' => 'Có lỗi xảy ra khi cập nhật phân nhóm',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
