@if(session()->has("success"))
<p class="alert alert-success">{{ session()->get('success') }}</p>
@endif

@if(session()->has("warning"))
<p class="alert alert-warning">{{ session()->get('warning') }}</p>
@endif