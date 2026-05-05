<?php

if (!function_exists('gambarMobil')) {

    function gambarMobil($model)
    {
        if(!$model){
            return 'img/mobil/allnewveloz.jpeg';
        }

        $nama = strtolower($model);

        // ubah titik menjadi -
        $nama = str_replace('.', '-', $nama);

        // ubah spasi menjadi -
        $nama = str_replace(' ', '-', $nama);

        // hapus karakter aneh
        $nama = preg_replace('/[^a-z0-9\-]/', '', $nama);

        $path = 'img/mobil/'.$nama.'.jpeg';

        // cek apakah file ada
        if(file_exists(public_path($path))){
            return $path;
        }

        // fallback berdasarkan merk mobil
        if(str_contains($nama,'xenia')){
            return 'img/mobil/xenia fc.jpeg';
        }

        if(str_contains($nama,'rush')){
            return 'img/mobil/rush gr.jpeg';
        }

        if(str_contains($nama,'veloz')){
            return 'img/mobil/allnewveloz.jpeg';
        }

        // fallback terakhir - menggunakan image yang pasti ada
        return 'img/mobil/allnewveloz.jpeg';
    }

}