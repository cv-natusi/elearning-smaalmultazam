@extends('main.layouts.index')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>E-Rapor </h5>

        <form action="{{ route('admin.rapor.iframe.setting') }}" method="GET" class="float-end d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-primary">
                <i class="bx bx-cog"></i> Pengaturan
            </button>
        </form>
    </div>

    <div class="card-body" style="height:80vh">
        @if(!empty($iframe_url))
            <iframe src="{{ $iframe_url }}" width="100%" height="100%" frameborder="0" allowfullscreen></iframe>
        @else
            <div class="alert alert-warning">
                Link iframe belum diatur. Silakan klik tombol <b>Pengaturan</b>.
            </div>
        @endif
    </div>
</div>
@endsection
