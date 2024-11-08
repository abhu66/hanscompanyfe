@extends('layouts.app')
@section('title', 'Role Page')
@section('content')
    @php
        use App\Services\MenuService;
    @endphp
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Role Users</h4>
                    <div class="flex-shrink-0">
                        @if (MenuService::hasAccess(Session::get('role_functions'), 'CRUD Role'))
                            <a href="{{ route('role.create') }}" class="btn btn-primary" id="addUserButton">
                                <i class="ri-add-line align-middle"></i> Add Role
                            </a>
                        @endif
                    </div>
                </div><!-- end card header -->

                @if (MenuService::hasAccess(Session::get('role_functions'), 'View Role'))
                    <div class="card-body">
                        <div class="live-preview">
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Is active</th>
                                            <th scope="col">Created date</th>
                                            <th scope="col">Updated date</th>
                                            <th scope="col">Created by</th>
                                            <th scope="col">Updated by</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($role as $data)
                                            <tr>
                                                <th scope="row">
                                                    <a href="#" class="fw-medium">{{ $loop->iteration }}</a>
                                                </th>
                                                <td>{{ $data->name }}</td>
                                                <td>{{ $data->is_active == 1 ? 'Active' : 'Non Active' }}</td>
                                                <td>
                                                   {{ $data->created_date ? \Carbon\Carbon::parse($data->created_date)->format('d-m-Y H:i') : '-' }}
                                               </td>
                                                <td>
                                                    {{ $data->updated_date ? \Carbon\Carbon::parse($data->created_date)->format('d-m-Y H:i') : '-' }}
                                                </td>
                                                <td>
                                                    {{ $data->created_by }}
                                                </td>
                                                <td>
                                                    {{ $data->updated_by }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('role.detail', $data->id) }}"
                                                        class="link-primary">View</a>
                                                    @if (MenuService::hasAccess(Session::get('role_functions'), 'CRUD Role'))
                                                        |
                                                        <a href="{{ route('role.edit', $data->id) }}"
                                                            class="link-success">Edit</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                @endif
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!-- end col -->
    </div>
    <!-- end row -->
@endsection
