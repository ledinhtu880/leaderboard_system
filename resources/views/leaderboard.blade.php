@extends('layouts.master')

@section('title', 'Bảng xếp hạng')

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <style>
        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            background-image: none;
        }

        .r-1 {
            color: rgb(181, 138, 27);
            font-weight: 700;
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, rgb(255, 247, 224) 0%, rgb(255, 215, 0) 100%);
            border: 1px solid rgba(212, 160, 23, 0.35);
            border-radius: 50%;
            font-size: 1rem;
            line-height: 1;
            box-shadow: rgba(212, 160, 23, 0.8) 1px 1px 0px;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .r-2 {
            color: rgb(102, 115, 128);
            font-weight: 700;
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, rgb(255, 255, 255) 0%, rgb(216, 227, 237) 100%);
            border: 1px solid rgba(124, 139, 153, 0.35);
            border-radius: 50%;
            font-size: 1rem;
            line-height: 1;
            box-shadow: rgba(124, 139, 153, 0.8) 1px 1px 0px;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .r-3 {
            color: rgb(184, 92, 47);
            font-weight: 700;
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, rgb(253, 240, 233) 0%, rgb(255, 188, 140) 100%);
            border: 1px solid rgba(204, 108, 61, 0.35);
            border-radius: 50%;
            font-size: 1rem;
            line-height: 1;
            box-shadow: rgba(204, 108, 61, 0.8) 1px 1px 0px;

            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endpush

