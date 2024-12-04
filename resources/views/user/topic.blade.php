@extends('layouts.master')

@section('title', 'Trang chủ')

@push('css')
    <style>
        .topic-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .topic-card:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .topic-card.selected {
            border: 2px solid #007bff;
            background-color: #f8f9fa;
        }
    </style>
@endpush

@section('content')
    <!-- Content header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Chọn đề tài</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Chọn đề tài</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @foreach ($topics as $each)
                    <div class="col-md-6 mb-4">
                        <div class="card topic-card shadow-sm rounded {{ $selectedTopic && $selectedTopic == $each->id ? ' selected' : '' }}"
                            data-topic-id="{{ $each->id }}">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title mb-2">{{ $each->name }}</h5>
                                    <p class="card-text text-muted">{{ $each->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-primary" id="btnSave">Lưu lại</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('.topic-card').on('click', function() {
                $('.topic-card').removeClass('selected');
                $(this).addClass('selected');
                $(this).find('.topic-radio').prop('checked', true);
                $('#submitBtn').prop('disabled', false);
            });
            $('#btnSave').on('click', function() {
                var selectedTopic = $('.topic-card.selected');
                if (selectedTopic.length === 0) {
                    showToast("Vui lòng chọn một chủ đề", "warning");
                    return;
                }

                var topicId = selectedTopic.data('topic-id');

                $.ajax({
                    url: '/user/topics/store',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        topic_id: topicId,
                    },
                    success: function(response) {
                        if (response.success) {
                            showToast(response.message, response.status);
                            setTimeout(() => {
                                window.location.href = '';
                            }, 1000);
                        } else {
                            showToast(response.message, response.status);
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 400) {
                            alert(xhr.responseJSON.error);
                        } else {
                            alert('Có lỗi xảy ra. Vui lòng thử lại.');
                        }
                    }
                });
            });
        });
    </script>
@endpush
