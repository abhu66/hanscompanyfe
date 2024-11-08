@extends('layouts.app')
@section('title', 'Edit Company Page')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Edit Company Form</h4>
                </div>
                <div class="card-body">

                    {{-- Display validation errors --}}
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

                    <form method="POST" action="{{ route('company.update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">

                            <input type="text" class="form-control" id="id" name="id" hidden value="{{ $f_company->id }}" required />



                        <div class="col-lg-6">
                       <div>
                             <label class="form-label d-block" for="logo">Current Logo</label>
                                                                                  @if ($f_company->logo_url)
                                                                                      <div class="mt-2">
                                                                                          <img src="{{ env('API_URL') . '/api/images/logo/' . $f_company->logo_url }}" alt="Logo Image" class="img-fluid" style="max-width: 120px;" />
                                                                                      </div>
                                                                                  @else
                                                                                      <p>No logo image available</p>
                                                                                  @endif
                           <input type="file" class="form-control mt-3" id="logo" name="logo" accept="image/*" />

                       </div>
                   </div>

                   <div class="col-lg-6">
                       <div>
                            <label class="form-label d-block" for="background_image">Current Background Image</label>
                                                                                 @if ($f_company->background_image_url)
                                                                                     <div class="mt-2">
                                                                                         <img src="{{ env('API_URL') . '/api/images/bg/' . $f_company->background_image_url }}" alt="Background Image" class="img-fluid" style="max-width: 300px;" />
                                                                                     </div>
                                                                                 @else
                                                                                     <p>No background image available</p>
                                                                                 @endif
                           <input type="file" class="form-control mt-3" id="background_image" name="background_image" accept="image/*" />

                       </div>
                   </div>



                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="company_name">Company Name</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name"
                                        placeholder="Enter company name" value="{{ $f_company->company_name }}" required />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="url">Url</label>
                                    <input type="text" class="form-control" id="url" name="url" placeholder="Enter URL" value="{{ $f_company->url }}" required />
                                </div>
                            </div>





                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="is_active">Is Active</label>
                                    <div class="form-check mt-2">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                            value="1" {{ isset($f_company) && $f_company->is_active == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Active</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="created_date">Created date</label>
                                    <input type="text" class="form-control" id="created_date" name="created_date"
                                        disabled placeholder="Enter created date" value="{{ $f_company->created_date ? \Carbon\Carbon::parse($f_company->created_date)->format('d-m-Y H:i') : '-' }}"
                                        required />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="updated_date">Updated date</label>
                                    <input type="text" class="form-control" id="updated_date" name="updated_date"
                                        disabled placeholder="Enter updated date" value="{{ $f_company->updated_date ? \Carbon\Carbon::parse($f_company->updated_date)->format('d-m-Y H:i') : '-' }}"
                                        required />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="created_by">Created by</label>
                                    <input type="text" class="form-control" id="created_by" name="created_by" disabled
                                        placeholder="Enter created by" value="{{ $f_company->created_by }}" required />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="updated_by">Updated by</label>
                                    <input type="text" class="form-control" id="updated_by" name="updated_by" disabled
                                        placeholder="Enter updated by" value="{{ $f_company->updated_by }}" required />
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
