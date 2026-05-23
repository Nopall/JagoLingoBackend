@extends('layouts.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/dropzone/dropzone.css') }}" />
@endpush

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-xl-6">
            <div class="menud mb-4">
                <div class="menud-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Leaflet</h5>
                    <small class="text-muted float-end">Create new Leaflet</small>
                </div>
                <div class="menud-body">
                    <form id="leafletForm" enctype="multipart/form-data"> <!-- Add enctype for file upload -->
                        <div class="mb-3">
                            <label class="form-label" for="name">Lefalet name</label>
                            <input type="text" class="form-control" id="name" placeholder="Leaflet name"
                                name="name" value="{{ $leaflet->name ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Leaflet Description</label>
                            <textarea class="form-control" id="description" placeholder="Leaflet Description"
                                      name="description">{{ $leaflet->description ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="pdf">PDF</label> <br>
                            @if (isset($leaflet->image))
                                <img src="{{ $leaflet->image }}" alt="" style="width: 50%">
                            @endif
                            <div action="/upload" class="dropzone needsclick dz-clickable" id="dropzone-basic"
                                style="border: 2px dashed #d9dee3;">
                                <div class="dz-message needsclick">
                                    Drop files here or click to upload
                                </div>
                            </div>
                        </div>
                        <button id="btn-submit-leaflet" type="submit" class="btn btn-primary">
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

                document.querySelector('#leafletForm').addEventListener('submit', async function(event) {
                    $("#btn-submit-leaflet").prop("disabled", true);
                    $("#loading-indicator").removeClass("d-none");
                    const isEdit = '{{ isset($leaflet) }}'

                    event.preventDefault();

                    const leafletName = document.querySelector('#name').value;
                    const leafletDescription = document.querySelector('#description').value;
                    const logoFile = myDropzone.getAcceptedFiles()[0];

                    const formData = new FormData();
                    formData.append('name', leafletName);
                    formData.append('description', leafletDescription);
                    formData.append('pdf', logoFile);

                    let url = "{{ route('leaflet.create') }}";
                    @if (isset($leaflet->id))
                        url = "{{ route('leaflet.update', $leaflet->id) }}";
                    @endif

                    const response = await httpClient.post(url, formData);

                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $("#btn-submit-leaflet").prop("disabled", false);
                    $("#loading-indicator").addClass("d-none");
                    window.location = '{{ route('master.leaflet.list') }}';
                });
            }
        })();
    </script>
@endpush
