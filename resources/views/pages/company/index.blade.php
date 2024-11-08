@extends('layouts.app')
@section('title', 'List Company Page')
@section('content')
    @php
        use App\Services\MenuService;
        // dd(Session::get('user'));
    @endphp
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Table Company</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('company.create') }}" class="btn btn-primary" id="addCompanyButton">
                               <i class="ri-add-line align-middle"></i> Add Company
                       </a>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Company Name</th>
                                        <th scope="col">URL</th>
                                         <th scope="col">Is active</th>
                                        <th scope="col">Created date</th>
                                        <th scope="col">Updated date</th>
                                        <th scope="col">Created by</th>
                                        <th scope="col">Updated by</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($company as $data)
                                        <tr>
                                            <th scope="row"><a href="#"
                                                    class="fw-medium">{{ $loop->iteration }}</a>
                                            </th>
                                            <td>{{ $data->company_name }}</td>
                                            <td>{{ $data->url }}</td>
                                            <td>{{ $data->is_active == 1 ? 'Active' : 'Non Active' }}</td>
                                            <td>
                                                {{ $data->created_date ? \Carbon\Carbon::parse($data->created_date)->format('d-m-Y H:i') : '-' }}
                                            </td>
                                            <td>
                                                {{ $data->updated_date ? \Carbon\Carbon::parse($data->updated_date)->format('d-m-Y H:i') : '-' }}
                                            </td>
                                            <td>{{ $data->created_by }}</td>
                                            <td>
                                                {{ $data->updated_by }}
                                            </td>

                                            <td>
                                            <a href="{{ route('company.detail', $data->id) }}"
                                                class="link-primary">View</a>

                                               |
                                                <a href="{{ route('company.edit', $data->id) }}"
                                                 class="link-success">Edit</a>


                                        </td>



                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <!-- end card-body -->
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
@endsection
