<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Departemen extends Model
{
    use HasFactory;
	public function detail_lembur(){
		return $this->hasMany('App\Models\Detaillembur','departemen_id');
	  }
    public function getReadableCreatedAt(){
		setlocale(LC_TIME, 'id_ID.UTF-8');
    return Carbon::parse($this->attributes['created_at'])->formatLocalized('%H:%S %A, %d %B %Y');
	}   

	public function getReadableUpdatedAt(){
		setlocale(LC_TIME, 'id_ID.UTF-8');
    return Carbon::parse($this->attributes['updated_at'])->formatLocalized('%H:%S %A, %d %B %Y');
	}  
}
