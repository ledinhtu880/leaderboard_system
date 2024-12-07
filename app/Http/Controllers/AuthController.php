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
            throw new Exception('Mã sinh viên hoặc mật khẩu không chính xác');
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
    public function checkLogin(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        $user = User::where('username', $username)->first();

        if (!$user) {
            try {
                $bearerToken = $this->getAccessToken($username, $password);
                $studentData = $this->getStudentData($bearerToken);

                $user = User::create([
                    'username' => $username,
                    'password' => $password
                ]);

                $birthdate = \DateTime::createFromFormat('d/m/Y', $studentData['student']['birthDateString'])
                    ->format('Y-m-d');

                $user->member()->create([
                    'name' => $studentData['student']['displayName'],
                    'phone' => $studentData['student']['phoneNumber'],
                    'birthdate' => $birthdate,
                    'email' => $studentData['student']['user']['email'],
                    'class' => $studentData['student']['enrollmentClass']['className'],
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'danger',
                    'message' => $e->getMessage(),
                ]);
            }
        }

        if (Hash::check($password, $user->password)) {
            Auth::login($user);

            $request->session()->put([
                'name' => $user->member->name,
                'username' => $user->username,
                'role' => $user->role,
                'firstCharacter' => $user->member->getFirstCharacter,
            ]);

            return response()->json([
                'status' => 'success',
                'url' => redirect()->intended('')
                    ->with('type', 'success')
                    ->with('message', 'Đăng nhập thành công')->getTargetUrl(),
            ]);
        } else {
            return redirect()->back()
                ->withInput()
                ->with('type', 'warning')
                ->with('message', 'Mã sinh viên hoặc mật khẩu không chính xác');
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('login')->with('type', 'success')->with('message', 'Đăng xuất thành công');
    }
}
