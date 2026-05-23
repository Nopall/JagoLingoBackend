@extends('layouts.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/dropzone/dropzone.css') }}" />
@endpush

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-xl-6">
            <div class="menud mb-4">
                <div class="menud-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">pestisida</h5>
                    <small class="text-muted float-end">Create new Pestisida</small>
                </div>
                <div class="pestisidad-body">
                    <form id="pestisidaForm" enctype="multipart/form-data"> <!-- Add enctype for file upload -->
                        <div class="mb-3">
                            <label class="form-label" for="name">Pestisida name</label>
                            <input type="text" class="form-control" id="name" placeholder="Pestisida name"
                                name="name" value="{{ $pestisida->name ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Lokasi</label>
                            <input type="text" class="form-control" id="location" placeholder="Lokasi"
                                name="location" value="{{ $pestisida->location ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Seller</label>
                            <input type="text" class="form-control" id="seller" placeholder="Seller"
                                name="seller" value="{{ $pestisida->seller ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Contact Number</label>
                            <input type="text" class="form-control" id="contact_no" placeholder="Contact Person"
                                name="contact_no" value="{{ $pestisida->contact_no ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Photo</label> <br>
                            @if (isset($pestisida->photo))
                                <img src="{{ $pestisida->photo }}" alt="" style="width: 50%">
                            @endif
                            <div action="/upload" class="dropzone needsclick dz-clickable" id="dropzone-basic"
                                style="border: 2px dashed #d9dee3;">
                                <div class="dz-message needsclick">
                                    Drop files here or click to upload
                                </div>
                            </div>
                        </div>
                        <button id="btn-submit-pestisida" type="submit" class="btn btn-primary">
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

                document.querySelector('#pestisidaForm').addEventListener('submit', async function(event) {
                    $("#btn-submit-pestisida").prop("disabled", true);
                    $("#loading-indicator").removeClass("d-none");
                    const isEdit = '{{ isset($pestisida) }}'

                    event.preventDefault();

                    const pestisidaName = document.querySelector('#name').value;
                    const seller = document.querySelector('#seller').value;
                    const location = document.querySelector('#location').value;
                    const contactNo = document.querySelector('#contact_no').value;
                    const logoFile = myDropzone.getAcceptedFiles()[0];

                    const formData = new FormData();
                    formData.append('name', pestisidaName);
                    formData.append('seller', seller);
                    formData.append('location', location);
                    formData.append('contact_no', contactNo);
                    formData.append('photo', logoFile);

                    let url = "{{ route('pestisida.create') }}";
                    @if (isset($pestisida->id))
                        url = "{{ route('pestisida.update', $pestisida->id) }}";
                    @endif

                    const response = await httpClient.post(url, formData);

                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $("#btn-submit-pestisida").prop("disabled", false);
                    $("#loading-indicator").addClass("d-none");
                    window.location = '{{ route('master.pestisida.list') }}';
                });
            }
        })();
    </script>
@endpush
