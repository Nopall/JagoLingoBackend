@extends('layouts.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/dropzone/dropzone.css') }}" />
@endpush

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Car Brand</h5>
                    <small class="text-muted float-end">Create new car brand</small>
                </div>
                <div class="card-body">
                    <form id="brandForm" enctype="multipart/form-data"> <!-- Add enctype for file upload -->
                        <div class="mb-3">
                            <label class="form-label" for="name">Brand name</label>
                            <input type="text" class="form-control" id="name" placeholder="Brand name"
                                name="name" value="{{ $carBrand->name ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Logo</label> <br>
                            @if (isset($carBrand->logo_url))
                                <img src="{{ $carBrand->logo_url }}" alt="" style="width: 50%">
                            @endif
                            <div action="/upload" class="dropzone needsclick dz-clickable" id="dropzone-basic"
                                style="border: 2px dashed #d9dee3;">
                                <div class="dz-message needsclick">
                                    Drop files here or click to upload
                                </div>
                            </div>
                        </div>
                        <button id="btn-submit-brand" type="submit" class="btn btn-primary">
                            <div id="loading-indicator" class="spinner-border spinner-border-sm text-default d-none"
                                role="status"></div> Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/libs/dropzone/dropzone.js') }}"></script>
    <script>
        (function() {
            const previewTemplate = `<div class="dz-preview dz-file-preview">
            <div class="dz-details">
            <div class="dz-thumbnail">
                <img data-dz-thumbnail>
                <span class="dz-nopreview">No preview</span>
                <div class="dz-success-mark"></div>
                <div class="dz-error-mark"></div>
                <div class="dz-error-message"><span data-dz-errormessage></span></div>
                <div class="progress">
                <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
                </div>
            </div>
            <div class="dz-filename" data-dz-name></div>
            <div class="dz-size" data-dz-size></div>
            </div>
            </div>`;

            const dropzoneBasic = document.querySelector('#dropzone-basic');
            if (dropzoneBasic) {
                const myDropzone = new Dropzone(dropzoneBasic, {
                    previewTemplate: previewTemplate,
                    parallelUploads: 1,
                    maxFilesize: 5,
                    addRemoveLinks: true,
                    maxFiles: 1,
                });

                document.querySelector('#brandForm').addEventListener('submit', async function(event) {
                    $("#btn-submit-brand").prop("disabled", true);
                    $("#loading-indicator").removeClass("d-none");
                    const isEdit = '{{ isset($carBrand) }}'

                    event.preventDefault();

                    const brandName = document.querySelector('#name').value;
                    const logoFile = myDropzone.getAcceptedFiles()[0];

                    const formData = new FormData();
                    formData.append('name', brandName);
                    formData.append('logo', logoFile);

                    let url = "{{ route('car.create-brand') }}";
                    @if (isset($carBrand->id))
                        url = "{{ route('car.update-brand', $carBrand->id) }}";
                    @endif

                    const response = await httpClient.post(url, formData);

                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $("#btn-submit-brand").prop("disabled", false);
                    $("#loading-indicator").addClass("d-none");
                    window.location = '{{ route('car.list') }}';
                });
            }
        })();
    </script>
@endpush
