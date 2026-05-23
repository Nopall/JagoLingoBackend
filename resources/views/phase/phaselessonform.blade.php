@extends('layouts.index')

@section('title', isset($phaselesson) ? 'Edit Lesson' : 'Tambah Lesson')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/dropzone/dropzone.css') }}" />
@endpush

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-7 col-lg-9">

        <div class="mb-3">
            <a href="{{ route('phase.lesson', ['id' => $id]) }}" class="btn btn-sm btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="avatar-initial rounded bg-label-primary p-2" style="font-size: 1.4rem;">
                        <i class="bx bx-headphone"></i>
                    </span>
                    <div>
                        <h5 class="card-title mb-0 fw-semibold">
                            {{ isset($phaselesson) ? 'Edit Lesson' : 'Tambah Lesson Baru' }}
                        </h5>
                        <small class="text-muted">{{ isset($phaselesson) ? 'Perbarui konten lesson' : 'Isi form di bawah untuk menambah lesson' }}</small>
                    </div>
                </div>
            </div>
            <div class="card-body py-4">
                <form id="productimageForm" enctype="multipart/form-data">

                    <div class="mb-4">
                        <label class="form-label fw-medium" for="title">Judul Lesson <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Masukkan judul lesson"
                               value="{{ $phaselesson->title ?? '' }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium" for="type">Tipe Lesson <span class="text-danger">*</span></label>
                        <select class="form-select" id="type" name="type">
                            <option value="audio" {{ isset($phaselesson) && $phaselesson->type == 'audio' ? 'selected' : '' }}>
                                Audio
                            </option>
                            <option value="audio_image" {{ isset($phaselesson) && $phaselesson->type == 'audio_image' ? 'selected' : '' }}>
                                Audio dengan Gambar
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Gambar</label>
                        @if(isset($phaselesson->image_url))
                            <div class="mb-2">
                                <img src="{{ $phaselesson->image_url }}" alt="Current image" class="img-thumbnail" style="max-height: 150px;">
                                <div class="form-text">Gambar saat ini. Upload baru untuk mengganti.</div>
                            </div>
                        @endif
                        <div class="dropzone needsclick dz-clickable" id="dropzone-basic"
                             style="border: 2px dashed #d9dee3; border-radius: 8px; background: #f8f9fa; min-height: 100px;">
                            <div class="dz-message needsclick text-muted">
                                <i class="bx bx-cloud-upload bx-lg d-block mb-1"></i>
                                Klik atau seret gambar ke sini
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium" for="audio">Upload Audio <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="audio" name="audio" accept="audio/*">
                        @if(isset($phaselesson->audio_url))
                            <div class="form-text">
                                <i class="bx bx-music me-1"></i>
                                <a href="{{ $phaselesson->audio_url }}" target="_blank">Audio saat ini</a>
                                — upload baru untuk mengganti.
                            </div>
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        <button id="btn-submit-productimage" type="submit" class="btn btn-primary">
                            <div id="loading-indicator" class="spinner-border spinner-border-sm d-none me-1" role="status"></div>
                            <i class="bx bx-save me-1"></i>
                            {{ isset($phaselesson) ? 'Simpan Perubahan' : 'Tambah Lesson' }}
                        </button>
                        <a href="{{ route('phase.lesson', ['id' => $id]) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

@endsection

@push('js')
<script src="{{ asset('assets/libs/dropzone/dropzone.js') }}"></script>
<script>
    (function () {
        const dropzoneEl = document.querySelector('#dropzone-basic');
        const phaseId = <?php echo json_encode($id); ?>;
        let myDropzone = null;

        if (dropzoneEl) {
            myDropzone = new Dropzone(dropzoneEl, {
                url: '#',
                autoProcessQueue: false,
                parallelUploads: 1,
                maxFilesize: 5,
                addRemoveLinks: true,
                maxFiles: 1,
                previewTemplate: `<div class="dz-preview dz-file-preview">
                    <div class="dz-details"><div class="dz-filename" data-dz-name></div><div class="dz-size" data-dz-size></div></div>
                    <div class="dz-error-message"><span data-dz-errormessage></span></div></div>`
            });
        }

        document.querySelector('#productimageForm').addEventListener('submit', async function (event) {
            event.preventDefault();
            $("#btn-submit-productimage").prop("disabled", true);
            $("#loading-indicator").removeClass("d-none");

            const formData = new FormData();
            if (myDropzone && myDropzone.getAcceptedFiles().length > 0) {
                formData.append('image', myDropzone.getAcceptedFiles()[0]);
            }
            const audioFile = document.querySelector('#audio').files[0];
            if (audioFile) formData.append('audio', audioFile);
            formData.append('phase_id', phaseId);
            formData.append('title', document.querySelector('#title').value);
            formData.append('type', document.querySelector('#type').value);

            let url = "{{ route('phaselesson.create', ['id' => $id]) }}";
            @if(isset($phaselesson->id))
                url = "{{ route('phaselesson.update', $phaselesson->id) }}";
            @endif

            try {
                const response = await httpClient.post(url, formData);
                Swal.fire({ icon: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
                setTimeout(() => { window.location = "{{ route('phase.lesson', ['id' => $id]) }}"; }, 1500);
            } catch (e) {
                Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan data.', 'error');
            } finally {
                $("#btn-submit-productimage").prop("disabled", false);
                $("#loading-indicator").addClass("d-none");
            }
        });
    })();
</script>
@endpush
