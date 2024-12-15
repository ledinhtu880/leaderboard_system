<?php

namespace App\Http\Controllers;

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
    public static function getDataFromSuperset(): array
    {
        $response = Http::get('http://localhost:8088/api/v1/chart/231/data/');
        if (!$response->successful()) {
            throw new Exception($response->json('message'));
        }

        $data = $response->json();

        return $data["result"][0]["data"];
    }
    public static function getDataFromSheet(): array
    {
        $response = Http::get('https://sheetdb.io/api/v1/6kos5lsn1o82d');
        if (!$response->successful()) {
            throw new Exception($response->json('message'));
        }

        $data = $response->json();

        // Filter out elements with NULL 'STT'
        $filteredData = array_filter($data, function ($each) {
            return !($each['STT'] == "");
        });

        return $filteredData;
    }
    /* public static function rankingStudent($data): array
    {
        usort($data, function ($a, $b) {
            if ($a['Điểm tổng'] != $b['Điểm tổng']) {
                return $b['Điểm tổng'] <=> $a['Điểm tổng'];
            }
            if ($a['Điểm chuyên cần'] != $b['Điểm chuyên cần']) {
                return $b['Điểm chuyên cần'] <=> $a['Điểm chuyên cần'];
            }
            if ($a['Điểm phát biểu'] != $b['Điểm phát biểu']) {
                return $b['Điểm phát biểu'] <=> $a['Điểm phát biểu'];
            }
            if ($a['Tên'] != $b['Tên']) {
                return strcmp($a['Tên'], $b['Tên']);
            }
            return strcmp($a['Họ'], $b['Họ']);
        });

        $data = array_map(function ($index, $member) {
            $member['ranking'] = $index + 1;
            return $member;
        }, array_keys($data), $data);
        return $data;
    } */
    public static function rankingStudent($data): array
    {
        usort($data, function ($a, $b) {
            if ($a['Điểm tổng'] != $b['Điểm tổng']) {
                return $b['Điểm tổng'] <=> $a['Điểm tổng'];
            }
            if ($a['Điểm chuyên cần'] != $b['Điểm chuyên cần']) {
                return $b['Điểm chuyên cần'] <=> $a['Điểm chuyên cần'];
            }
            if ($a['Điểm phát biểu'] != $b['Điểm phát biểu']) {
                return $b['Điểm phát biểu'] <=> $a['Điểm phát biểu'];
            }
            return 0; // Equal ranking for tied scores
        });

        $rank = 1;
        $prevScore = null;
        $sameRankCount = 0;

        $data = array_map(function ($member) use (&$rank, &$prevScore, &$sameRankCount) {
            $currentScore = [
                'total' => $member['Điểm tổng'],
                'attendance' => $member['Điểm chuyên cần'],
                'speech' => $member['Điểm phát biểu']
            ];

            if ($prevScore !== null) {
                if (
                    $currentScore['total'] === $prevScore['total'] &&
                    $currentScore['attendance'] === $prevScore['attendance'] &&
                    $currentScore['speech'] === $prevScore['speech']
                ) {
                    $sameRankCount++;
                } else {
                    // Next rank should be current rank + 1
                    $rank = $rank + 1;
                    $sameRankCount = 0;
                }
            }

            $member['ranking'] = $rank;
            $prevScore = $currentScore;
            return $member;
        }, $data);

        usort($data, function ($a, $b) {
            if ($a['ranking'] !== $b['ranking']) {
                return $a['ranking'] - $b['ranking'];
            }
            return $a['STT'] - $b['STT'];
        });

        return $data;
    }
    public static function getRankingById($msv)
    {
        $data = HelperController::getDataFromSuperset();
        $members = HelperController::rankingStudent($data);
        $student = array_filter($members, function ($each) use ($msv) {
            return $each['Mã sinh viên'] == $msv;
        });
        $student = array_values($student)[0] ?? [];
        return $student['ranking'];
    }
    public static function getMarkByStudentId($msv): array
    {
        $studentData = HelperController::getDataFromSuperset();
        $student = array_filter($studentData, function ($each) use ($msv) {
            return $each['Mã sinh viên'] == $msv;
        });

        $student = array_values($student)[0] ?? [];

        return [
            'Điểm chuyên cần' => $student['Điểm chuyên cần'],
            'Điểm phát biểu' => $student['Điểm phát biểu'],
            'Điểm tổng' => $student['Điểm tổng'],
            'Phát biểu' => $student['Phát biểu'],
            'Vắng' => $student['Vắng'],
            'Điểm project' => $student['Điểm project'],
        ];
    }
}
