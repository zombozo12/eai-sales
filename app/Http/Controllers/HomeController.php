<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        return view('welcome')->with('items', $this->BASE_RESPONSE);
    }

    public function detail($barang_id){
        $data = $this->search($this->BASE_RESPONSE, 'id_barang', $barang_id);

        return view('detail')
            ->with('detail', $data[0]);
    }
}
