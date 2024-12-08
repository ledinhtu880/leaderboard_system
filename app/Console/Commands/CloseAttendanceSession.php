<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\AttendanceSession;
use Carbon\Carbon;

class CloseAttendanceSession extends Command
{
    // Tên lệnh
    protected $signature = 'attendance:close-sessions';

    // Mô tả lệnh
    protected $description = 'Close timed roll call sessions';

    public function handle()
    {
        $now = Carbon::now(); // Lấy thời gian hiện tại

        // Tìm và cập nhật các phiên đã hết giờ
        $affectedRows = AttendanceSession::where('is_open', true)
            ->where('end_time', '<=', $now)
            ->update(['is_open' => false]);
        Log::info('I was here' . Carbon::now());

        $this->info("Closed $affectedRows roll call sessions.");
    }
}
