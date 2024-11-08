@extends('layouts.app')
@section('title', 'List Users Page')
@section('content')
    @php
        use App\Services\MenuService;
        // dd(Session::get('user'));
    @endphp
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Table Users</h4>
                    <div class="flex-shrink-0">
                        @if (MenuService::hasAccess(Session::get('role_functions'), 'CRUD User'))
                            <a href="{{ route('user.create') }}" class="btn btn-primary" id="addUserButton">
                                <i class="ri-add-line align-middle"></i> Add User
                            </a>
                        @endif
                    </div>
                </div><!-- end card header -->
                @if (MenuService::hasAccess(Session::get('role_functions'), 'View User'))
                    <div class="card-body">
                        <div class="live-preview">
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Created date</th>
                                            <th scope="col">Updated date</th>
                                            <th scope="col">Created by</th>
                                            <th scope="col">Updated by</th>
                                            <th scope="col">Role</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user as $data)
                                            <tr>
                                                <th scope="row"><a href="#"
                                                        class="fw-medium">{{ $loop->iteration }}</a>
                                                </th>
                                                <td>{{ $data->name }}</td>
                                                <td>{{ $data->email }}</td>
                                                <td>
                                                    {{ $data->created_date ? \Carbon\Carbon::parse($data->created_date)->format('d-m-Y H:i') : '-' }}
                                                </td>
                                                <td>
                                                    {{ $data->updated_date ? \Carbon\Carbon::parse($data->updated_date)->format('d-m-Y H:i') : '-' }}
                                                </td>
                                                <td>{{ $data->created_by }}</td>
                                                <td>{{ $data->updated_by }}</td>
                                                <td>{{ $data->role->name ?? '-' }}</td>
                                                <td>
                                                    <a href="{{ route('user.detail', $data->id) }}"
                                                        class="link-primary">View</a>
                                                    @if (MenuService::hasAccess(Session::get('role_functions'), 'CRUD User'))
                                                        <a href="{{ route('user.edit', $data->id) }}"
                                                            class="link-success">| Edit</a>
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
                <!-- end card-body -->
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
@endsection
