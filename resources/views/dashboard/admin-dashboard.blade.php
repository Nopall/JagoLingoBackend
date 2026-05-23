@extends('layouts.index')

@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-7 col-sm-7 mb-4">
            <x-card-statistic :label="'Total users'" :value="'150'" :description="'Last Week Analytics'" :icon="'bx-user'" />
        </div>
    </div>
@endsection
