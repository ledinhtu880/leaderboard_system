<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\GroupMember;
use App\Models\Group;

class ApiController extends Controller
{

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
    public function userStore(Request $request)
    {
        $user = Auth::user();

        $topicId = $request->input('topic_id');
        $existingRegistration = DB::table('member_topics')->where('member_id', $user->member->id)->first();
        if ($existingRegistration) {
            if ($existingRegistration->status === 'pending') {
                DB::table('member_topics')->where('member_id', $user->member->id)->update(['topic_id' => $topicId]);
                return response()->json(['status' => 'success', 'message' => 'Thay đổi chủ đề thành công']);
            } elseif ($existingRegistration->status === 'locked') {
                return response()->json(['status' => 'danger', 'message' => "Danh sách chủ đề đã bị khóa! Không thể thay đổi"]);
            }
        }

        DB::table('member_topics')->insert([
            'member_id' => $user->member->id,
            'topic_id' => $topicId,
            'status' => 'pending'
        ]);

        return response()->json(['status' => 'success', 'message' => 'Chọn chủ đề thành công']);
    }

    public function runClustering()
    {
        $pythonScript = base_path('python/cluster_groups.py');
        $output = shell_exec("python $pythonScript");
        $result = json_decode($output, true);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 400);
        }

        DB::transaction(function () use ($result) {
            foreach ($result as $groupName => $groupData) {
                $group = Group::firstOrCreate(['name' => $groupName]);

                foreach ($groupData['members'] as $memberData) {
                    GroupMember::create([
                        'group_id' => $group->id,
                        'member_id' => $memberData['id'],
                    ]);
                }
            }
        });

        return response()->json($result);
    }
}
