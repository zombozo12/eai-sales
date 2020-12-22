@extends('layouts.app')
@push('css')
    <style>
        .card-img-top {
            width: 5vw;
            height: 5vw;
            object-fit: cover;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-6">
                <img src="https://picsum.photos/1080" class="img-fluid">
            </div>
            <div class="col-6">
                <h1 class="font-weight-bold">{{ $detail['nama_barang'] }}</h1>
                <h2>Rp {{ number_format($detail['harga_barang']) }}</h2>
                <p>Stok Tersedia: <span class="text-primary">{{ $detail['barang_masuk'] }}</span></p>
                <p>Kode Produk: <span class="text-primary">{{ $detail['lokasi_penyimpanan'] . ' - ' .$detail['id_barang'] }}</span></p>

                <p class="text-justify">{{ $detail['des_barang'] }}</p>

                <form method="post" action="{{ route('cart.add', request()->barang_id) }}">
                    @csrf
                    <div class="input-group">
                        <input type="number" class="form-row @error('quantity') is-invalid @enderror" name="quantity" placeholder="Quantity" aria-label="Jumlah Pesanan" aria-describedby="btn_cart">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit" id="btn_cart">Add to Cart</button>
                        </div>
                        @error('quantity')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="row">
                    <div class="col">
                        <div class="card border-0 img-hover-zoom">
                            <img
                                src="https://picsum.photos/100"
                                class="card-img-top" alt="{{ __('...') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="card border-0 img-hover-zoom">
                            <img
                                src="https://picsum.photos/100"
                                class="card-img-top" alt="{{ __('...') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="card border-0 img-hover-zoom">
                            <img
                                src="https://picsum.photos/100"
                                class="card-img-top" alt="{{ __('...') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="card border-0 img-hover-zoom">
                            <img
                                src="https://picsum.photos/100"
                                class="card-img-top" alt="{{ __('...') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
            </div>
        </div>
    </div>
@endsection
@push('script')
@endpush
