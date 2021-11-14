<?php

namespace App\Http\Controllers;

use App\Models\Detaillembur;
use App\Models\Lembur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Transformers\DetaillemburTransformer;
use Laratrust\LaratrustFacade as Laratrust;
use Carbon\Carbon;

class DetaillemburController extends Controller
{
    /**
     * Contructor
     */
    public function __construct(Detaillembur $detaillembur,DetaillemburTransformer $detaillemburTransformer){
        $this->detaillembur = $detaillembur;
        $this->detaillemburTransformer = $detaillemburTransformer;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        if (!(Laratrust::hasRole('Pengawas'))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $validator = Validator::make($request->all(), [
            'nik'   => 'required',
            'nama' => 'required',
            'mulai' => 'required',
            'selesai' => 'required',
            'keterangan' => 'required',
            'lama_lembur' => 'required',
            'lembur_id' => 'required',
        ]);
      
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $lembur = Lembur::find($request->lembur_id);

        if($lembur->departemen_id != auth()->user()->departemen_id){
            return response()->json(['success' => 'false', 'message' => 'Silahkan masukkan detail lembur sesuai departemen Anda'], 401);
        }
        
        $this->detaillembur->nik = $request->nik;
        $this->detaillembur->nama = $request->nama;
        $this->detaillembur->mulai = $request->mulai;
        $this->detaillembur->selesai = $request->selesai;
        $this->detaillembur->keterangan = $request->keterangan;
        $this->detaillembur->departemen_id = auth()->user()->departemen_id;
        $this->detaillembur->lama_lembur = $request->lama_lembur;
        $this->detaillembur->lembur_id = $request->lembur_id;
        $this->detaillembur->status_id = 1;

        setlocale(LC_TIME, 'id_ID.UTF-8');
        $today = Carbon::parse(Carbon::now())->formatLocalized('%Y-%m-%d');
        
        $this->detaillembur->tanggal = $today;


        try{            
            $this->detaillembur->save();
        }
        catch(\Exception $e){
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        return response()->json(['success' => true, 'message' => 'Data pekerja yang lembur berhasil ditambahkan']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Detaillembur  $detaillembur
     * @return \Illuminate\Http\Response
     */
    public function show(Detaillembur $detaillembur)
    {

        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Detaillembur  $detaillembur
     * @return \Illuminate\Http\Response
     */
    public function edit(Detaillembur $detaillembur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Detaillembur  $detaillembur
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
        if (!auth()->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if (!(Laratrust::hasRole('Kepala departemen') || Laratrust::hasRole('Kepala pabrik'))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $validator = Validator::make($request->all(), [
            'id'   => 'required',
            'status'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $karyawan = $this->detaillembur->find($request->id);  
        
        if(empty($karyawan)){
            return $this->respondNotFound();
        }

        $lembur = Lembur::find($karyawan->lembur_id);
        if($lembur->departemen_id != auth()->user()->departemen_id && auth()->user()->departemen_id != 1){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (Laratrust::hasRole('Kepala departemen')) {
            if($request->status == 3 || $karyawan->status_id != 1 || $request->status == 1 ){
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $karyawan->status_id = $request->status;
        }else if (Laratrust::hasRole('Kepala pabrik')){
            if($request->status == 2 || $karyawan->status_id != 2 || $request->status == 1 ){
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $karyawan->status_id = $request->status;
        }else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
  
        try{            
            $karyawan->save();
            
        }catch(\Exception $e){
            if($e->getCode() == "23000"){
                return response()->json(['success' => false, 'message' => 'Failed, Other Data Refrence this data']);
            }
            
            return response()->json(['success' => false, 'message' => 'There is problem on server']);
        }

        return response()->json(['success' => true, 'message' => 'Status lembur karyawan telah diperbaharui']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Detaillembur  $detaillembur
     * @return \Illuminate\Http\Response
     */
    public function destroy(Detaillembur $detaillembur)
    {
        //
    }
}
