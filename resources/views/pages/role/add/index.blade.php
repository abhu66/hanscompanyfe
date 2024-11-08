@extends('layouts.app')
@section('title', 'Add Role Page')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Role Form</h4>
                </div>
                <div class="card-body">

                    {{-- Menampilkan pesan kesalahan validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (Session::has('error'))
                        <div class="alert alert-danger justify-content-start align-items-center" role="alert">
                            {{ Session::get('error') }}
                        </div>
                    @endif

                    <form action="{{ route('role.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="name">Role Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Enter role name" required />
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-check mt-1">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                        value="1" {{ isset($data) && $data->is_active == 1 ? 'checked' : '' }} checked>
                                    <label class="form-check-label ms-2" for="is_active">Is Active</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    @foreach ($f_role as $data)
                                        <div class="form-check mt-1"> <!-- Removed d-flex for vertical stacking -->
                                            <input type="checkbox" class="form-check-input" id="role_{{ $data->id }}"
                                                name="roles[]" value="{{ $data->id }}"
                                                {{ $data->is_active ? '' : 'checked' }}>
                                            <label class="form-check-label ms-2"
                                                for="role_{{ $data->id }}">{{ $data->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>

@endsection
