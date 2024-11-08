@extends('layouts.app')
@section('title', 'Edit User Page')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Edit User Form</h4>
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

                    <form method="POST" action="{{ route('user.update') }}">
                        @csrf
                        <div class="row g-3">
                            <input type="text" name="company_id" hidden value="{{ $company->id }}">
                            
                            <input type="text" class="form-control" id="id" name="id" hidden
                                value="{{ $f_user_detail->id }}" required />
                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="name">Nama</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Enter your name" value="{{ $f_user_detail->name }}" required />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" disabled
                                        placeholder="Enter your email" value="{{ $f_user_detail->email }}" required />
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="password">Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="*********" />
                                        <span class="input-group-text" onclick="togglePassword('password', this)">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="c_password">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="c_password" name="c_password"
                                            placeholder="*********" />
                                        <span class="input-group-text" onclick="togglePassword('c_password', this)">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="created_date">Created date</label>
                                    <input type="text" class="form-control" id="created_date" name="created_date"
                                        disabled placeholder="Enter created date" value="{{ $f_user_detail->created_date ? \Carbon\Carbon::parse($f_user_detail->created_date)->format('d-m-Y H:i') : '-' }}"
                                        required />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="updated_date">Updated date</label>
                                    <input type="text" class="form-control" id="updated_date" name="updated_date"
                                        disabled placeholder="Enter updated date" value="{{ $f_user_detail->updated_date ? \Carbon\Carbon::parse($f_user_detail->updated_date)->format('d-m-Y H:i') : '-' }}"
                                        required />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="created_by">Created by</label>
                                    <input type="text" class="form-control" id="created_by" name="created_by" disabled
                                        placeholder="Enter created by" value="{{ $f_user_detail->created_by }}" required />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="updated_by">Updated by</label>
                                    <input type="text" class="form-control" id="updated_by" name="updated_by" disabled
                                        placeholder="Enter updated by" value="{{ $f_user_detail->updated_by }}" required />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="role">Role</label>
                                    <select name="role_id" id="role_id" class="form-control" required>
                                        <option value="">Select a Role</option>
                                        @foreach ($f_role as $role)
                                            <option value="{{ $role->id }}"
                                                {{ isset($f_user_detail) && $f_user_detail->role == $role->name ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Save</button>
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
@push('script')
    <script>
        function togglePassword(fieldId, eyeIcon) {
            const field = document.getElementById(fieldId);
            const icon = eyeIcon.querySelector('i');

            if (field.type === "password") {
                field.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
@endpush
