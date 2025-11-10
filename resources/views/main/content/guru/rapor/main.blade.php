@extends('main.layouts.index')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>E-Rapor (Tampilan Iframe)</h5>
    </div>

    <div class="card-body" style="height:80vh">
        @if(!empty($iframe_url))
            <iframe src="{{ $iframe_url }}" width="100%" height="100%" frameborder="0" allowfullscreen></iframe>
        @else
            <div class="alert alert-warning">
                Link iframe belum diatur oleh admin.
            </div>
        @endif
    </div>
</div>
@endsection
