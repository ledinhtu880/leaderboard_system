<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Lesson;
use App\Models\Room;
use Carbon\Carbon;
use Exception;

class HelperController extends Controller
{

    public static function getAccessToken(string $username, string $password): string
    {
        $response = Http::post('http://sinhvien1.tlu.edu.vn/education/oauth/token', [
            'username' => $username,
            'password' => $password,
            'client_id' => 'education_client',
            'client_secret' => 'password',
            'grant_type' => 'password',
        ]);

        if (!$response->successful()) {
            throw new Exception('Mã sinh viên hoặc mật khẩu không chính xác');
        }

        return $response->json('access_token');
    }
    public static function getStudentData(string $bearerToken): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $bearerToken
        ])->get('http://sinhvien1.tlu.edu.vn/education/api/studentsummarymark/getbystudent');

        if (!$response->successful()) {
            throw new Exception('Không thể lấy thông tin người dùng');
        }

        return $response->json();
    }
    public static function convertTimestampToDateTime($timestamp): string
    {
        return $timestamp ? date('Y-m-d H:i:s', $timestamp / 1000) : null;
    }
    public static function saveSubjectData(string $bearerToken)
    {
        try {
            DB::beginTransaction();
            $subjectData = HelperController::processSubjectData($bearerToken);

            $subject = Subject::updateOrCreate(
                ['subject_code' => $subjectData['subject']['subject_code']],
                [
                    'subject_name' => $subjectData['subject']['subject_name'],
                ]
            );

            foreach ($subjectData['timetables'] as $timetable) {
                $teacher = Teacher::updateOrCreate(
                    ['display_name' => $timetable['teacher']['displayName']]
                );

                $room = Room::updateOrCreate(
                    ['name' => $timetable['room']['name']]
                );
                $schedule = Schedule::create([
                    'subject_id' => $subject->id,
                    'teacher_id' => $teacher->id,
                    'room_id' => $room->id,
                    'week_index' => $timetable['weekIndex'],
                    'start_week' => $timetable['fromWeek'],
                    'end_week' => $timetable['toWeek'],
                ]);

                $startDate = Carbon::parse(HelperController::convertTimestampToDateTime($timetable['startDate']));
                $endDate = Carbon::parse(HelperController::convertTimestampToDateTime($timetable['endDate']));

                $currentDate = $startDate->copy();
                while ($currentDate <= $endDate) {
                    $lessonDate = $currentDate->copy()->startOfWeek()->addDays($schedule->week_index == 3 ? 1 : 4);

                    Lesson::create([
                        'schedule_id' => $schedule->id,
                        'lesson_date' => $lessonDate->format('Y-m-d'),
                        'start_time' => $timetable['startHour']['startString'],
                        'end_time' => $timetable['endHour']['endString'],
                    ]);
                    $currentDate->addWeek();
                }
            }

            DB::commit();
            return response()->json(['message' => 'Lưu dữ liệu môn học thành công']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json([
                'message' => 'Lỗi khi lưu dữ liệu môn học',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public static function processSubjectData(string $bearerToken): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $bearerToken
        ])->get('http://sinhvien1.tlu.edu.vn/education/api/StudentCourseSubject/studentLoginUser/11');

        if (!$response->successful()) {
            throw new Exception('Không thể lấy thông tin môn học');
        }

        $subjects = $response->json();

        $targetSubject = collect($subjects)->first(function ($subject) {
            return $subject['courseSubject']['semesterSubject']['subject']['subjectCode'] === 'CSE414';
        });

        if (!$targetSubject) {
            throw new Exception('Không tìm thấy môn học CSE414');
        }

        return [
            'subject' => [
                'subject_code' => $targetSubject['courseSubject']['semesterSubject']['subject']['subjectCode'],
                'subject_name' => $targetSubject['courseSubject']['semesterSubject']['subject']['subjectName'],
                'subject_name_eng' => $targetSubject['courseSubject']['semesterSubject']['subject']['subjectNameEng'],
                'number_of_credits' => $targetSubject['numberOfCredit']
            ],
            'course_subject' => [
                'display_name' => $targetSubject['courseSubject']['displayName'],
                'course_year_code' => $targetSubject['courseSubject']['courseYearCode'],
                'status' => $targetSubject['courseSubject']['status']
            ],
            'timetables' => $targetSubject['courseSubject']['timetables']
        ];
    }
}
