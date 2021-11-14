<?php

namespace App\Transformers;
use Laratrust\LaratrustFacade as Laratrust;
use App\Models\Detaillembur;

class LaporanTransformer {

    public function transformCollection($items,$date)
    {
        if (Laratrust::hasRole('Kepala departemen') || Laratrust::hasRole('Pengawas')) {  
            return $items->map(function($item)use ($date){
                $total_lembur = Detaillembur::where('departemen_id', $item['departemen_id'])
                    ->where('nik', $item['nik'])
                    ->where('status_id', 3)
                    ->where('tanggal', 'like', $date.'%')
                    ->groupBy('nik')->sum('lama_lembur');
                return $this->transformDepartemen($item,$total_lembur);
            })->unique('nik')->values();
        }else if (Laratrust::hasRole('Kepala pabrik')) {
            return $items->map(function($item)use ($date){
                return $this->transformAll($item,$date);
            })->filter()->values();
        }
        else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    public function transformDepartemen($data,$total_lembur)
    {
        return [
            'id' => $data['id'],
            'nik' => $data['nik'],
            'nama' => $data['nama'],
            'departemen' => $data->departemen['name'],
            'total_lembur' => $total_lembur.' Jam',
            'total_biaya_lembur' => (int)$total_lembur * (int)$data->departemen['biaya_lembur'],
            'created_at' => $data->getReadableCreatedAt(),
            'updated_at' => $data->getReadableUpdatedAt(), 
        ];
    }
    public function transformAll($data,$date)
    {
        $karyawans = $data->detail_lembur()
        ->where('status_id', 3)
        ->where('tanggal', 'like', $date.'%')
        ->get();

        $datakaryawan = $karyawans->map(function($karyawan)use ($date){
            $total_lembur = Detaillembur::where('departemen_id', $karyawan['departemen_id'])
                    ->where('nik', $karyawan['nik'])
                    ->where('status_id', 3)
                    ->where('tanggal', 'like', $date.'%')
                    ->groupBy('nik')->sum('lama_lembur');

            return [
                'id' => $karyawan['id'],
                'nik' => $karyawan['nik'],
                'nama' => $karyawan['nama'],
                'total_lembur' => $total_lembur.' Jam',
                'total_biaya_lembur' => (int)$total_lembur * (int)$karyawan->departemen['biaya_lembur'],
                'created_at' => $karyawan->getReadableCreatedAt(),
                'updated_at' => $karyawan->getReadableUpdatedAt(), 
            ];
        })->unique('nik')->values();
        
        if ($datakaryawan->count()==0){
            return NULL;
        }
        return [
            'id' => $data['id'],
            'departemen' => $data['name'],
            'karyawan' => $datakaryawan,
            'created_at' => $data->getReadableCreatedAt(),
            'updated_at' => $data->getReadableUpdatedAt(), 
        ];
    }
}
