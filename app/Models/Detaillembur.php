<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Detaillembur extends Model
{
    use HasFactory;

    public function status(){
		  return $this->belongsTo('App\Models\Status','status_id');
    }
    public function departemen(){
      return $this->belongsTo('App\Models\Departemen','departemen_id');
    }
    public function lembur(){
		  return $this->belongsTo('App\Models\Lembur','lembur_id');
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
