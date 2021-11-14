<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departemen;
use App\Models\Detaillembur;
use App\Models\Lembur;
use App\Transformers\LaporanTransformer;
use App\Transformers\DateTransformer;
use Laratrust\LaratrustFacade as Laratrust;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Contructor
     */
    public function __construct(Departemen $departemen,LaporanTransformer $laporanTransformer,DateTransformer $dateTransformer){
        $this->departemen = $departemen;
        $this->laporanTransformer = $laporanTransformer;
        $this->dateTransformer = $dateTransformer;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {  
        if (!auth()->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $bulan = $this->dateTransformer->transformCollection(Lembur::all())->unique('bulan')->values();
        return response()->json($bulan); 
    }

    public function show($date)
    {   
        if (!auth()->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if (Laratrust::hasRole('Kepala departemen') || Laratrust::hasRole('Pengawas')) {
            $laporans = $this->laporanTransformer->transformCollection(Detaillembur::where('departemen_id', auth()->user()->departemen_id)
                ->where('status_id', 3)
                ->where('tanggal', 'like', $date.'%')
                ->get(), $date);
        }else if (Laratrust::hasRole('Kepala pabrik')) {
            $laporans = $this->laporanTransformer->transformCollection($this->departemen->all(),$date);
        }else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json($laporans);
    }
}
