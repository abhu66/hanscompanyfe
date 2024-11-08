@extends('layouts.app')
@section('title', 'Detail Company Page')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Detail Company Form</h4>
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

                    <form>
                        @csrf
                        <div class="row g-3">

                            <!-- Display company details -->
                             <div class="col-lg-6">
                                <div>
                                    <label class="form-label d-block">Logo</label>
                                    @if ($d_company->logo_url)
                                        <div class="mt-2">
                                            <img src="{{ env('API_URL') . '/api/images/logo/' . $d_company->logo_url }}" alt="Logo Image" class="img-fluid" style="max-width: 200px;" />
                                        </div>
                                    @else
                                        <p>No logo image available</p>
                                    @endif
                                </div>
                            </div>



                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label d-block">Background Image</label>
                                    @if ($d_company->background_image_url)
                                        <div class="mt-2">
                                            <img src="{{ env('API_URL') . '/api/images/bg/' . $d_company->background_image_url }}" alt="Background Image" class="img-fluid" style="max-width: 200px;" />
                                        </div>
                                    @else
                                        <p>No background image available</p>
                                    @endif
                                </div>
                            </div>




                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="company_name">Company Name</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" disabled
                                        value="{{ $d_company->company_name }}" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="url">Url</label>
                                    <input type="text" class="form-control" id="url" name="url" disabled
                                        value="{{ $d_company->url }}" />
                                </div>
                            </div>




                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="is_active">Is Active</label>
                                    <div class="form-check mt-2">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" disabled
                                            value="1" {{ $d_company->is_active == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Active</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="created_date">Created Date</label>
                                    <input type="text" class="form-control" id="created_date" name="created_date" disabled
                                        value="{{ $d_company->created_date ? \Carbon\Carbon::parse($d_company->created_date)->format('d-m-Y H:i') : '-' }}" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="updated_date">Updated Date</label>
                                    <input type="text" class="form-control" id="updated_date" name="updated_date" disabled
                                        value="{{ $d_company->updated_date ? \Carbon\Carbon::parse($d_company->updated_date)->format('d-m-Y H:i') : '-' }}" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="created_by">Created By</label>
                                    <input type="text" class="form-control" id="created_by" name="created_by" disabled
                                        value="{{ $d_company->created_by }}" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label" for="updated_by">Updated By</label>
                                    <input type="text" class="form-control" id="updated_by" name="updated_by" disabled
                                        value="{{ $d_company->updated_by }}" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
