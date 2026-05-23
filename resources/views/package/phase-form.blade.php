@extends('layouts.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/dropzone/dropzone.css') }}" />
@endpush

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-xl-6">
            <div class="menud mb-4">
                <div class="menud-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Phase</h5>
                    <small class="text-muted float-end">Create new Phase</small>
                </div>
                <div class="menud-body">
                    <form id="phaseForm" enctype="multipart/form-data">
                        <!-- Hidden input for package ID -->
                        <input type="hidden" name="package_id" value="{{ $package->id }}">

                        <div class="mb-3">
                            <label class="form-label" for="name">Phase name</label>
                            <input type="text" class="form-control" id="name" placeholder="Phase name"
                                name="name" value="{{ $phase->phase_title ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="course">Pilih Course</label>
                            <select class="form-select" id="course" name="course">
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" {{ isset($phase) && $phase->course_id == $course->id ? 'selected' : '' }}>
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Phase Description</label>
                            <input type="text" class="form-control" id="description" placeholder="Phase description"
                                name="description" value="{{ $phase->description ?? '' }}">
                        </div>
                        <button id="btn-submit-phase" type="submit" class="btn btn-primary">
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
            document.querySelector('#phaseForm').addEventListener('submit', async function(event) {
                $("#btn-submit-phase").prop("disabled", true);
                $("#loading-indicator").removeClass("d-none");

                event.preventDefault();

                const isEdit = '{{ isset($phase) }}';
                const phaseName = document.querySelector('#name').value;
                const phaseDescription = document.querySelector('#description').value;
                const courseId = document.querySelector('#course').value;
                const packageId = '{{ $package->id }}';

                const formData = new FormData();
                formData.append('phase_title', phaseName);
                formData.append('description', phaseDescription);
                formData.append('course_id', courseId);
                formData.append('package_id', packageId);

                let url = "{{ route('package.phase.create', $package->id) }}";
                @if (isset($phase->id))
                    url = "{{ route('phase.update', $phase->id) }}";
                @endif

                try {
                    const response = await httpClient.post(url, formData);

                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });

                    window.location = '{{ route('package.phase.detail', $package->id) }}';
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal menyimpan data'
                    });
                } finally {
                    $("#btn-submit-phase").prop("disabled", false);
                    $("#loading-indicator").addClass("d-none");
                }
            });
        })();
    </script>
@endpush
