<?php

namespace App\Transformers;


class DetaillemburTransformer extends Transformer {

    public function transform($data)
    {        
        return [
            'id' => $data['id'],
            'nik' => $data['nik'],
            'nama' => $data['nama'],
            'waktu' => $data['waktu'],
            'lama_lembur' => $data['lama_lembur'],
            'lembur_id' => $data['lembur_id'],
            'created_at' => $data->getReadableCreatedAt(),
            'updated_at' => $data->getReadableUpdatedAt(), 
        ];
    }
}
