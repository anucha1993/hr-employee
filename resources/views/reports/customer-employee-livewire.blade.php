@extends('layouts.vertical-main')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @livewire('reports.simple-test')
            <hr>
            <div class="mt-4">
                <!-- Try different ways to call the component -->
                @livewire('reports.customer_employee_report')
            </div>
        </div>
    </div>
</div>
@endsection
