<?php

if (!function_exists('gambarMobil')) {

    function gambarMobil($model)
    {
        if(!$model){
            return 'img/mobil/agya-gr-sport.jpeg';
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
            return 'img/mobil/xenia-1-3-r.jpeg';
        }

        if(str_contains($nama,'agya')){
            return 'img/mobil/agya-gr-sport.jpeg';
        }

        if(str_contains($nama,'fortuner')){
            return 'img/mobil/fortuner-vrz.jpeg';
        }

        if(str_contains($nama,'terios')){
            return 'img/mobil/terios-ids.jpeg';
        }

        if(str_contains($nama,'alphard')){
            return 'img/mobil/alphard.jpeg';
        }

        // fallback terakhir
        return 'img/mobil/agya-gr-sport.jpeg';
    }

}