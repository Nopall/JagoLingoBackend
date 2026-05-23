<div>
    <a href="{{ route('course.form-edit', $id) }}" class="btn btn-sm btn-warning"><span class="fa fa-pencil"></span> Edit</a>
    <button id="btn-delete-course" class="btn btn-sm btn-danger" onclick="deleteById({{ $id }})">
        <div id="loading-indicator" class="spinner-border spinner-border-sm text-default d-none" role="status"></div>
        <span class="fa fa-trash"></span>
        Delete
    </button>
</div>
