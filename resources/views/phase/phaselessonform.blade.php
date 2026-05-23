@extends('layouts.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/dropzone/dropzone.css') }}" />
@endpush

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-xl-6">
            <div class="productimaged mb-4">
                <div class="productimaged-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Phase Lesson</h5>
                    <small class="text-muted float-end">Create new Lesson</small>
                </div>
                <div class="productimaged-body">
                    <form id="productimageForm" enctype="multipart/form-data"> <!-- Add enctype for file upload -->
                        
                        <!-- Title field -->
                        <div class="mb-3">
                            <label class="form-label" for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" 
                            value="{{ isset($phaselesson->title) ? $phaselesson->title : '' }}">
                        </div>

                        <!-- Type dropdown -->
                        <div class="mb-3">
                            <label class="form-label" for="type">Type</label>
                            <select class="form-control" id="type" name="type">
                                <option value="audio" {{ isset($phaselesson->type) && $phaselesson->type == 'audio' ? 'selected' : '' }}>Audio</option>
                                <option value="audio_image" {{ isset($phaselesson->type) && $phaselesson->type == 'audio_image' ? 'selected' : '' }}>Audio with Image</option>
                            </select>
                        </div>

                        <!-- Image upload field -->
                        <div class="mb-3">
                            <label class="form-label" for="name">Image</label> <br>
                            @if (isset($phaselesson->image_url))
                                <img src="{{ $phaselesson->image_url }}" alt="" style="width: 50%">
                            @endif
                            <div action="/upload" class="dropzone needsclick dz-clickable" id="dropzone-basic"
                                style="border: 2px dashed #d9dee3;">
                                <div class="dz-message needsclick">
                                    Drop files here or click to upload
                                </div>
                            </div>
                        </div>

                        <!-- Audio upload field -->
                        <div class="mb-3">
                            <label class="form-label" for="audio">Upload Audio</label>
                            <input type="file" class="form-control" id="audio" name="audio" accept="audio/*">
                        </div>

                        <button id="btn-submit-productimage" type="submit" class="btn btn-primary">
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
            const productId = <?php echo json_encode($id); ?>;
            if (dropzoneBasic) {
                const myDropzone = new Dropzone(dropzoneBasic, {
                    previewTemplate: previewTemplate,
                    parallelUploads: 1,
                    maxFilesize: 5,
                    addRemoveLinks: true,
                    maxFiles: 1,
                });

                document.querySelector('#productimageForm').addEventListener('submit', async function(event) {
                    $("#btn-submit-productimage").prop("disabled", true);
                    $("#loading-indicator").removeClass("d-none");
                    const isEdit = '{{ isset($phaselesson) }}'

                    event.preventDefault();
                    
                    const logoFile = myDropzone.getAcceptedFiles()[0];
                    const audioFile = document.querySelector('#audio').files[0]; // Get the audio file

                    const formData = new FormData();
                    formData.append('image', logoFile);
                    formData.append('audio', audioFile); // Append the audio file
                    formData.append('phase_id', productId);
                    formData.append('title', document.querySelector('#title').value);
                    formData.append('type', document.querySelector('#type').value);

                    let url = "{{ route('phaselesson.create', ['id' => $id]) }}";
                    @if (isset($phaselesson->id))
                        url = "{{ route('phaselesson.update', $phaselesson->id) }}";
                    @endif

                    const response = await httpClient.post(url, formData);

                    Swal.fire({
                        position: 'top-ceter',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $("#btn-submit-productimage").prop("disabled", false);
                    $("#loading-indicator").addClass("d-none");
                    window.location = "{{ route('phase.lesson' , ['id' => $id]) }}";

                });
            }
        })();
    </script>
@endpush
