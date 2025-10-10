<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LaporanController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_laporan' => 'required',
            'kategori_laporan' => 'required',
            'satuan' => 'required|integer',
            'jumlah' => 'required|integer',
            'harga' => 'required',
        ]);

       if($validator->fails()) {
            return $this->error('Error!', $validator->errors(), 422);
        }
    }
}
