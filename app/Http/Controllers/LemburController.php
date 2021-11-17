<?php

namespace App\Http\Controllers;

use App\Models\Lembur;
use Illuminate\Http\Request;
use App\Transformers\LemburTransformer;
use Laratrust\LaratrustFacade as Laratrust;
use Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class LemburController extends Controller
{
    /**
     * Contructor
     */
    public function __construct(Lembur $lembur,LemburTransformer $lemburTransformer){
        $this->lembur = $lembur;
        $this->lemburTransformer = $lemburTransformer;
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

        if (Laratrust::hasRole('Kepala departemen')) {
            $lembur = $this->lemburTransformer->transformCollection($this->lembur
                ->where('departemen_id',auth()->user()->departemen_id)
                ->get());
        }else if (Laratrust::hasRole('Kepala pabrik')) {
            $lembur = $this->lemburTransformer->transformCollection($this->lembur->all());
        }else if (Laratrust::hasRole('Pengawas')){
            $lembur = $this->lemburTransformer->transformCollection($this->lembur
                ->where('departemen_id',auth()->user()->departemen_id)
                ->get());
        }
        return response()->json($lembur->filter()->values());
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
            'keterangan'   => 'required',
        ]);
        
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        setlocale(LC_TIME, 'id_ID.UTF-8');
        $today = Carbon::parse(Carbon::now())->formatLocalized('%Y-%m-%d');

        $this->lembur->tanggal = $today;
        $this->lembur->keterangan = $request->keterangan;
        $this->lembur->departemen_id = auth()->user()->departemen_id;
        $this->lembur->user_id = auth()->user()->id;

        try{            
            $this->lembur->save();
        }
        catch(\Exception $e){
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        return response()->json(['success' => true, 'lembur_id' => $this->lembur->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lembur  $lembur
     * @return \Illuminate\Http\Response
     */
    public function show(Lembur $lembur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lembur  $lembur
     * @return \Illuminate\Http\Response
     */
    public function edit(Lembur $lembur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lembur  $lembur
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lembur $lembur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lembur  $lembur
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lembur $lembur)
    {
        //
    }
}
