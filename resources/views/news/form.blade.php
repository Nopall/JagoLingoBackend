@extends('layouts.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/dropzone/dropzone.css') }}" />
@endpush

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-xl-6">
            <div class="menud mb-4">
                <div class="menud-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">News</h5>
                    <small class="text-muted float-end">Create new News</small>
                </div>f
                <div class="menud-body">
                    <form id="newsForm" enctype="multipart/form-data"> <!-- Add enctype for file upload -->
                        <div class="mb-3">
                            <label class="form-label" for="title">News title</label>
                            <input type="text" class="form-control" id="title" placeholder="News title"
                                name="title" value="{{ $news->title ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="url">News Url</label>
                            <textarea class="form-control" id="url" placeholder="News Url"
                                      name="url">{{ $news->url ?? '' }}</textarea>
                        </div>
                        <button id="btn-submit-news" type="submit" class="btn btn-primary">
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
           
            document.querySelector('#newsForm').addEventListener('submit', async function(event) {
                    $("#btn-submit-news").prop("disabled", true);
                    $("#loading-indicator").removeClass("d-none");
                    const isEdit = '{{ isset($news) }}'

                    event.preventDefault();

                    const titleNews = document.querySelector('#title').value;
                    const urlNews = document.querySelector('#url').value;

                    const formData = new FormData();
                    formData.append('title', titleNews);
                    formData.append('url', urlNews);

                    let url = "{{ route('news.create') }}";
                    @if (isset($news->id))
                        url = "{{ route('news.update', $news->id) }}";
                    @endif

                    const response = await httpClient.post(url, formData);

                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $("#btn-submit-news").prop("disabled", false);
                    $("#loading-indicator").addClass("d-none");
                    window.location = '{{ route('master.news.list') }}';
                });
        })();
    </script>
@endpush
