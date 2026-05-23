@extends('layouts.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/dropzone/dropzone.css') }}" />
@endpush

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-xl-6">
            <div class="menud mb-4">
                <div class="menud-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Package</h5>
                    <small class="text-muted float-end">Create new Package</small>
                </div>
                <div class="packaged-body">
                    <form id="packageForm" enctype="multipart/form-data"> <!-- Add enctype for file upload -->
                        <div class="mb-3">
                            <label class="form-label" for="name">Nama</label>
                            <input type="text" class="form-control" id="name" placeholder="Nama"
                                name="name" value="{{ $package->name ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="price">Price</label>
                            <input type="number" class="form-control" id="price" placeholder="Price"
                                name="price" value="{{ $package->price ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="is_active">Active</label>
                            <select class="form-control" id="is_active" name="is_active">
                                <option value="1" {{ (isset($package) && $package->is_active == 1) ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ (isset($package) && $package->is_active == 0) ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <button id="btn-submit-package" type="submit" class="btn btn-primary">
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
            
            document.querySelector('#packageForm').addEventListener('submit', async function(event) {
                    $("#btn-submit-package").prop("disabled", true);
                    $("#loading-indicator").removeClass("d-none");
                    const isEdit = '{{ isset($package) }}'

                    event.preventDefault();
                    
                    const packageName = document.querySelector('#name').value;
                    const packagePrice = document.querySelector('#price').value;
                    const isActive = document.querySelector('#is_active').value;
                    
                    const formData = new FormData();
                    formData.append('name', packageName);
                    formData.append('price', packagePrice);
                    formData.append('is_active', isActive);


                    let url = "{{ route('package.create') }}";
                    @if (isset($package->id))
                        url = "{{ route('package.update', $package->id) }}";
                    @endif

                    const response = await httpClient.post(url, formData);

                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $("#btn-submit-package").prop("disabled", false);
                    $("#loading-indicator").addClass("d-none");
                    window.location = '{{ route('master.package.list') }}';
                });
        })();
    </script>
@endpush
