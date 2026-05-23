@extends('layouts.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/dropzone/dropzone.css') }}" />
@endpush

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-xl-6">
            <div class="menud mb-4">
                <div class="menud-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Kecamatan</h5>
                    <small class="text-muted float-end">Create new Kecamatan</small>
                </div>
                <div class="kecamatand-body">
                    <form id="kecamatanForm" enctype="multipart/form-data"> <!-- Add enctype for file upload -->
                        <div class="mb-3">
                            <label class="form-label" for="name">Kecamatan</label>
                            <input type="text" class="form-control" id="name" placeholder="Kecamatan"
                                name="name" value="{{ $kecamatan->name ?? '' }}">
                        </div>
                        <button id="btn-submit-kecamatan" type="submit" class="btn btn-primary">
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
            
            document.querySelector('#kecamatanForm').addEventListener('submit', async function(event) {
                    $("#btn-submit-kecamatan").prop("disabled", true);
                    $("#loading-indicator").removeClass("d-none");
                    const isEdit = '{{ isset($kecamatan) }}'

                    event.preventDefault();

                    const kecamatanName = document.querySelector('#name').value;

                    const formData = new FormData();
                    formData.append('name', kecamatanName);

                    let url = "{{ route('kecamatan.create') }}";
                    @if (isset($kecamatan->id))
                        url = "{{ route('kecamatan.update', $kecamatan->id) }}";
                    @endif

                    const response = await httpClient.post(url, formData);

                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $("#btn-submit-kecamatan").prop("disabled", false);
                    $("#loading-indicator").addClass("d-none");
                    window.location = '{{ route('master.kecamatan.list') }}';
                });
        })();
    </script>
@endpush
