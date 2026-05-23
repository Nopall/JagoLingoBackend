@extends('layouts.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/dropzone/dropzone.css') }}" />
@endpush

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-xl-6">
            <div class="menud mb-4">
                <div class="menud-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Course</h5>
                    <small class="text-muted float-end">Create new Course</small>
                </div>
                <div class="menud-body">
                    <form id="courseForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label" for="title">Course title</label>
                            <input type="text" class="form-control" id="title" placeholder="Course title"
                                   name="title" value="{{ $course->title ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Course Description</label>
                            <textarea class="form-control" id="description" placeholder="Course Description"
                                      name="description">{{ $course->description ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Premium Course</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_premium" id="premium_yes" value="1"
                                    {{ isset($course) && $course->is_premium == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="premium_yes">
                                    Yes
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_premium" id="premium_no" value="0"
                                    {{ isset($course) && $course->is_premium == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="premium_no">
                                    No
                                </label>
                            </div>
                        </div>
                        <button id="btn-submit-course" type="submit" class="btn btn-primary">
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
            document.querySelector('#courseForm').addEventListener('submit', async function(event) {
                $("#btn-submit-course").prop("disabled", true);
                $("#loading-indicator").removeClass("d-none");
                const isEdit = '{{ isset($course) }}';

                event.preventDefault();

                const titleCourse = document.querySelector('#title').value;
                const descriptionCourse = document.querySelector('#description').value;
                const isPremium = document.querySelector('input[name="is_premium"]:checked').value;

                const formData = new FormData();
                formData.append('title', titleCourse);
                formData.append('description', descriptionCourse);
                formData.append('is_premium', isPremium);

                let url = "{{ route('course.create') }}";
                @if (isset($course->id))
                    url = "{{ route('course.update', $course->id) }}";
                @endif

                const response = await httpClient.post(url, formData);

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 1500
                });
                $("#btn-submit-course").prop("disabled", false);
                $("#loading-indicator").addClass("d-none");
                window.location = '{{ route('master.course.list') }}';
            });
        })();
    </script>
@endpush
