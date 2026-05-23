@extends('layouts.index')

@section('title', isset($course) ? 'Edit Course' : 'Tambah Course')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-7 col-lg-9">

        <div class="mb-3">
            <a href="{{ route('master.course.list') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="avatar-initial rounded bg-label-primary p-2" style="font-size: 1.4rem;">
                        <i class="bx bx-book-open"></i>
                    </span>
                    <div>
                        <h5 class="card-title mb-0 fw-semibold">
                            {{ isset($course) ? 'Edit Course' : 'Tambah Course Baru' }}
                        </h5>
                        <small class="text-muted">{{ isset($course) ? 'Perbarui informasi kursus' : 'Isi form di bawah untuk menambah kursus' }}</small>
                    </div>
                </div>
            </div>
            <div class="card-body py-4">
                <form id="courseForm" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="form-label fw-medium" for="title">Judul Course <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" placeholder="Masukkan judul course"
                               name="title" value="{{ $course->title ?? '' }}">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium" for="description">Deskripsi Course <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" placeholder="Masukkan deskripsi course"
                                  name="description" rows="4">{{ $course->description ?? '' }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium">Tipe Course</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_premium" id="premium_yes" value="1"
                                    {{ isset($course) && $course->is_premium == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="premium_yes">
                                    <span class="badge bg-label-warning me-1"><i class="bx bx-crown"></i></span> Premium
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_premium" id="premium_no" value="0"
                                    {{ !isset($course) || $course->is_premium == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="premium_no">
                                    <span class="badge bg-label-success me-1"><i class="bx bx-lock-open"></i></span> Gratis
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button id="btn-submit-course" type="submit" class="btn btn-primary">
                            <div id="loading-indicator" class="spinner-border spinner-border-sm d-none me-1" role="status"></div>
                            <i class="bx bx-save me-1"></i>
                            {{ isset($course) ? 'Simpan Perubahan' : 'Tambah Course' }}
                        </button>
                        <a href="{{ route('master.course.list') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection

@push('js')
<script>
    document.querySelector('#courseForm').addEventListener('submit', async function (event) {
        event.preventDefault();
        $("#btn-submit-course").prop("disabled", true);
        $("#loading-indicator").removeClass("d-none");

        const formData = new FormData();
        formData.append('title', document.querySelector('#title').value);
        formData.append('description', document.querySelector('#description').value);
        formData.append('is_premium', document.querySelector('input[name="is_premium"]:checked').value);

        let url = "{{ route('course.create') }}";
        @if(isset($course->id))
            url = "{{ route('course.update', $course->id) }}";
        @endif

        try {
            const response = await httpClient.post(url, formData);
            Swal.fire({ icon: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
            setTimeout(() => { window.location = '{{ route('master.course.list') }}'; }, 1500);
        } catch (e) {
            Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan data.', 'error');
        } finally {
            $("#btn-submit-course").prop("disabled", false);
            $("#loading-indicator").addClass("d-none");
        }
    });
</script>
@endpush
