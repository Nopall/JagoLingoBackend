@extends('layouts.index')

@section('title', isset($package) ? 'Edit Package' : 'Tambah Package')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-7 col-lg-9">

        <div class="mb-3">
            <a href="{{ route('master.package.list') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="avatar-initial rounded bg-label-success p-2" style="font-size: 1.4rem;">
                        <i class="bx bx-package"></i>
                    </span>
                    <div>
                        <h5 class="card-title mb-0 fw-semibold">
                            {{ isset($package) ? 'Edit Package' : 'Tambah Package Baru' }}
                        </h5>
                        <small class="text-muted">{{ isset($package) ? 'Perbarui informasi paket' : 'Isi form di bawah untuk menambah paket' }}</small>
                    </div>
                </div>
            </div>
            <div class="card-body py-4">
                <form id="packageForm" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="form-label fw-medium" for="name">Nama Package <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" placeholder="Masukkan nama package"
                               name="name" value="{{ $package->name ?? '' }}">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium" for="price">Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="price" placeholder="0"
                                   name="price" value="{{ $package->price ?? '' }}">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium" for="is_active">Status</label>
                        <select class="form-select" id="is_active" name="is_active">
                            <option value="1" {{ (isset($package) && $package->is_active == 1) ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ (isset($package) && $package->is_active == 0) ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button id="btn-submit-package" type="submit" class="btn btn-primary">
                            <div id="loading-indicator" class="spinner-border spinner-border-sm d-none me-1" role="status"></div>
                            <i class="bx bx-save me-1"></i>
                            {{ isset($package) ? 'Simpan Perubahan' : 'Tambah Package' }}
                        </button>
                        <a href="{{ route('master.package.list') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection

@push('js')
<script>
    document.querySelector('#packageForm').addEventListener('submit', async function (event) {
        event.preventDefault();
        $("#btn-submit-package").prop("disabled", true);
        $("#loading-indicator").removeClass("d-none");

        const formData = new FormData();
        formData.append('name', document.querySelector('#name').value);
        formData.append('price', document.querySelector('#price').value);
        formData.append('is_active', document.querySelector('#is_active').value);

        let url = "{{ route('package.create') }}";
        @if(isset($package->id))
            url = "{{ route('package.update', $package->id) }}";
        @endif

        try {
            const response = await httpClient.post(url, formData);
            Swal.fire({ icon: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
            setTimeout(() => { window.location = '{{ route('master.package.list') }}'; }, 1500);
        } catch (e) {
            Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan data.', 'error');
        } finally {
            $("#btn-submit-package").prop("disabled", false);
            $("#loading-indicator").addClass("d-none");
        }
    });
</script>
@endpush
