@extends('layouts.app')
@section('title', 'Upload Page')
@section('content')
    @php
        use App\Services\MenuService;
    @endphp

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <div class="d-flex align-items-center flex-grow-1">
                        <h4 class="card-title mb-0 me-2">Upload Excel File</h4>
                        <div id="progressContainer" class="d-none" style="width: 200px;">
                            <div class="progress" style="height: 30%">
                                <div id="progressBar" class="progress-bar" role="progressbar"
                                    style="width: 0%; padding: 5px;h" aria-valuenow="0" aria-valuemin="0"
                                    aria-valuemax="100">Uploading File</div>
                            </div>
                        </div>

                    </div>
                    <div class="flex-shrink-0">
                        <a href="#" class="btn btn-primary" id="addUserButton" data-bs-toggle="modal"
                            data-bs-target="#uploadCsvModal">
                            <i class="ri-add-line align-middle"></i> Add File
                        </a>
                    </div>
                </div><!-- end card header -->

                @if (MenuService::hasAccess(Session::get('role_functions'), 'Upload Excel'))
                    <div class="card-body">
                        {{-- <p class="text-muted">Use <code>table</code> class to show bootstrap-based default table.</p> --}}
                        <div class="live-preview">
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Filename</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Created by</th>
                                            <th scope="col">Created date</th>
                                            <th scope="col">Updated by</th>
                                            <th scope="col">Updated date</th>
                                            {{-- <th scope="col">Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody id="dataTableBody">
                                        @foreach ($file as $data)
                                            <tr>
                                                <th scope="row">
                                                    <a href="#" class="fw-medium">{{ $loop->iteration }}</a>
                                                </th>
                                                <td>
                                                    <a href="{{ $data->url_file }}" download>
                                                        {{ $data->filename }} &nbsp; <i class="fas fa-download"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    @if ($data->status == 1)
                                                        <span class="badge bg-primary">Process</span>
                                                    @elseif ($data->status == 2)
                                                        <span class="badge bg-success">Success</span>
                                                    @else
                                                        <span class="badge bg-danger">Failed</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $data->created_by }}
                                                </td>
                                                <td>
                                                    {{ $data->created_date ? \Carbon\Carbon::parse($data->created_date)->format('d-m-Y H:i') : '-' }}
                                                </td>
                                                <td>
                                                    {{ $data->updated_by }}
                                                </td>
                                                <td>
                                                    {{ $data->updated_date ? \Carbon\Carbon::parse($data->updated_date)->format('d-m-Y H:i') : '-' }}
                                                </td>
                                                {{-- <td>
                                                <a href="javascript:void(0);" class="link-success">View More <i
                                                        class="ri-arrow-right-line align-middle"></i></a>
                                            </td> --}}
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




    <!-- Modal for Upload Excel -->
    <div class="modal fade" id="uploadCsvModal" tabindex="-1" aria-labelledby="uploadCsvModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadCsvModalLabel">Upload Excel File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadForm" enctype="multipart/form-data" onsubmit="hideModal()">
                        @csrf
                        <div class="mb-3">
                            <label for="upload" class="form-label">Select Excel File</label>
                            <input type="file" class="form-control" id="upload" name="upload" accept=".xls, .xlsx"
                                required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>

                    </form>
                    <div id="uploadMessage" class="text-success mt-2"></div> <!-- Message display -->
                    <div id="uploadError" class="text-danger mt-2"></div> <!-- Error message display -->
                </div>
            </div>
        </div>
    </div>


@endsection
@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#uploadForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                var formData = new FormData(this); // Create FormData object

                // Show the progress container and reset the progress bar
                const progressContainer = $('#progressContainer');
                const progressBar = $('#progressBar');

                progressContainer.removeClass('d-none'); // Show the progress bar
                progressBar.width('0%'); // Reset width
                progressBar.attr('aria-valuenow', 0); // Reset aria value
                progressBar.text('Uploading File');

                $.ajax({
                    url: '{{ route('upload.store') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        // Upload progress
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                progressBar.width(percentComplete +
                                    '%'); // Update the width of the progress bar
                                progressBar.attr('aria-valuenow',
                                    percentComplete); // Update the aria value
                                // Optionally update text to show progress
                                progressBar.text('Uploading File...');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function(response) {
                        progressBar.text(
                            'Upload Complete'); // Change text when upload is complete
                        setTimeout(() => {
                            progressContainer.addClass(
                                'd-none'); // Hide the progress bar after a delay
                            progressBar.width('0%'); // Reset width for the next upload
                            progressBar.attr('aria-valuenow', 0); // Reset aria value
                            progressBar.text('Uploading File'); // Reset text
                        }, 2000); // Change this duration as needed

                        $('#uploadMessage').text('File uploaded successfully!');
                        $('#uploadError').text('');
                        $('#uploadCsvModal').modal('hide');
                        refreshTable();
                    },
                    error: function(xhr) {
                        //progressBar.text('Upload Failed'); // Change text for error feedback
                        $('#uploadMessage').text('');
                        $('#uploadError').text('Error: ' + xhr.responseJSON.error);
                        progressContainer.addClass('d-none'); // Hide progress on error
                    }
                });
            });
        });


        function hideModal() {
            // Hide the modal
            var modalElement = document.getElementById('uploadCsvModal');
            var modal = bootstrap.Modal.getInstance(modalElement);
            modal.hide();
            // Show loading message
            // $('#loadingMessage').show();
            showProgressBar();

            // Refresh the table
            refreshTable();

            // Optionally, show a loading indicator here or disable the button to prevent multiple submissions
            // Example:
            // document.querySelector('.btn-primary').disabled = true;
        }

        function refreshTable() {
            const dataTableBody = document.getElementById("dataTableBody");
            const scrollPosition = dataTableBody.scrollTop; // Save the current scroll position

            fetch("{{ route('upload') }}")
                .then(response => response.text())
                .then(html => {
                    // Create a temporary element to hold the new content
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;

                    // Replace the inner HTML of the current table body
                    dataTableBody.innerHTML = tempDiv.querySelector("#dataTableBody").innerHTML;

                    // Restore the scroll position
                    dataTableBody.scrollTop = scrollPosition;

                    // Hide loading message after data is refreshed
                    // Update the file count for comparison

                    $('#loadingMessage').hide();
                })
                .catch(error => console.error('Error fetching data:', error));
        }
        // Refresh the table every 10 seconds
        setInterval(refreshTable, 5000);

        function showProgressBar() {
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');

            // Show the progress bar
            progressContainer.classList.remove('d-none');

            // Simulate progress (replace with actual upload progress logic)
            let progress = 0;
            const interval = setInterval(() => {
                progress += 10; // Increase progress by 10% for demonstration
                progressBar.style.width = progress + '%';
                progressBar.setAttribute('aria-valuenow', progress);

                if (progress >= 100) {
                    clearInterval(interval);
                    progressBar.textContent = 'Upload Complete'; // Change text when upload is complete

                    // Hide the progress bar after a short delay
                    setTimeout(() => {
                        progressContainer.classList.add('d-none');
                        progressBar.style.width = '0%'; // Reset width for the next upload
                        progressBar.setAttribute('aria-valuenow', 0); // Reset aria value
                        progressBar.textContent = 'Uploading File'; // Reset text
                    }, 2000); // Hide after 2 seconds
                }
            }, 500); // Update every 500ms
        }
    </script>
@endpush
