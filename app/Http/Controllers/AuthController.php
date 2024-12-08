<?php

namespace App\Http\Controllers;

use App\Http\Controllers\HelperController;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\DB;

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
    public function checkLogin(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        $user = User::where('username', $username)->first();

        if (!$user) {
            try {
                DB::beginTransaction();
                $bearerToken = HelperController::getAccessToken($username, $password);
                $studentData = HelperController::getStudentData($bearerToken);

                // Kiểm tra xem môn học CSE414 đã tồn tại chưa
                $subject = Subject::where('subject_code', 'CSE414')->first();

                // Chỉ gọi saveSubjectData nếu môn học chưa tồn tại
                if (!$subject) {
                    HelperController::saveSubjectData($bearerToken);
                    $subject = Subject::where('subject_code', 'CSE414')->first();
                }

                $user = User::create([
                    'username' => $username,
                    'password' => $password
                ]);

                $birthdate = \DateTime::createFromFormat('d/m/Y', $studentData['student']['birthDateString'])
                    ->format('Y-m-d');

                $member = $user->member()->create([
                    'name' => $studentData['student']['displayName'],
                    'phone' => $studentData['student']['phoneNumber'],
                    'birthdate' => $birthdate,
                    'email' => $studentData['student']['user']['email'],
                    'class' => $studentData['student']['enrollmentClass']['className'],
                ]);

                // Tạo bản ghi member_schedules
                $schedules = Schedule::where('subject_id', $subject->id)->get();
                foreach ($schedules as $schedule) {
                    $member->memberSchedules()->create([
                        'schedule_id' => $schedule->id
                    ]);
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
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
