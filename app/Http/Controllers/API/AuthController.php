<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    // function login
    public function login(Request $request){
        // rules -> adalah sebuah aturan input yang dibuat untuk pengguna. rules menggunakan tipe data array.
        // Jenis array-nya adalah Associative arrays.
        $rules = [
            'username' => 'required|max:50', // required -> data yang diberikan oleh pengguna harus ada atau tidak boleh kosong. max -> maksimal karakter
            'password' => 'required|min:8|max:150',
        ];

        // message -> adalah sebuah feedback error ketika data yang masuk ke server tidak sesuai aturan
        $message = [
            'username.required' => 'Email atau username tidak boleh kosong',
            'username.max' => 'Email tidak boleh lebih dari :max karakter',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password tidak boleh kurang dari :min karakter',
            'password.max' => 'Password tidak boleh lebih dari :max karakter',
        ];

        // validator -> adalah sebuah sistem yang memvalidasi input2 dari pengguna
        $validator = Validator::make($request->all(), $rules, $message);

        // Pengecekan apakah ada input data dari pengguna yang tidak sesuai aturan
        if($validator->fails()){
            // server akan memberikan feedback kalau terjadi kesalahan dalam bentuk JSON. Isi JSON-nya ada status dan errors
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_OK);
        }

        // Pengecekan apakah user menggunakan email atau username.
        $loginType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $auth = auth()->attempt([
            $loginType => $request->username,
            'password' => $request->password
        ]);

        // pengecekan login berhasil atau tidak. Jika login gagal, server akan menampilkan message error.
        if ($auth === false) {
            return response()->json([
                'status' => false,
                'message' => 'Username/Password Anda salah'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // generate token agar bisa digunakan user dalam fitur2 lain di server.
        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        // setelah login berhasil, server akan memberikan feedback berupa informasi status, message, info user, dan token.
        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'user' => auth()->user(),
            'access_token' => $accessToken
        ], Response::HTTP_OK);
    }

    public function register(Request $request){
        $rules = [
            'username' => 'required|max:20|unique:users',
            'email' => 'email|required|unique:users|max:254',
            'password' => 'required|min:8|max:100',
        ];

        $message = [
            'username.required' => 'Username tidak boleh kosong',
            'username.max' => 'Username tidak boleh lebih dari :max karakter',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email tidak boleh kosong',
            'email.unique' => 'Email sudah digunakan',
            'email.email' => 'Format email salah',
            'email.max' => 'Email tidak boleh lebih dari :max karakter',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password tidak boleh kurang dari :min karakter',
            'password.max' => 'Password tidak boleh lebih dari :max karakter',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Memasukkan data registrasi ke dalam database dengan table User. Data yang dimasukkan adalah username, email, dan password.
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password) // bcrypt -> Blowfish Encryption.
        ]);

        // generate token agar bisa digunakan user dalam fitur2 lain di server.
        $accessToken = $user->createToken('authToken')->accessToken;

        // setelah registrasi berhasil, server akan memberikan feedback berupa informasi status, message, info user, dan token.
        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil',
            'user' => $user,
            'access_token' => $accessToken
        ], Response::HTTP_CREATED);
    }

    // function logout. Keluar dari sistem.
    public function logout(){
        // pengecekan apakah user sudah login?
        if(auth()->check() === false){
            return response()->json([
                'status' => false,
                'message' => 'Anda belum melakukan login.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // menghapus token yang dimiliki oleh user yang sedang login tersebut
        $delete = auth()->user()->token()->revoke();

        // ketika penghapusan token gagal
        if($delete === false){
            return response()->json([
                'status' => false,
                'message' => 'Anda gagal logout dari aplikasi'
            ]);
        }

        // ketika penghapusan token berhasil.
        return response()->json([
            'status' => true,
            'message' => 'Anda berhasil logout dari aplikasi'
        ]);
    }
}
