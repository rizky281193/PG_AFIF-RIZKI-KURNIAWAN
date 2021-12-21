<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function home(Request $request)
    {
        $rsRujukan = Http::get('https://data.jakarta.go.id/read-resource/get-json/daftar-rumah-sakit-rujukan-penanggulangan-covid-19/65d650ae-31c8-4353-a72b-3312fd0cc187');

        $daftarRS = Http::get('https://data.jakarta.go.id/read-resource/get-json/rsdkijakarta-2017-10/8e179e38-c1a4-4273-872e-361d90b68434');

        $rsRujukan = $rsRujukan->json();
        $rsDki = $daftarRS->json();

        $data = collect($rsRujukan)->map(function ($item, $key) use($rsDki) {
            $detail = [];

            foreach ($rsDki as $data){ 
                if($item['kelurahan'] == strtoupper($data['kelurahan'])) {
                    $detail = $data;
                }
            }

            if(count($detail) > 0) {
                $item['kode_pos'] = $detail['kode_pos'];
                $item['nomor_telepon'] = $detail['nomor_telepon'];
                $item['nomor_fax'] = $detail['nomor_fax'];
                $item['no_hp_direktur/kepala_rs'] = $detail['no_hp_direktur/kepala_rs'];
                $item['website'] = $detail['website'];
                $item['email'] = $detail['email'];
            }

            return $item;
        });

        return response()->json($data);
    }

    public function filter(Request $request)
    {
        $tipeParam = '';
        $filterParam = '';
        if($request->kelurahan) {
            $filterParam = $request->kelurahan;
            $tipeParam = 'kelurahan';
        } elseif ($request->kecamatan) {
            $filterParam = $request->kecamatan;
            $tipeParam = 'kecamatan';
        }
        elseif ($request->kota_madya) {
            $filterParam = $request->kota_madya;
            $tipeParam = 'kota_madya';
        }

        $rsRujukan = Http::get('https://data.jakarta.go.id/read-resource/get-json/daftar-rumah-sakit-rujukan-penanggulangan-covid-19/65d650ae-31c8-4353-a72b-3312fd0cc187');

        $daftarRS = Http::get('https://data.jakarta.go.id/read-resource/get-json/rsdkijakarta-2017-10/8e179e38-c1a4-4273-872e-361d90b68434');

        $rsRujukan = $rsRujukan->json();
        $rsDki = $daftarRS->json();

        if($tipeParam != '') {
            $rsRujukan = collect($rsRujukan)->filter(function ($item, $key) use($filterParam, $tipeParam) {
                return $item[$tipeParam] == $filterParam;
            })->values();
        }
        
        $data = collect($rsRujukan)->map(function ($item, $key) use($rsDki) {
            $detail = [];

            foreach ($rsDki as $data){ 
                if($item['kelurahan'] == strtoupper($data['kelurahan'])) {
                    $detail = $data;
                }
            }

            if(count($detail) > 0) {
                $item['kode_pos'] = $detail['kode_pos'];
                $item['nomor_telepon'] = $detail['nomor_telepon'];
                $item['nomor_fax'] = $detail['nomor_fax'];
                $item['no_hp_direktur/kepala_rs'] = $detail['no_hp_direktur/kepala_rs'];
                $item['website'] = $detail['website'];
                $item['email'] = $detail['email'];
            }

            return $item;
        });

        return response()->json($data);
    }
}
