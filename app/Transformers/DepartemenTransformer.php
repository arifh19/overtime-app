<?php

namespace App\Transformers;


class DepartemenTransformer extends Transformer {

    public function transform($data)
    {        
        return [
            'id' => $data['id'],
            'name' => $data['name'],
            'biaya_lembur' => $data['biaya_lembur'],
            'created_at' => $data->getReadableCreatedAt(),
            'updated_at' => $data->getReadableUpdatedAt(), 
        ];
    }
}
