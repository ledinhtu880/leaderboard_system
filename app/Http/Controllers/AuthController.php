<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->intended('');
        } else {
            return view('login');
        }
    }
    public function register()
    {

        if (Auth::check()) {
            return redirect()->intended('');
        } else {
            return view('register');
        }
    }
    public function handleRegister(Request $request)
    {
        if ($request->ajax()) {
            $username = $request->username;
            $password = $request->password;

            if (User::where('username', $username)->exists()) {
                return response()->json(['status' => 'danger', 'message' => 'Tài khoản đã tồn tại']);
            }

            try {
                DB::beginTransaction();
                $bearerToken = $this->getAccessToken($username, $password);
                $studentData = $this->getStudentData($bearerToken);

                $user = User::create([
                    'username' => $username,
                    'password' => $password,
                ]);

                $user->member()->create([
                    'name' => $studentData['student']['displayName'],
                    'gpa' => $studentData['learningMark'],
                    'last_gpa' => $this->getLastGpa($studentData),
                    'final_score' => 8,
                    'personality' => 0,
                    'hobby' => 'Chơi game',
                ]);

                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Đăng ký thành công', 'data' => $studentData]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['status' => 'danger', 'message' => $e->getMessage()]);
            }
        }
    }
    protected function getAccessToken(string $username, string $password): string
    {
        $response = Http::post('http://sinhvien1.tlu.edu.vn/education/oauth/token', [
            'username' => $username,
            'password' => $password,
            'client_id' => 'education_client',
            'client_secret' => 'password',
            'grant_type' => 'password',
        ]);

        if (!$response->successful()) {
            throw new Exception('Sai mã sinh viên hoặc mật khẩu');
        }

        return $response->json('access_token');
    }
    protected function getStudentData(string $bearerToken): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $bearerToken
        ])->get('http://sinhvien1.tlu.edu.vn/education/api/studentsummarymark/getbystudent');

        if (!$response->successful()) {
            throw new Exception('Không thể lấy thông tin người dùng');
        }

        return $response->json();
    }

    protected function getLastGpa(array $data): float
    {
        foreach ($data['schoolYearSummaryMarks'] as $yearMark) {
            if (!empty($yearMark['semesterMarks'])) {
                foreach ($yearMark['semesterMarks'] as $semesterMark) {
                    if ($semesterMark['semester']['semesterCode'] === '2_2023_2024') {
                        return $semesterMark['learningMark'] ?? 0;
                    }
                }
            }
        }
        return 0;
    }

    public function checklogin(Request $request)
    {
        $user = User::where('Username', $request->username)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            $name = $user->member->name;
            $username = $user->username;
            $role = $user->role;
            $firstCharacter = $user->member->getFirstCharacter;
            $request->session()->put('name', $name);
            $request->session()->put('username', $username);
            $request->session()->put('role', $role);
            $request->session()->put('firstCharacter', $firstCharacter);
            return redirect()->intended('')
                ->with('type', 'success')
                ->with('message', 'Đăng nhập thành công');
        } else {
            return redirect()->back()->withInput()->with('type', 'warning')->with('message', 'Tên đăng nhập hoặc mật khẩu không chính xác');
        }
    }
    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('login')->with('type', 'success')->with('message', 'Đăng xuất thành công');
    }
}
