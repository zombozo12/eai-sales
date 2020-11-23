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

    private function isCustomerExists($customer_id){
        try{
            Customer::findOrFail($customer_id);
        }catch(ModelNotFoundException $ex){
            return false;
        }

        return true;
    }

    public function getCurrent(){
        $customer = Customer::where('user_id', auth()->user()->id)->first();
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

    public function store(Request $request){
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

        $customer = Customer::create([
            'user_id' => auth()->user()->id,
            'name' => $request->name,
            'birthday' => Carbon::parse($request->birthday)->format('Y-m-d'),
            'address' => $request->address
        ]);

        if (!$customer->save()){
            return response()->json([
                'status' => false,
                'message' => 'Customer gagal ditambah'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Customer berhasil ditambah',
            'customer' => Customer::find($customer->id)
        ]);
    }

    public function update($customer_id, Request $request){
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

        $customer = Customer::find($customer_id);
        $customer->name = $request->name;
        $customer->birthday = Carbon::parse($request->birthday)->format('Y-m-d');
        $customer->address = $request->address;

        if (!$customer->save()){
            return response()->json([
                'status' => false,
                'message' => 'Customer gagal diubah'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Customer berhasil diubah',
            'customer' => Customer::find($customer->id)
        ]);
    }

     public function delete($customer_id){
         if(!$this->isCustomerExists($customer_id)){
             return response()->json([
                 'status' => false,
                 'message' => 'Customer tidak ditemukan'
             ]);
         }

        $customer = Customer::find($customer_id);

         if (!$customer->delete()){
             return response()->json([
                 'status' => false,
                 'message' => 'Customer gagal dihapus'
             ]);
         }

         return response()->json([
             'status' => true,
             'message' => 'Customer berhasil dihapus'
         ]);
     }
}
