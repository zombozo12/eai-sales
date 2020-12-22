<?php

namespace App\Http\Controllers;

use App\Customer;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index(){
        $customer = Customer::where('user_id', auth()->id())->first();

        return view('profile')
            ->with('customer', $customer);
    }

    public function store(Request $request){
        $rules = [
            'nama_lengkap' => 'required|min:5|max:150',
            'birthday' => 'required|date_format:d-m-Y',
            'address' => 'required|min:5|max:200'
        ];

        $message = [
            'nama_lengkap.required' => 'Nama tidak boleh kosong',
            'nama_lengkap.min' => 'Nama tidak boleh kurang dari :min karakter',
            'nama_lengkap.max' => 'Nama tidak boleh lebih dari :max karakter',
            'birthday.required' => 'Tanggal lahir tidak boleh kosong',
            'birthday.date_format' => 'Format tanggal lahir tidak sesuai',
            'address.required' => 'Alamat tidak boleh kosong',
            'address.min' => 'Alamat tidak boleh kurang dari :min karakter',
            'address.max' => 'Alamat tidak boleh lebih dari :max karakter'
        ];

        $this->validate($request, $rules, $message);

        $customer = Customer::where('user_id', auth()->id());
        if($customer->count() === 0){
            Customer::create([
                'user_id' => auth()->id(),
                'name' => $request->nama_lengkap,
                'birthday' => Carbon::parse($request->birthday)->format('Y-m-d'),
                'address' => $request->address
            ]);

            alert('Profile', 'Profile berhasil disimpan');
            return redirect()->back();
        }

        $customer->update([
            'user_id' => auth()->id(),
            'name' => $request->nama_lengkap,
            'birthday' => Carbon::parse($request->birthday)->format('Y-m-d'),
            'address' => $request->address
        ]);

        alert('Profile', 'Profile berhasil disimpan');
        return redirect()->back();
    }

    public function password(Request $request){
        $rules = [
            'password_old' => 'required|min:8|max:50',
            'password' => 'required|confirmed|min:8|max:50'
        ];
        $message = [
            'password_old.required' => 'Password lama tidak boleh kosong',
            'password_old.min' => 'Password lama tidak boleh kurang dari :min karakter',
            'password_old.max' => 'Password lama tidak boleh lebih dari :max karakter',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password tidak boleh kurang dari :min karakter',
            'password.max' => 'Password tidak boleh lebih dari :max karakter',
            'password.confirmed' => 'Password konfirmasi tidak sesuai'
        ];

        $this->validate($request, $rules, $message);

        $password_old = Hash::check($request->password_old, auth()->user()->password);
        if(!$password_old){
            alert('Password', 'Password lama Anda salah', 'error');
            return redirect()->back();
        }

        User::find(auth()->id())
            ->update([
                'password' => Hash::make($request->password)
            ]);

        alert('Password', 'Password berhasil diubah');
        return redirect()->back();
    }
}
