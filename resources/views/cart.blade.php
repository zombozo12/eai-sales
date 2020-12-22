@extends('layouts.app')
@push('css')
    <style>
        .table > tbody > tr > td, .table > tfoot > tr > td {
            vertical-align: middle;
        }

        @media screen and (max-width: 600px) {
            table#cart tbody td .form-control {
                width: 20%;
                display: inline !important;
            }

            .actions .btn {
                width: 36%;
                margin: 1.5em 0;
            }

            .actions .btn-info {
                float: left;
            }

            .actions .btn-danger {
                float: right;
            }

            table#cart thead {
                display: none;
            }

            table#cart tbody td {
                display: block;
                padding: .6rem;
                min-width: 320px;
            }

            table#cart tbody tr td:first-child {
                background: #333;
                color: #fff;
            }

            table#cart tbody td:before {
                content: attr(data-th);
                font-weight: bold;
                display: inline-block;
                width: 8rem;
            }


            table#cart tfoot td {
                display: block;
            }

            table#cart tfoot td .btn {
                display: block;
            }

        }
    </style>
@endpush

@section('content')
    <div class="container">
        <table id="cart" class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th style="width:50%">Product</th>
                    <th style="width:13%">Price</th>
                    <th style="width:5%">Quantity</th>
                    <th style="width:22%" class="text-center">Subtotal</th>
                    <th style="width:10%">Actions</th>
                </tr>
            </thead>
            <tbody>
            @if(isset($carts))
                @foreach($carts as $cart)
                    <tr>
                        <td data-th="Product">
                            <div class="row">
                                <div class="col-sm-2 hidden-xs"><img src="http://placehold.it/100x100" alt="..." class="img-responsive"/></div>
                                <div class="col-sm-10">
                                    <h4 class="ml-3">{{ $cart['nama_barang'] }}</h4>
                                    <p class="ml-3">{{ $cart['des_barang'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td data-th="Price">Rp {{ number_format($cart['harga_barang']) }}</td>
                        <td data-th="Quantity">
                            <input type="number" class="form-control text-center" value="{{ number_format($cart['qty_barang']) }}">
                        </td>
                        <td data-th="Subtotal" class="text-center">{{ number_format($cart['harga_barang'] * $cart['qty_barang']) }}</td>
                        <td class="actions" data-th="Actions">
                            <a class="btn btn-danger btn-sm" href="{{ route('cart.delete', $cart['id_barang']) }}" role="button"><i class="fa fa-trash-o"></i> Delete</a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">
                        {{ __('Anda belum menambahkan barang') }}
                    </td>
                </tr>
            @endif
            </tbody>
            <tfoot>
                <tr>
                    <td><a href="{{ route('home') }}" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue Shopping</a></td>
                    <td colspan="2" class="hidden-xs"></td>
                    <td class="hidden-xs text-center"><strong>Total Rp {{ number_format($prices) }}</strong></td>
                    <td><a href="{{ route('cart.purchase') }}" class="btn btn-success btn-block">Bayar <i class="fa fa-angle-right"></i></a></td>
                </tr>
            </tfoot>
        </table>
@endsection
@push('script')
@endpush
