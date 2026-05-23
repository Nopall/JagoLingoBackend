@extends('layouts.index')

@section('title', isset($phase) ? 'Edit Phase' : 'Tambah Phase')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-7 col-lg-9">

        <div class="mb-3">
            <a href="{{ route('master.phase.list') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="avatar-initial rounded bg-label-info p-2" style="font-size: 1.4rem;">
                        <i class="bx bx-list-ul"></i>
                    </span>
                    <div>
                        <h5 class="card-title mb-0 fw-semibold">
                            {{ isset($phase) ? 'Edit Phase' : 'Tambah Phase Baru' }}
                        </h5>
                        <small class="text-muted">{{ isset($phase) ? 'Perbarui informasi fase' : 'Isi form di bawah untuk menambah fase' }}</small>
                    </div>
                </div>
            </div>
            <div class="card-body py-4">
                <form id="phaseForm" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="form-label fw-medium" for="name">Nama Phase <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" placeholder="Masukkan nama phase"
                               name="name" value="{{ $phase->phase_title ?? '' }}">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium" for="course">Course <span class="text-danger">*</span></label>
                        <select class="form-select" id="course" name="course">
                            <option value="" disabled {{ !isset($phase) ? 'selected' : '' }}>Pilih course...</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" {{ isset($phase) && $phase->course_id == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium" for="description">Deskripsi Phase</label>
                        <input type="text" class="form-control" id="description" placeholder="Masukkan deskripsi phase"
                               name="description" value="{{ $phase->description ?? '' }}">
                    </div>
                    <div class="d-flex gap-2">
                        <button id="btn-submit-phase" type="submit" class="btn btn-primary">
                            <div id="loading-indicator" class="spinner-border spinner-border-sm d-none me-1" role="status"></div>
                            <i class="bx bx-save me-1"></i>
                            {{ isset($phase) ? 'Simpan Perubahan' : 'Tambah Phase' }}
                        </button>
                        <a href="{{ route('master.phase.list') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection

@push('js')
<script>
    document.querySelector('#phaseForm').addEventListener('submit', async function (event) {
        event.preventDefault();
        $("#btn-submit-phase").prop("disabled", true);
        $("#loading-indicator").removeClass("d-none");

        const formData = new FormData();
        formData.append('phase_title', document.querySelector('#name').value);
        formData.append('description', document.querySelector('#description').value);
        formData.append('course_id', document.querySelector('#course').value);

        let url = "{{ route('phase.create') }}";
        @if(isset($phase->id))
            url = "{{ route('phase.update', $phase->id) }}";
        @endif

        try {
            const response = await httpClient.post(url, formData);
            Swal.fire({ icon: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
            setTimeout(() => { window.location = '{{ route('master.phase.list') }}'; }, 1500);
        } catch (e) {
            Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan data.', 'error');
        } finally {
            $("#btn-submit-phase").prop("disabled", false);
            $("#loading-indicator").addClass("d-none");
        }
    });
</script>
@endpush
