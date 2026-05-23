@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/formvalidation/dist/css/formValidation.min.css') }}" />
@endpush
<!-- DataTable with Buttons -->
<div class="card">
    <div class="card-datatable table-responsive">
        <table class="datatables-basic table border-top">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Manufacturer</th>
                    <th>Nickname</th>
                    <th>Refueling</th>
                    <th>Expenses</th>
                    <th>Services</th>
                    <th>Incomes</th>
                    <th>Balance</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!--/ DataTable with Buttons -->
@push('js')
    <script src="{{ asset('assets/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/tables-datatables-basic.js') }}"></script>
    <script src="{{ asset('assets/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
@endpush
