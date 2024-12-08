<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (env(key: 'APP_ENV') !== 'local') {
            URL::forceScheme(scheme: 'https');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Đặt múi giờ cho toàn bộ ứng dụng
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        // Hoặc, nếu bạn đang sử dụng Carbon (Carbon được Laravel sử dụng cho ngày giờ)
        Carbon::setLocale('vi');
    }
}
