<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Transaction;
use Illuminate\Http\Request;

class CartController extends Controller{


    public function index(){
        $cart = session()->get('cart');

        $total = 0;
        if(isset($cart)){
            foreach($cart as $data){
                $total += $data['harga_barang'] * $data['qty_barang'];
            }
        }

        return view('cart')
            ->with('carts', $cart)
            ->with('prices', $total);
    }

    public function add($barang_id, Request $request){
        $rules = [
            'quantity' => 'required|max:10|min:1|numeric'
        ];

        $message = [
            'quantity.required' => 'Jumlah tidak boleh kosong',
            'quantity.min' => 'Jumlah tidak boleh kurang dari :min',
            'quantity.max' => 'Jumlah tidak boleh lebih dari :max',
            'quantity.numeric' => 'Jumlah tidak diketahui',
        ];

        $this->validate($request, $rules, $message);

        $cart = session()->get('cart');

        $data = $this->search($this->BASE_RESPONSE, 'id_barang', $barang_id);
        if(!$cart){
            $cart = [
                $barang_id => [
                    'id_barang' => $data[0]['id_barang'],
                    'nama_barang' => $data[0]['nama_barang'],
                    'des_barang' => $data[0]['des_barang'],
                    'qty_barang' => $request->quantity,
                    'harga_barang' => $data[0]['harga_barang']
                ]
            ];
            session()->put('cart', $cart);
            alert()->success('Cart', 'Barang berhasil disimpan ke dalam cart!');
            return redirect()->back();
        }

        if(isset($cart[$barang_id])){
            $cart[$barang_id]['qty_barang'] = $request->quantity;

            session()->put('cart', $cart);
            alert()->success('Cart', 'Berhasil mengubah jumlah barang di dalam cart!');
            return redirect()->back();
        }

        $cart[$barang_id] = [
            'id_barang' => $data[0]['id_barang'],
            'nama_barang' => $data[0]['nama_barang'],
            'des_barang' => $data[0]['des_barang'],
            'qty_barang' => $request->quantity,
            'harga_barang' => $data[0]['harga_barang']
        ];
        session()->put('cart', $cart);
        alert()->success('Cart', 'Barang berhasil disimpan ke dalam cart!');
        return redirect()->back();
    }

    public function delete($barang_id){
        $cart = session()->get('cart');
        if(isset($cart)){
            unset($cart[$barang_id]);
            session()->put('cart', $cart);
        }

        alert('Cart', 'Berhasil menghapus barang dari cart');
        return redirect()->back();
    }

    public function purchase(){
        $cart = session()->get('cart');
        $customer = Customer::where('user_id', auth()->id())->first();

        foreach($cart as $data){
            $transaction = Transaction::create([
                'customer_id' => $customer->id,
                'barang_id' => $data['id_barang'],
                'amount' => $data['qty_barang'],
                'total_price' => $data['harga_barang'] * $data['qty_barang'],
                'status' => 'purchased'
            ]);
        }

        alert('Cart', 'Barang-barang berhasil dibeli');
        return redirect()->route('home');
    }
}
