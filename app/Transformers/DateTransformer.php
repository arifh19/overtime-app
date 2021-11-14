<?php

namespace App\Transformers;
use Carbon\Carbon;

class DateTransformer extends Transformer {

    public function transform($data)
    {   
        setlocale(LC_TIME, 'id_ID.UTF-8');
        return [
            'bulan' => Carbon::parse($data['tanggal'])->formatLocalized('%B %Y'),
            'kode' => Carbon::parse($data['tanggal'])->formatLocalized('%Y-%m'),
        ];
    }
}
