@extends('layouts.app')

@push('css')
    <style>
        .card-img-top {
            width: 100%;
            height: 10vw;
            object-fit: cover;
        }

        .card-body {
            width: 100%;
            height: 7vw;
        }

        .card {
            box-shadow: 0 0 0 grey;
            -webkit-transition: box-shadow .15s ease-out;
        }

        .card:hover {
            box-shadow: 1px 8px 20px grey;
            -webkit-transition: box-shadow .15s ease-in;
        }

        a.custom-card,
        a.custom-card:hover {
            color: inherit;
            text-decoration: none;
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <div class="row row-cols-1 row-cols-md-3">
            @foreach($items as $item)
            <div class="col mb-4">
                <a href="{{ route('detail', $item['id_barang']) }}" class="custom-card">
                    <div class="card border-0 img-hover-zoom">
                        <img
                            src="https://picsum.photos/200"
                            class="card-img-top" alt="{{ __('...') }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item['nama_barang'] }}</h5>
                            <p class="card-text">{{ Str::words($item['des_barang'], 11) }}</p>
                        </div>
                        <div class="card-footer border-0 bg-white">
                            {{ 'Rp ' . number_format($item['harga_barang'], 0) }}
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
@endsection
