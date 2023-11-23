@extends('admin.layouts.master')
@section('content')
    <form action="{{ route('admin.role.update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin vai trò</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Checkbox chọn tất cả -->
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tên vai trò <span class="text-danger">*</span></label>
                                <input name="name" type="text" class="form-control" value="{{ $data->name }}"
                                    placeholder="Nhập ...">
                                {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Mô tả </label>
                                <input name="description" type="text" class="form-control"
                                    value="{{ $data->description }}" placeholder="Nhập ...">
                                {!! $errors->first('description', '<span class="text-danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input name="active" type="checkbox" class="custom-control-input"
                                        {{ $data->active ? 'checked' : '' }} id="customSwitch3">
                                    <label class="custom-control-label" for="customSwitch3">Hoạt động</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="checkbox" id="select-all" class="form-check-input">
                        <label for="select-all" class="form-check-label">Chọn tất cả quyền hạn</label>
                    </div>
                    <div class="row">

                        @foreach ($routeGroups as $groupKey => $routes)
                            <div class="col-sm-4">
                                <!-- Đưa checkbox và tiêu đề vào cùng một dòng -->
                                <div class="mb-2 d-flex align-items-center">
                                    <input type="checkbox" class="form-check-input select-all-group mr-2">
                                    <h3 class="mb-0">{{ __('route.' . $groupKey) }}</h3>
                                </div>
                                <ul class="list-unstyled">
                                    @foreach ($routes as $routeName)
                                        <li class="form-check">
                                            @if (in_array($routeName, $permisions))
                                                <input type="checkbox" checked name="selected_routes[]"
                                                    value="{{ $routeName }}" id="{{ $routeName }}"
                                                    class="form-check-input">
                                            @else
                                                <input type="checkbox" name="selected_routes[]" value="{{ $routeName }}"
                                                    id="{{ $routeName }}" class="form-check-input">
                                            @endif
                                            <label for="{{ $routeName }}"
                                                class="form-check-label">{{ __('route.' . $routeName) }}</label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="float-right">
            <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            // Chọn tất cả trong nhóm
            $('.select-all-group').on('click', function() {
                $(this).closest('div').next('ul').find('input[type="checkbox"]').prop('checked', $(this)
                    .prop('checked'));
            });

            // Chọn tất cả các routes trong tất cả các nhóm
            $('#select-all').on('click', function() {
                $('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
            });
        });
    </script>
    @include('ckfinder::setup')
@stop
