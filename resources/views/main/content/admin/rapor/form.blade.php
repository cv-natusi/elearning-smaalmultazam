@extends('main.layouts.index')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Pengaturan Link E-Rapor</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.rapor.iframe.setting.save') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Link Iframe E-Rapor</label>
                <input type="url" name="iframe_url" value="{{ old('iframe_url', $iframe_url) }}" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.rapor.main') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
