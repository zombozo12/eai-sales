@extends('layouts.app')
@push('css')
@endpush

@section('content')
    <div class="container">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false">Password</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <form class="mt-3" method="post" action="{{ route('profile.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" id="nama_lengkap" name="nama_lengkap"
                               value="{{ old('nama_lengkap') ? old('nama_lengkap') : $customer->name }}">
                        @error('nama_lengkap')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="birthday">Tanggal Lahir</label>
                        <input type="text" class="form-control @error('birthday') is-invalid @enderror" id="birthday" name="birthday"
                               value="{{ old('birthday') ? old('birthday') : \Carbon\Carbon::parse($customer->birthday)->format('d-m-Y') }}">
                        <small class="form-text text-muted">Format: dd-mm-yyyy</small>
                        @error('birthday')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" rows="3" name="address">{{ old('address') ? old('address') : $customer->address }}</textarea>
                        @error('address')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                <form class="mt-3" method="post" action="{{ route('profile.password') }}">
                    @csrf
                    <div class="form-group">
                        <label for="password_old">Password lama</label>
                        <input type="password" class="form-control @error('password_old') is-invalid @enderror" id="password_old" name="password_old"
                               value="{{ old('password_old') }}">
                        @error('password_old')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password baru</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password"
                               value="{{ old('password') }}">
                        @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Password konfirmasi</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation"
                               value="{{ old('password_confirmation') }}">
                        @error('password_confirmation')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
@endpush
