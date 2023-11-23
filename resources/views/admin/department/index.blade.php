@extends('layouts.admin.default')
@section('content')

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th>
                            Tên phòng ban
                        </th>
                        <th>
                            Phòng ban cấp trên
                        </th>
                        {{-- <th>
                        Trạng thái
                    </th> --}}
                        <th>
                            Hành động
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($datas as $data)
                        <tr>
                            <td>
                                {{ str_repeat('|--', $depth) }} {{ $data->name }}
                            </td>
                            <td>
                                {{ optional($data->parent)->name }}
                            </td>
                            {{-- <td class="">
                        {!! $data->active ? '<span class="badge badge-success">Hoạt động</span>' : '<span
                            class="badge badge-danger">Khoá</span>' !!}
                    </td> --}}
                            <td class="project-actions">
                                <a class="btn btn-info btn-sm" href="{{ route('admin.department.edit', $data->id) }}">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                    Sửa
                                </a>
                                <form action="{!! route('admin.department.destroy', $data->id) !!}" method="POST" style="display: inline-block">
                                    {!! method_field('DELETE') !!}
                                    {!! csrf_field() !!}

                                    <button type="submit" class="btn btn-danger btn-sm delete_confirm"
                                        data-action="delete">
                                        <i class="fas fa-trash-alt">
                                        </i>
                                        Xoá
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @if ($data->children->isNotEmpty())
                            @include('admin.department.department_children', [
                                'datas' => $data->children,
                                'depth' => $depth + 1,
                            ])
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <style>
        #diagram {
            height: 725px;
        }

        /* .dx-overlay-content .dx-popup-normal .dx-popup-draggable .dx-resizable {
                                    display: none;
                                } */

        #diagram .template .template-name {
            font-weight: bold;
            text-decoration: underline;
        }

        #diagram .template .template-title {
            font-weight: bold;
            font-style: italic;
        }

        #diagram .template .template-button {
            cursor: pointer;
            font-size: 8pt;
            fill: navy;
        }

        #diagram .template .template-button:hover {
            text-decoration: underline;
        }

        .dx-popup-content {
            padding: 0;
        }

        .dx-popup-content .dx-fieldset.buttons {
            display: flex;
            justify-content: flex-end;
        }

        .dx-popup-content .dx-fieldset.buttons>* {
            margin-left: 8px;
        }
    </style>

    <script type="text/javascript">
        $('.delete_confirm').click(function(e) {
            if (!confirm('Bạn có muốn xoá bản ghi này?')) {
                e.preventDefault();
            }
        });
    </script>
@stop
