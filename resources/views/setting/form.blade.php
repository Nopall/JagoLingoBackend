@extends('layouts.index')

@section('title', isset($setting) ? 'Edit Setting' : 'Tambah Setting')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">

        <div class="mb-3">
            <a href="{{ route('master.setting.list') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="avatar-initial rounded bg-label-secondary p-2" style="font-size: 1.4rem;">
                        <i class="bx bx-cog"></i>
                    </span>
                    <div>
                        <h5 class="card-title mb-0 fw-semibold">
                            {{ isset($setting) ? 'Edit Setting' : 'Tambah Setting Baru' }}
                        </h5>
                        <small class="text-muted">{{ isset($setting) ? 'Perbarui konfigurasi' : 'Tambah konfigurasi baru' }}</small>
                    </div>
                </div>
            </div>
            <div class="card-body py-4">
                <form id="settingForm" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="form-label fw-medium" for="teks">Setting Key <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="teks" placeholder="Contoh: app_name, contact_email"
                               name="teks" value="{{ $setting->teks ?? '' }}">
                        <div class="form-text">Gunakan format snake_case untuk key.</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium" for="content">Setting Content <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" placeholder="Isi konten setting"
                                  name="content">{{ $setting->content ?? '' }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button id="btn-submit-setting" type="submit" class="btn btn-primary">
                            <div id="loading-indicator" class="spinner-border spinner-border-sm d-none me-1" role="status"></div>
                            <i class="bx bx-save me-1"></i>
                            {{ isset($setting) ? 'Simpan Perubahan' : 'Tambah Setting' }}
                        </button>
                        <a href="{{ route('master.setting.list') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#content').summernote({
            height: 220,
            placeholder: 'Tuliskan konten di sini...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>
@endsection

@push('js')
<script>
    document.querySelector('#settingForm').addEventListener('submit', async function (event) {
        event.preventDefault();
        $("#btn-submit-setting").prop("disabled", true);
        $("#loading-indicator").removeClass("d-none");

        const formData = new FormData();
        formData.append('teks', document.querySelector('#teks').value);
        formData.append('content', $('#content').summernote('code'));

        let url = "{{ route('setting.create') }}";
        @if(isset($setting->id))
            url = "{{ route('setting.update', $setting->id) }}";
        @endif

        try {
            const response = await httpClient.post(url, formData);
            Swal.fire({ icon: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
            setTimeout(() => { window.location = '{{ route('master.setting.list') }}'; }, 1500);
        } catch (e) {
            Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan data.', 'error');
        } finally {
            $("#btn-submit-setting").prop("disabled", false);
            $("#loading-indicator").addClass("d-none");
        }
    });
</script>
@endpush
