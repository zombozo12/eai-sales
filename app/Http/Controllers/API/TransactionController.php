<?php

namespace App\Http\Controllers\API;

use App\Customer;
use App\Http\Controllers\Controller;
use App\Transaction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    private function isCustomerExists($customer_id){
        try{
            Customer::findOrFail($customer_id);
        }catch(ModelNotFoundException $ex){
            return false;
        }

        return true;
    }

    public function getAll(){
        $transaction = Transaction::all();

        return response()->json([
            'status' => true,
            'transactions' => $transaction
        ]);
    }

    public function getByID($id){
        $transaction = Transaction::find($id);

        if($transaction->count() === 0){
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada transaksi'
            ]);
        }

        return response()->json([
            'status' => true,
            'transactions' => $transaction
        ]);
    }

    public function store(Request $request){
        $rules = [
            'barang_id' => 'required|integer',
            'amount' => 'required|integer',
        ];
        $message = [
            'barang_id.required' => 'Barang ID tidak boleh kosong',
            'barang_id.integer' => 'Barang ID harus berupa angka bulat',
            'amount.required' => 'Jumlah barang tidak boleh kosong',
            'amount.integer' => 'Jumlah barang harus berupa angka bulat',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Customer, mengambil id dari akun yang sedang login ke aplikasi
        $customer = Customer::where('user_id', auth()->user()->id)->first();

        // Cek kalau data customer ada
        //if($this->isCustomerExists(auth())){
	//
        //}

        // Cek kalau barang_id, amount, atau total_price kurang dari 0 atau angka minus.
        if($request->barang_id < 0 OR $request->amount < 0){
            return response()->json([
                'status' => false,
                'message' => 'Angka-angka yang Anda masukkan tidak boleh kurang dari 0'
            ]);
        }

        // Menghitung total harga
        $harga = 10000; // harga barang sesungguhnya belum ada dari API Warehouse, jadi di deklarasi harga = 10000
        $total_price = $request->amount * $harga;

        $transaction = Transaction::create([
            'customer_id' => $customer->id,
            'barang_id' => $request->barang_id,
            'amount' => $request->amount,
            'total_price' => $total_price
        ]);


        if (!$transaction->save()){
            return response()->json([
                'status' => false,
                'message' => 'Transaksi gagal dilakukan'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Transaksi berhasil ditambah',
            'transaction' => Transaction::find($transaction->id)
        ]);
    }
}
