<?php

namespace App\Http\Controllers;

use App\Http\Controllers\HelperController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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
        try {
            $username = $request->username;
            $password = $request->password;
            $bearerToken = HelperController::getAccessToken($username, $password);
            $studentData = HelperController::getStudentData($bearerToken);

            $studentName = $studentData['student']['displayName'];
            $nameArray = explode(' ', $studentName);
            $firstName = end($nameArray);
            $firstCharacter = substr($firstName, 0, 1);

            $studentClass = $studentData['student']['enrollmentClass']['className'];
            $studentEmail = $studentData['student']['user']['email'];
            $studentGpa = $studentData['learningMark'];
            $studentBirthdate = $studentData['student']['birthDateString'];

            $request->session()->put([
                'auth' => true,
                'name' => $studentName,
                'username' => $username,
                'phone' => $studentData['student']['phoneNumber'],
                'class' => $studentClass,
                'email' => $studentEmail,
                'gpa' => $studentGpa,
                'birthdate' => $studentBirthdate,
                'firstCharacter' => $firstCharacter,
            ]);

            return response()->json([
                'status' => 'success',
                'url' => redirect()->intended('')
                    ->with('type', 'success')
                    ->with('message', 'Đăng nhập thành công')->getTargetUrl(),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'danger',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function logout()
    {
        session()->flush();
        return redirect()->route('leaderboard')->with('type', 'success')->with('message', 'Đăng xuất thành công');
    }
}
