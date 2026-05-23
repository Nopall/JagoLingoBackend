@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/apex-chart/apex-charts.css') }}" />
@endpush

<div class="row">
    <!-- Donut Chart -->
    <div class="col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title mb-0">Expense Ratio</h5>
                    <small class="text-muted">Spending on various categories</small>
                </div>
                <div class="dropdown d-none d-sm-flex">
                    <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bx bx-calendar"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Today</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Yesterday</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last 7
                                Days</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last 30
                                Days</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Current
                                Month</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last Month</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div id="donutChart"></div>
            </div>
        </div>
    </div>
    <!-- /Donut Chart -->

    <!-- Line Chart -->
    <div class="col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div>
                    <h5 class="card-title mb-0">Montly Expenses Chart</h5>
                </div>
                <div class="d-sm-flex d-none align-items-center">
                    <h5 class="fw-bold mb-0 me-3">IDR 250.000</h5>
                    <span class="badge bg-label-secondary">
                        <i class="bx bx-down-arrow-alt bx-xs text-danger"></i>
                        <span class="align-middle">20%</span>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div id="lineChart"></div>
            </div>
        </div>
    </div>
    <!-- /Line Chart -->
</div>




@push('js')
    <script src="{{ asset('assets/libs/apex-chart/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/charts-apex.js') }}"></script>
@endpush
