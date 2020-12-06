<?php

namespace App\Http\Controllers\API;

use App\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    // proses pengecekan customer ada atau tidak
    private function isCustomerExists($customer_id){
        try{
            Customer::findOrFail($customer_id);
        }catch(ModelNotFoundException $ex){
            return false;
        }
        return true;
    }


    // mengambil data customer yang sedang login. atau data customer milik diri sendiri
    public function getCurrent(){
        $customer = Customer::where('user_id', auth()->user()->id)->first();
        // menampilkan data customer sesuai dengan user id-nya
        return response()->json([
            'status' => true,
            'customers' => $customer
        ]);
    }

    public function getByID($customer_id){
        if(!$this->isCustomerExists($customer_id)){
            return response()->json([
                'status' => false,
                'message' => 'Customer tidak ditemukan'
            ]);
        }

        $customer = Customer::find($customer_id);

        return response()->json([
            'status' => true,
            'customers' => $customer
        ]);
    }

    // create atau store
    public function store(Request $request){
        // validasi input mulai dari sini

        // aturan validasi input
        $rules = [
            'name' => 'required|max:150',
            'birthday' => 'required|date_format:d-m-Y',
            'address' => 'required|max:200'
        ];

        // error message ketika aturan tidak terpenuhi
        $message = [
            'name.required' => 'Nama tidak boleh kosong',
            'name.max' => 'Nama tiak boleh lebih dari :max karakter',
            'birthday.required' => 'Tanggal lahir tidak boleh kosong',
            'birthday.date_format' => 'Format tanggal lahir tidak sesuai',
            'address.required' => 'Alamat tidak boleh kosong',
            'address.max' => 'Alamat tidak boleh lebih dari :max karakter'
        ];

        // validator -> adalah sebuah sistem yang memvalidasi input2 dari pengguna
        $validator = Validator::make($request->all(), $rules, $message);

        // jika ada validasi yang tidak terpenuhi, maka akan menampilkan error sesuai dengan message yang ada.
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors() // menampilkan errornya
            ]);
        }
        // validasi input berakhir disini

        // proses insert ke database dengan table Customer
        $customer = Customer::create([
            'user_id' => auth()->user()->id,
            'name' => $request->name,
            'birthday' => Carbon::parse($request->birthday)->format('Y-m-d'), // format tanggal diubah ke year-month-day
            'address' => $request->address
        ]);

        // jika proses insert gagal maka akan menampilkan error
        if (!$customer->save()){
            return response()->json([
                'status' => false,
                'message' => 'Customer gagal ditambah'
            ]);
        }

        // jika proses insert berhasil maka akan menampilkan informasi
        return response()->json([
            'status' => true,
            'message' => 'Customer berhasil ditambah',
            'customer' => Customer::find($customer->id)
        ]);
    }

    public function update($customer_id, Request $request){
        // pengecekan data customer sesuai dengan id customer
        if(!$this->isCustomerExists($customer_id)){
            return response()->json([
                'status' => false,
                'message' => 'Customer tidak ditemukan'
            ]);
        }

        $rules = [
            'name' => 'required|max:150',
            'birthday' => 'required|date_format:d-m-Y',
            'address' => 'required|max:200'
        ];

        $message = [
            'name.required' => 'Nama tidak boleh kosong',
            'name.max' => 'Nama tiak boleh lebih dari :max karakter',
            'birthday.required' => 'Tanggal lahir tidak boleh kosong',
            'birthday.date_format' => 'Format tanggal lahir tidak diketahui',
            'address.required' => 'Alamat tidak boleh kosong',
            'address.max' => 'Alamat tidak boleh lebih dari :max karakter'
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // proses update customer berdasarkan id customer
        $customer = Customer::find($customer_id);
        // data customer di table, akan diubah sesuai apa yang diinput
        $customer->name = $request->name;
        $customer->birthday = Carbon::parse($request->birthday)->format('Y-m-d');
        $customer->address = $request->address;

        // jika proses update gagal maka akan menampilkan error
        if (!$customer->save()){
            return response()->json([
                'status' => false,
                'message' => 'Customer gagal diubah'
            ]);
        }

        // jika proses update berhasil maka akan menampilkan informasi
        return response()->json([
            'status' => true,
            'message' => 'Customer berhasil diubah',
            'customer' => Customer::find($customer->id)
        ]);
    }

     public function delete($customer_id){
         // pengecekan data customer sesuai dengan id customer
         if(!$this->isCustomerExists($customer_id)){
             return response()->json([
                 'status' => false,
                 'message' => 'Customer tidak ditemukan'
             ]);
         }

         // proses delete customer sesuai dengan id customer
        $customer = Customer::find($customer_id);

         // jika proses delete gagal maka akan menampilkan error
         if (!$customer->delete()){
             return response()->json([
                 'status' => false,
                 'message' => 'Customer gagal dihapus'
             ]);
         }

         // jika proses delete berhasil maka akan menampilkan informasi
         return response()->json([
             'status' => true,
             'message' => 'Customer berhasil dihapus'
         ]);
     }
}
