<?php

namespace App\Transformers;
use Laratrust\LaratrustFacade as Laratrust;
use Carbon\Carbon;

class LemburTransformer extends Transformer {

    public function transform($data)
    {
        if (Laratrust::hasRole('Kepala departemen')) {
            $karyawan = $data->detail_lembur()
                ->where('status_id', 1)
                ->get();
        }else if (Laratrust::hasRole('Kepala pabrik')) {
            $karyawan = $data->detail_lembur()->where('status_id', 2)->get();
        }else if (Laratrust::hasRole('Pengawas')){
            $karyawan = $data->detail_lembur()->get();
        }else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($karyawan->count()==0){
            return NULL;
        }
        setlocale(LC_TIME, 'id_ID.UTF-8');

        return [
            'id' => $data['id'],
            'tanggal' => Carbon::parse($data['tanggal'])->formatLocalized('%d %B %Y'),
            'departemen' => $data->departemen['name'],
            'keterangan' => $data['keterangan'],
            'karyawan_lembur' => $karyawan,
            'created_at' => $data->getReadableCreatedAt(),
            'updated_at' => $data->getReadableUpdatedAt(), 
        ];
    }
}
