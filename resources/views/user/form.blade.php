@extends('layouts.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/dropzone/dropzone.css') }}" />
@endpush

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-xl-6">
            <div class="menud mb-4">
                <div class="menud-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">User</h5>
                    <small class="text-muted float-end">Edit User</small>
                </div>
                <div class="menud-body">
                    <form id="settingForm" enctype="multipart/form-data"> <!-- Add enctype for file upload -->
                        <div class="mb-3">
                            <label class="form-label" for="teks">New Password</label>
                            <input type="password" class="form-control" id="password" placeholder="New Password"
                                name="password" value="">
                        </div>
                        <button id="btn-submit-setting" type="submit" class="btn btn-primary">
                            <div id="loading-indicator" class="spinner-border spinner-border-sm text-default d-none"
                                role="status"></div> Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Tambahkan script CKEditor -->
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#content').summernote({
                height: 200, // Atur tinggi editor
                placeholder: 'Tuliskan konten di sini...',
                toolbar: [
                    // Atur toolbar sesuai kebutuhan
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endsection



@push('js')
    <script src="{{ asset('assets/libs/dropzone/dropzone.js') }}"></script>
    <script>
        (function() {
           
            document.querySelector('#settingForm').addEventListener('submit', async function(event) {
                    $("#btn-submit-setting").prop("disabled", true);
                    $("#loading-indicator").removeClass("d-none");
                    const isEdit = '{{ isset($user) }}'

                    event.preventDefault();

                    const password = document.querySelector('#password').value;

                    const formData = new FormData();
                    formData.append('password', password);

                    let url = "";
                    @if (isset($user->id))
                        url = "{{ route('user.update', $user->id) }}";
                    @endif

                    const response = await httpClient.post(url, formData);

                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $("#btn-submit-setting").prop("disabled", false);
                    $("#loading-indicator").addClass("d-none");
                    window.location = '{{ route('user.list') }}';
                });
        })();
    </script>
@endpush
