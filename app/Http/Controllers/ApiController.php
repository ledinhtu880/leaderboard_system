<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\MemberTopic;
use App\Models\User;

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
    public function storeTopic(Request $request)
    {
        if ($request->ajax()) {
            $username = $request->input('username');
            $user = User::where('username', $username)->first();
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
    }

    public function runClustering()
    {
        $pythonScript = base_path('python/cluster_groups.py');
        $output = shell_exec("python $pythonScript");
        $result = json_decode($output, true);

        return response()->json($result);
    }
    public function updateTopics(Request $request)
    {
        DB::beginTransaction();
        try {
            $topicGroups = $request->input('topic_groups');

            // Check for locked members
            $lockedMembers = MemberTopic::where('status', 'locked')->pluck('member_id');

            // Collect all members from the new assignment
            $newAssignmentMembers = collect($topicGroups)
                ->flatMap(fn($topic) => $topic['members'])
                ->unique();

            // Check if any locked members are in the new assignment
            $conflictingMembers = $lockedMembers->intersect($newAssignmentMembers);

            if ($conflictingMembers->isNotEmpty()) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Không thể cập nhật. Danh sách đã bị khóa.',
                ]);
            }

            // Clear non-locked member_topics
            MemberTopic::where('status', '!=', 'locked')->delete();

            // Process each topic group
            foreach ($topicGroups as $topicId => $topicData) {
                foreach ($topicData['members'] as $memberId) {
                    MemberTopic::create([
                        'member_id' => $memberId,
                        'topic_id' => $topicId,
                        'status' => 'assigned'
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật nhóm thành công'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
