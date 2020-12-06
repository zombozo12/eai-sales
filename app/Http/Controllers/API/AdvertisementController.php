<?php

namespace App\Http\Controllers\API;

use App\Advertisement;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdvertisementController extends Controller
{
    // proses menampilkan semua iklan
    public function index(){
        return response()->json([
            'status' => true,
            'advertisements' => Advertisement::all() // menampilkan semua iklan
        ]);
    }

    // proses menampilkan iklan sesuai dengan id iklan
    public function getByID($id){
        try{
            $advertisement = Advertisement::findOrFail($id);
        }catch(ModelNotFoundException $ex){
            // jika gagal
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada pengiklanan'
            ]);
        }

        // jika berhasil
        return response()->json([
            'status' => true,
            'advertisement' => $advertisement
        ]);
    }

    public function create(Request $request){
        // aturan validasi input
        $rules = [
            'title' => 'required|max:150',
            'description' => 'required|max:250',
            'platform' => 'required|max:150',
            'duration' => 'required|date_format:H:i',
            'price' => 'required|integer'
        ];

        // error message ketika aturan tidak terpenuhi
        $message = [
            'title.required' => 'Judul harus diisi',
            'title.max' => 'Judul tidak boleh lebih dari :max karakter',
            'description.required' => 'Deskripsi tidak boleh kosong',
            'description.max' => 'Deskripsi tidak boleh lebih dari :max karakter',
            'platform.required' => 'Platform tidak boleh kosong',
            'platform.max' => 'Platform tidak boleh lebih dari :max',
            'duration.required' => 'Durasi tidak boleh kosong',
            'duration.date_format' => 'Format durasi tidak diketahui, Contoh: H:i -> Jam:Menit',
            'price.required' => 'Harga tidak boleh kosong',
            'price.integer' => 'Harga harus berupa angka'
        ];

        // validator -> adalah sebuah sistem yang memvalidasi input2 dari pengguna
        $validator = Validator::make($request->all(), $rules, $message);

        // jika ada validasi yang tidak terpenuhi, maka akan menampilkan error sesuai dengan message yang ada.
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // proses input ke database dimasukkan ke table advertisement
        $advertisement = Advertisement::create([
            'title' => $request->title,
            'description' => $request->description,
            'platform' => $request->platform,
            'duration' => $request->duration,
            'price' => $request->price
        ]);

        // jika gagal insert maka akan menampilkan error
        if(!$advertisement->save()){
            return response()->json([
                'status' => false,
                'message' => 'Pengiklanan gagal dibuat'
            ]);
        }

        // jika berhasil akan menampilkan informasi
        return response()->json([
            'status' => true,
            'message' => 'Pengiklanan berhasil ditambah',
            'advertisements' => Advertisement::find($advertisement->id)
        ]);
    }
}
