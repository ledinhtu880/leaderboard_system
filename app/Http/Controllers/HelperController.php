<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
        $response = Http::get('localhost:8088/api/v1/chart/231/data/');
        if (!$response->successful()) {
            throw new Exception($response->json('message'));
        }

        $data = $response->json();

        return $data["result"][0]["data"];
    }
    public static function rankingStudent($data): array
    {
        // First, sort by scores
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
            return 0;
        });

        // Separate students into two groups: normal and excessive absences
        $normalStudents = array_filter($data, function ($student) {
            return $student['Vắng'] <= 3;
        });

        $excessiveAbsences = array_filter($data, function ($student) {
            return $student['Vắng'] > 3;
        });

        // Rank normal students
        $rank = 1;
        $prevScore = null;
        $sameRankCount = 0;

        $normalStudents = array_values($normalStudents); // Reset array keys
        $normalStudents = array_map(function ($member) use (&$rank, &$prevScore, &$sameRankCount) {
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
                    $rank = $rank + 1;
                    $sameRankCount = 0;
                }
            }

            $member['ranking'] = $rank;
            $prevScore = $currentScore;
            return $member;
        }, $normalStudents);

        // Rank excessive absence students (they will be placed after normal students)
        $lastRank = $rank + 1;
        $excessiveAbsences = array_values($excessiveAbsences); // Reset array keys
        $excessiveAbsences = array_map(function ($member) use ($lastRank) {
            $member['ranking'] = $lastRank;
            return $member;
        }, $excessiveAbsences);

        // Merge both groups
        $data = array_merge($normalStudents, $excessiveAbsences);

        // Final sort by ranking and STT
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
    public static function forceRefreshChart()
    {
        $url = "http://localhost:8088/api/v1/chart/data";

        $payload = [
            "datasource" => [
                "id" => 48,
                "type" => "table"
            ],
            "force" => false,
            "queries" => [
                [
                    "filters" => [],
                    "extras" => [
                        "having" => "",
                        "where" => ""
                    ],
                    "columns" => [
                        "STT",
                        "Mã sinh viên",
                        "Họ",
                        "Tên",
                        "Lớp",
                        "Vắng",
                        "Phát biểu",
                        "Điểm project",
                        "Điểm chuyên cần",
                        "Điểm phát biểu",
                        "Điểm tổng",
                        "Những ngày vắng",
                        "Những ngày phát biểu"
                    ],
                    "metrics" => [],
                    "orderby" => [],
                    "annotation_layers" => [],
                    "row_limit" => 100,
                    "series_limit" => 0,
                    "order_desc" => true,
                    "url_params" => [
                        "form_data_key" => "uyD_pp_9fNDYVsQbGrIRV7wH9f8-QjfucRsw8HfBn6dwhdWny7rhdX-FXo4xgMaC",
                        "slice_id" => "231"
                    ],
                    "custom_params" => [],
                    "custom_form_data" => [],
                    "post_processing" => [],
                    "time_offsets" => []
                ]
            ],
            "form_data" => [
                "datasource" => "48__table",
                "viz_type" => "table",
                "slice_id" => 231,
                "url_params" => [
                    "form_data_key" => "uyD_pp_9fNDYVsQbGrIRV7wH9f8-QjfucRsw8HfBn6dwhdWny7rhdX-FXo4xgMaC",
                    "slice_id" => "231"
                ],
                "query_mode" => "aggregate",
                "groupby" => [
                    "STT",
                    "Mã sinh viên",
                    "Họ",
                    "Tên",
                    "Lớp",
                    "Vắng",
                    "Phát biểu",
                    "Điểm project",
                    "Điểm chuyên cần",
                    "Điểm phát biểu",
                    "Điểm tổng",
                    "Những ngày vắng",
                    "Những ngày phát biểu"
                ],
                "temporal_columns_lookup" => [],
                "metrics" => [],
                "all_columns" => [],
                "percent_metrics" => [],
                "adhoc_filters" => [],
                "order_by_cols" => [],
                "row_limit" => 100,
                "server_page_length" => 10,
                "order_desc" => true,
                "table_timestamp_format" => "smart_date",
                "allow_render_html" => true,
                "column_config" => [
                    "Mã sinh viên" => [
                        "horizontalAlign" => "center",
                        "truncateLongCells" => false
                    ],
                    "Điểm chuyên cần" => [
                        "alignPositiveNegative" => false,
                        "colorPositiveNegative" => false,
                        "columnWidth" => 0,
                        "horizontalAlign" => "right",
                        "showCellBars" => false
                    ]
                ],
                "show_cell_bars" => false,
                "align_pn" => false,
                "color_pn" => false,
                "comparison_color_scheme" => "Green",
                "conditional_formatting" => [],
                "comparison_type" => "values",
                "extra_form_data" => [],
                "force" => false,
                "result_format" => "json",
                "result_type" => "full"
            ],
            "result_format" => "json",
            "result_type" => "full"
        ];

        $response = Http::post($url, $payload);

        if ($response->successful()) {
            Log::info('Successfully refreshed the chart');
            return;
        }
        Log::error('Failed to refresh the chart', [
            'response' => $response->body(),
        ]);
    }
}
