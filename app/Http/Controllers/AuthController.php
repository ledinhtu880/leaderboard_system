<?php

namespace App\Http\Controllers;

use App\Http\Controllers\HelperController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\User;
use Exception;

class AuthController extends Controller
{
    public function login()
    {
        // dd(HelperController::getDataFromSheet());
        if (Auth::check()) {
            return redirect()->intended('');
        } else {
            return view('auth.login');
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
                $user = User::create([
                    'username' => $username,
                    'password' => $password
                ]);

                $sheetsData = HelperController::getDataFromSheet();
                if (Member::count() != 59) {
                    foreach ($sheetsData as $each) {
                        Member::create([
                            'name' => $each['Họ'] . ' ' . $each['Tên'],
                            'msv' => $each["Mã sinh viên"],
                            'absence_count' => $each['Vắng'],
                            'raisehand_count' => $each['Phát biểu'],
                            'class' => $each['Lớp'],
                        ]);
                    }
                }
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e->getMessage());
                return response()->json([
                    'status' => 'danger',
                    'message' => $e->getMessage(),
                ]);
            }
        }

        if (Hash::check($password, $user->password)) {
            Auth::login($user);
            $bearerToken = HelperController::getAccessToken($username, $password);
            $studentData = HelperController::getStudentData($bearerToken);
            $birthdate = \DateTime::createFromFormat('d/m/Y', $studentData['student']['birthDateString'])
                ->format('Y-m-d');

            if ($user->member->name == null) {
                Member::where('msv', $username)->update([
                    'name' => $studentData['student']['displayName'],
                    'msv' => $username,
                    'phone' => $studentData['student']['phoneNumber'],
                    'birthdate' => $birthdate,
                    'email' => $studentData['student']['user']['email'],
                    'class' => $studentData['student']['enrollmentClass']['className'],
                    'gpa' => $studentData['learningMark'],
                    'user_id' => $user->id,
                ]);
            }

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
            return response()->json(['status' => 'warning', 'message' => 'Mã sinh viên hoặc mật khẩu không chính xác']);
        }
    }
    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('login')->with('type', 'success')->with('message', 'Đăng xuất thành công');
    }
}
