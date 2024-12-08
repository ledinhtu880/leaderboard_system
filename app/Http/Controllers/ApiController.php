<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\AttendanceSession;
use App\Models\Lesson;
use Carbon\Carbon;

class ApiController extends Controller
{
    public function getLessons()
    {
        $today = Carbon::today(); // Lấy ngày hiện tại (không tính giờ)

        $data = Lesson::with('subject', 'teacher') // Tải kèm quan hệ
            ->where('lesson_date', '>', $today)   // Chỉ lấy các lesson có lessonDate sau hôm nay
            ->get();
        return response()->json(data: ['lessons' => $data]);
    }
    public function storeAttendanceSession(Request $request)
    {
        $validatedData = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'password' => 'nullable|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        try {
            DB::beginTransaction();

            // Lấy bài học để lấy ngày
            $lesson = Lesson::findOrFail($validatedData['lesson_id']);

            // Kiểm tra xem đã có phiên điểm danh mở cho bài học này chưa
            $existingSession = AttendanceSession::where('lesson_id', $validatedData['lesson_id'])
                ->where('is_open', true)
                ->first();

            if ($existingSession) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Đã tồn tại phiên điểm danh đang mở cho bài học này'
                ]);
            }

            $lessonDate = Carbon::parse($lesson->lesson_date)->format('Y-m-d');
            $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $lessonDate . ' ' . trim($validatedData['start_time']));
            $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $lessonDate . ' ' . trim($validatedData['end_time']));
            $lessonStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $lessonDate . ' ' . trim($lesson->start_time));
            $lessonEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $lessonDate . ' ' . trim($lesson->end_time));

            // Kiểm tra thời gian hợp lệ
            if (
                $startDateTime->lt($lessonStartTime->subMinutes(5)) ||
                $endDateTime->gt($lessonEndTime)
            ) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Thời gian phiên điểm danh không hợp lệ',
                ]);
            }

            // Tạo phiên điểm danh mới
            AttendanceSession::create([
                'lesson_id' => $validatedData['lesson_id'],
                'teacher_id' => $validatedData['teacher_id'],
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'password' => $validatedData['password'] ? bcrypt($validatedData['password']) : null,
                'is_open' => true
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Tạo phiên điểm danh thành công',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi tạo phiên điểm danh',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function closeAttendanceSession($sessionId)
    {
        try {
            $session = AttendanceSession::findOrFail($sessionId);

            // Chỉ cho phép đóng phiên đang mở
            if (!$session->is_open) {
                return response()->json([
                    'message' => 'Phiên điểm danh đã được đóng'
                ], 400);
            }

            $session->update([
                'is_open' => false
            ]);

            return response()->json([
                'message' => 'Đóng phiên điểm danh thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi đóng phiên điểm danh',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
