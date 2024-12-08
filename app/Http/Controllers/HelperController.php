<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Room;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\CourseSubject;
use Illuminate\Support\Facades\Http;
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

    public static function saveSubjectData(string $bearerToken)
    {
        try {
            DB::beginTransaction();
            $subjectData = HelperController::processSubjectData($bearerToken);

            // Lưu thông tin môn học
            $subject = Subject::updateOrCreate(
                ['subject_code' => $subjectData['subject']['subject_code']],
                [
                    'subject_name' => $subjectData['subject']['subject_name'],
                    'subject_name_eng' => $subjectData['subject']['subject_name_eng'],
                    'number_of_credits' => $subjectData['subject']['number_of_credits']
                ]
            );

            // Lưu thông tin khóa học
            $courseSubject = CourseSubject::create([
                'subject_id' => $subject->id,
                'display_name' => $subjectData['course_subject']['display_name'],
                'course_year_code' => $subjectData['course_subject']['course_year_code'],
                'status' => $subjectData['course_subject']['status']
            ]);

            // Lưu thông tin lịch học
            foreach ($subjectData['timetables'] as $timetable) {
                // Lưu giảng viên
                $teacher = Teacher::updateOrCreate(
                    ['display_name' => $timetable['teacher']['displayName']]
                );

                // Lưu phòng học
                $room = Room::updateOrCreate(
                    ['name' => $timetable['room']['name']]
                );

                // Lưu thông tin lịch học
                Schedule::create([
                    'course_subject_id' => $courseSubject->id,
                    'subject_id' => $subject->id,
                    'teacher_id' => $teacher->id,
                    'room_id' => $room->id,
                    'start_hour_name' => $timetable['startHour']['name'],
                    'start_hour_string' => $timetable['startHour']['startString'],
                    'end_hour_name' => $timetable['endHour']['name'],
                    'end_hour_string' => $timetable['endHour']['endString'],
                    'week_index' => $timetable['weekIndex'],
                    'from_week' => $timetable['fromWeek'],
                    'to_week' => $timetable['toWeek'],
                    'start_date' => HelperController::convertTimestampToDateTime($timetable['startDate']),
                    'end_date' => HelperController::convertTimestampToDateTime($timetable['endDate'])
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Lưu dữ liệu môn học thành công']);
        } catch (Exception $e) {
            DB::rollBack();
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
    public static function convertTimestampToDateTime($timestamp): string
    {
        return $timestamp ? date('Y-m-d H:i:s', $timestamp / 1000) : null;
    }
}
