@extends('layouts.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/dropzone/dropzone.css') }}" />
@endpush

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-xl-6">
            <div class="menud mb-4">
                <div class="menud-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">pembiayaan</h5>
                    <small class="text-muted float-end">Create new Pembiayaan</small>
                </div>
                <div class="pembiayaand-body">
                    <form id="pembiayaanForm" enctype="multipart/form-data"> <!-- Add enctype for file upload -->
                        <div class="mb-3">
                            <label class="form-label" for="name">Pembiayaan name</label>
                            <input type="text" class="form-control" id="name" placeholder="Pembiayaan name"
                                name="name" value="{{ $pembiayaan->name ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="cp_1">Contact Person 1</label>
                            <input type="text" class="form-control" id="cp_1" placeholder="CP 1"
                                name="cp_1" value="{{ $pembiayaan->cp_1 ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="wa_1">Whatsapp 1</label>
                            <input type="text" class="form-control" id="wa_1" placeholder="Whatsapp 1"
                                name="wa_1" value="{{ $pembiayaan->wa_1 ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="cp_2">Contact Person 2</label>
                            <input type="text" class="form-control" id="cp_2" placeholder="Contact Person 2"
                                name="cp_2" value="{{ $pembiayaan->cp_2 ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="wa_2">Whatsapp 2</label>
                            <input type="text" class="form-control" id="wa_2" placeholder="Whatsapp 2"
                                name="wa_2" value="{{ $pembiayaan->wa_2 ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="image">Photo</label> <br>
                            @if (isset($pembiayaan->image))
                                <img src="{{ $pembiayaan->image }}" alt="" style="width: 50%">
                            @endif
                            <div action="/upload" class="dropzone needsclick dz-clickable" id="dropzone-basic"
                                style="border: 2px dashed #d9dee3;">
                                <div class="dz-message needsclick">
                                    Drop files here or click to upload
                                </div>
                            </div>
                        </div>
                        <button id="btn-submit-pembiayaan" type="submit" class="btn btn-primary">
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

                document.querySelector('#pembiayaanForm').addEventListener('submit', async function(event) {
                    $("#btn-submit-pembiayaan").prop("disabled", true);
                    $("#loading-indicator").removeClass("d-none");
                    const isEdit = '{{ isset($pembiayaan) }}'

                    event.preventDefault();

                    const pembiayaanName = document.querySelector('#name').value;
                    const cp1 = document.querySelector('#cp_1').value;
                    const wa1 = document.querySelector('#wa_1').value;
                    const cp2 = document.querySelector('#cp_2').value;
                    const wa2 = document.querySelector('#wa_2').value;
                    const logoFile = myDropzone.getAcceptedFiles()[0];

                    const formData = new FormData();
                    formData.append('name', pembiayaanName);
                    formData.append('cp_1', cp1);
                    formData.append('wa_1', wa1);
                    formData.append('cp_2', cp2);
                    formData.append('wa_2', wa2);
                    formData.append('image', logoFile);

                    let url = "{{ route('pembiayaan.create') }}";
                    @if (isset($pembiayaan->id))
                        url = "{{ route('pembiayaan.update', $pembiayaan->id) }}";
                    @endif

                    const response = await httpClient.post(url, formData);

                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $("#btn-submit-pembiayaan").prop("disabled", false);
                    $("#loading-indicator").addClass("d-none");
                    window.location = '{{ route('master.pembiayaan.list') }}';
                });
            }
        })();
    </script>
@endpush
