@if (session()->has('success'))
    <div class="alert alert-success mt-2">
        {{ session('success') }}
    </div>
@endif
@if (session()->has('error'))
    <div class="alert alert-danger mt-2">
        {{ session('error') }}
    </div>
@endif