@section('content')
    <!-- Modal -->
    <div id="cover-spin"></div>
    <div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="loginModalLabel">Đăng nhập</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="username" class="form-label">Tên đăng nhập</label>
                        <input type="text" class="form-control" id="username" name="username"
                            placeholder="Nhập tên đăng nhập" value="{{ old('username') }}">
                        <div class="error-message" id="username-error"></div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Nhập mật khẩu">
                        <div class="error-message" id="password-error"></div>
                    </div>
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Ghi nhớ đăng nhập</label>
                    </div>
                    <div class="d-grid">
                        <button id="btnLogin" class="btn btn-info btn-gradient">Đăng nhập</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 mb-3">
                    <h1 class="m-0">Bảng xếp hạng</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-lightblue card-outline">
                {{-- <div class="card-header">
                    <h2 class="card-title text-bold" style="font-size: 24px">Bảng xếp hạng</h2>
                </div> --}}
                <div class="card-body">
                    {{-- <div class="row">
                        <div class="col-md-4 mb-3 align-items-center justify-content-center d-flex">
                            <div class="card card-primary card-outline shadow">
                                <div
                                    class="card-header d-flex align-items-center justify-content-center flex-column border-0">
                                    <div class="position-relative">
                                        <h2>🥈</h2>
                                    </div>
                                    <h3 class="card-info">{{ $secondPlace['Họ'] . ' ' . $secondPlace['Tên'] }}</h3>
                                    <h5 class="card-title">{{ $secondPlace['Lớp'] }}</h5>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="row">
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="badge rounded-pill badge-info p-2">
                                                    <h6 class="m-0">Điểm chuyên cần:
                                                        {{ $secondPlace['Điểm chuyên cần'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="badge rounded-pill badge-primary p-2">
                                                    <h6 class="m-0">Điểm phát biểu:
                                                        {{ $secondPlace['Điểm phát biểu'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="badge rounded-pill badge-success p-2">
                                                    <h6 class="m-0">Điểm tổng: {{ $secondPlace['Điểm tổng'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-5 align-items-center justify-content-center d-flex">
                            <div class="card card-danger card-outline shadow">
                                <div
                                    class="card-header d-flex align-items-center justify-content-center flex-column border-0">
                                    <div class="position-relative">
                                        <h1>🥇</h1>
                                    </div>
                                    <h3 class="card-info">{{ $firstPlace['Họ'] . ' ' . $firstPlace['Tên'] }}</h3>
                                    <h5 class="card-title">{{ $firstPlace['Lớp'] }}</h5>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="row">
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="badge rounded-pill badge-info p-2">
                                                    <h6 class="m-0">Điểm chuyên cần:
                                                        {{ $firstPlace['Điểm chuyên cần'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="badge rounded-pill badge-primary p-2">
                                                    <h6 class="m-0">Điểm phát biểu:
                                                        {{ $firstPlace['Điểm phát biểu'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="badge rounded-pill badge-success p-2">
                                                    <h6 class="m-0">Điểm tổng: {{ $firstPlace['Điểm tổng'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 align-items-center justify-content-center d-flex">
                            <div class="card card-success card-outline shadow">
                                <div
                                    class="card-header d-flex align-items-center justify-content-center flex-column border-0">
                                    <div class="position-relative">
                                        <h2>🥉</h2>
                                    </div>
                                    <h3 class="card-info">{{ $thirdPlace['Họ'] . ' ' . $thirdPlace['Tên'] }}</h3>
                                    <h5 class="card-title">{{ $thirdPlace['Lớp'] }}</h5>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="row">
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="badge rounded-pill badge-info p-2">
                                                    <h6 class="m-0">Điểm chuyên cần:
                                                        {{ $thirdPlace['Điểm chuyên cần'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="badge rounded-pill badge-primary p-2">
                                                    <h6 class="m-0">Điểm phát biểu:
                                                        {{ $thirdPlace['Điểm phát biểu'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="badge rounded-pill badge-success p-2">
                                                    <h6 class="m-0">Điểm tổng: {{ $thirdPlace['Điểm tổng'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="membersTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Thứ hạng</th>
                                            <th scope="col">Họ và tên</th>
                                            <th scope="col">Mã sinh viên</th>
                                            <th scope="col">Lớp</th>
                                            <th scope="col">Điểm project</th>
                                            <th scope="col">Điểm chuyên cần</th>
                                            <th scope="col">Điểm phát biểu</th>
                                            <th scope="col">Điểm tổng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($members as $each)
                                            @if ($each['ranking'] == 1)
                                                <tr>
                                                    <td style="background-color: #FCFFC1 !important;"
                                                        class="d-flex align-items-center justify-content-center">
                                                        <span class="r-1">{{ $each['ranking'] }}</span>
                                                    </td>
                                                    <td style="background-color: #FCFFC1 !important;">
                                                        {{ $each['Họ'] . ' ' . $each['Tên'] }}</td>
                                                    <td style="background-color: #FCFFC1 !important;">
                                                        {{ $each['Mã sinh viên'] }}</td>
                                                    <td style="background-color: #FCFFC1 !important;">{{ $each['Lớp'] }}
                                                    </td>
                                                    <td style="background-color: #FCFFC1 !important;">
                                                        {{ $each['Điểm project'] }}</td>
                                                    <td style="background-color: #FCFFC1 !important;">
                                                        {{ $each['Điểm chuyên cần'] }}</td>
                                                    <td style="background-color: #FCFFC1 !important;">
                                                        {{ $each['Điểm phát biểu'] }}</td>
                                                    <td style="background-color: #FCFFC1 !important;">
                                                        {{ $each['Điểm tổng'] }}</td>
                                                </tr>
                                            @elseif($each['ranking'] == 2)
                                                <tr>
                                                    <td style="background-color: #F2F9FF !important;"
                                                        class="d-flex align-items-center justify-content-center">
                                                        <span class="r-2">{{ $each['ranking'] }}</span>
                                                    </td>
                                                    <td style="background-color: #F2F9FF !important;">
                                                        {{ $each['Họ'] . ' ' . $each['Tên'] }}</td>
                                                    <td style="background-color: #F2F9FF !important;">
                                                        {{ $each['Mã sinh viên'] }}</td>
                                                    <td style="background-color: #F2F9FF !important;">{{ $each['Lớp'] }}
                                                    </td>
                                                    <td style="background-color: #F2F9FF !important;">
                                                        {{ $each['Điểm project'] }}</td>
                                                    <td style="background-color: #F2F9FF !important;">
                                                        {{ $each['Điểm chuyên cần'] }}</td>
                                                    <td style="background-color: #F2F9FF !important;">
                                                        {{ $each['Điểm phát biểu'] }}</td>
                                                    <td style="background-color: #F2F9FF !important;">
                                                        {{ $each['Điểm tổng'] }}</td>
                                                </tr>
                                            @elseif($each['ranking'] == 3)
                                                <tr>
                                                    <td
                                                        style="background-color: #FFF0DC !important;"class="d-flex align-items-center justify-content-center">
                                                        <span class="r-3">{{ $each['ranking'] }}</span>
                                                    </td>
                                                    <td style="background-color: #FFF0DC !important;">
                                                        {{ $each['Họ'] . ' ' . $each['Tên'] }}</td>
                                                    <td style="background-color: #FFF0DC !important;">
                                                        {{ $each['Mã sinh viên'] }}</td>
                                                    <td style="background-color: #FFF0DC !important;">{{ $each['Lớp'] }}
                                                    </td>
                                                    <td style="background-color: #FFF0DC !important;">
                                                        {{ $each['Điểm project'] }}</td>
                                                    <td style="background-color: #FFF0DC !important;">
                                                        {{ $each['Điểm chuyên cần'] }}</td>
                                                    <td style="background-color: #FFF0DC !important;">
                                                        {{ $each['Điểm phát biểu'] }}</td>
                                                    <td style="background-color: #FFF0DC !important;">
                                                        {{ $each['Điểm tổng'] }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td class="d-flex align-items-center justify-content-center">
                                                        <span>{{ $each['ranking'] }}</span>
                                                    </td>
                                                    <td>{{ $each['Họ'] . ' ' . $each['Tên'] }}</td>
                                                    <td>{{ $each['Mã sinh viên'] }}</td>
                                                    <td>{{ $each['Lớp'] }}</td>
                                                    <td>{{ $each['Điểm project'] }}</td>
                                                    <td>{{ $each['Điểm chuyên cần'] }}</td>
                                                    <td>{{ $each['Điểm phát biểu'] }}</td>
                                                    <td>{{ $each['Điểm tổng'] }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@push('js')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Khởi tạo DataTable
            var table = $('#membersTable').DataTable({
                pageLength: 100,
                scrollY: '700px',
                scrollCollapse: true,
                paging: false,
                searching: true,
                autoWidth: false,
                responsive: true,
                ordering: false, // Tắt sorting
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/vi.json'
                },
            });

            $('#sidebar-toggle-button').on('click', function() {
                setTimeout(() => {
                    table.columns.adjust().draw(false); // Cập nhật lại table
                }, 0); // Đợi animation của sidebar kết thúc
            });


            const message = '{{ session('message') }}';
            const type = '{{ session('type') }}';

            if (message && type) {
                showToast(message, type);
            }

            const usernamePattern = /^[a-zA-Z0-9]{6,20}$/;
            const passwordPattern = /^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]{6,20}$/;

            $('#username').on('input', function() {
                hideError($(this).next());
            });

            $('#password').on('input', function() {
                hideError($(this).next());
            });

            $('#loginModal').on('shown.bs.modal', function() {
                $(document).on('keypress.login', function(e) {
                    if (e.which == 13) {
                        $('#btnLogin').click();
                    }
                });
            });

            // Remove event when modal hides
            $('#loginModal').on('hidden.bs.modal', function() {
                $(document).off('keypress.login');
            });

            $('#btnLogin').on('click', function(e) {
                const isUsernameValid = validateUsername();
                const isPasswordValid = validatePassword();

                if (isUsernameValid && isPasswordValid) {
                    $("#cover-spin").show();
                    $.ajax({
                        type: 'POST',
                        url: "/checkLogin",
                        data: {
                            username: $('#username').val(),
                            password: $('#password').val(),
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                window.location.href = response.url
                            } else {
                                showToast(response.message, response.status);
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            alert("Có lỗi xảy ra. Vui lòng thử lại sau.");
                        },
                        complete: function() {
                            $("#cover-spin").hide();
                        }
                    });
                }
            });

            function validateUsername() {
                const username = $('#username').val();
                const errorElement = $('#username-error');

                if (!username) {
                    showError(errorElement, 'Vui lòng nhập tên đăng nhập');
                    return false;
                }

                if (!usernamePattern.test(username)) {
                    showError(errorElement, 'Tên đăng nhập phải từ 6-20 ký tự và chỉ chứa chữ cái hoặc số');
                    return false;
                }

                hideError(errorElement);
                return true;
            }

            function validatePassword() {
                const password = $('#password').val();
                const errorElement = $('#password-error');

                if (!password) {
                    showError(errorElement, 'Vui lòng nhập mật khẩu');
                    return false;
                }

                if (!passwordPattern.test(password)) {
                    showError(errorElement, 'Tên đăng nhập phải từ 6-20 ký tự và chỉ chứa chữ cái hoặc số');
                    return false;
                }

                hideError(errorElement);
                return true;
            }

            function showError(element, message) {
                element.text(message).show();
                element.prev('input').addClass('is-invalid');
            }

            function hideError(element) {
                element.hide();
                element.prev('input').removeClass('is-invalid');
            }
        });
    </script>
@endpush
