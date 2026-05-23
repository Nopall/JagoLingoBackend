@extends('layouts.index')

@section('title', 'Ubah Password User')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-6 col-lg-8">

        <div class="mb-3">
            <a href="{{ route('user.list') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="avatar-initial rounded bg-label-warning p-2" style="font-size: 1.4rem;">
                        <i class="bx bx-key"></i>
                    </span>
                    <div>
                        <h5 class="card-title mb-0 fw-semibold">Ubah Password User</h5>
                        <small class="text-muted">{{ $user->name ?? '' }} &mdash; {{ $user->email ?? '' }}</small>
                    </div>
                </div>
            </div>
            <div class="card-body py-4">
                <form id="settingForm" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="form-label fw-medium" for="password">Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" placeholder="Masukkan password baru"
                                   name="password">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bx bx-show" id="eyeIcon"></i>
                            </button>
                        </div>
                        <div class="form-text">Minimal 8 karakter.</div>
                    </div>
                    <div class="d-flex gap-2">
                        <button id="btn-submit-setting" type="submit" class="btn btn-primary">
                            <div id="loading-indicator" class="spinner-border spinner-border-sm d-none me-1" role="status"></div>
                            <i class="bx bx-save me-1"></i> Simpan Password
                        </button>
                        <a href="{{ route('user.list') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection

@push('js')
<script>
    document.querySelector('#togglePassword').addEventListener('click', function () {
        const input = document.querySelector('#password');
        const icon = document.querySelector('#eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bx-show', 'bx-hide');
        } else {
            input.type = 'password';
            icon.classList.replace('bx-hide', 'bx-show');
        }
    });

    document.querySelector('#settingForm').addEventListener('submit', async function (event) {
        event.preventDefault();
        $("#btn-submit-setting").prop("disabled", true);
        $("#loading-indicator").removeClass("d-none");

        const formData = new FormData();
        formData.append('password', document.querySelector('#password').value);

        let url = "";
        @if(isset($user->id))
            url = "{{ route('user.update', $user->id) }}";
        @endif

        try {
            const response = await httpClient.post(url, formData);
            Swal.fire({ icon: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
            setTimeout(() => { window.location = '{{ route('user.list') }}'; }, 1500);
        } catch (e) {
            Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan data.', 'error');
        } finally {
            $("#btn-submit-setting").prop("disabled", false);
            $("#loading-indicator").addClass("d-none");
        }
    });
</script>
@endpush
