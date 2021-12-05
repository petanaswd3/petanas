<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Disposisi;
use Illuminate\Database\QueryException;
use App\Models\SuratMasuk;
use App\Models\Pencatatan;
use App\Models\TujuanDisposisi;
use App\Models\Log;

class DisposisiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function allInfoDisposisiSuratMasuk(){
        $disposisi = DB::table('disposisi')
        ->join('surat_masuk','disposisi.ID_PENCATATAN','=','surat_masuk.ID_PENCATATAN')
        ->join('pencatatan','disposisi.ID_PENCATATAN','=','pencatatan.ID_PENCATATAN')
        // ->join('tujuan_pencatatan','surat_masuk.ID_PENCACATAN','=','tujuan_pencatatan.ID_PENCATATAN')
        // ->join('kode_unit_kerja','tujuan_pencatatan.ID_KODE_UNIT_KERJA','=','kode_unit_kerja.ID_KODE_UNIT_KERJA')
        ->where('disposisi.JENIS_DISPOSISI',1)
        // ->select('disposisi.*','surat_masuk.*','pencatatan.*','tujuan_pencatatan.*','kode_unit_kerja.*')
        ->select('disposisi.*','surat_masuk.*','pencatatan.*')
        ->orderBy('ID_DISPOSISI','desc')
        ->get();
        if(!$disposisi){
            $respon=[
                'Msg' => 'error',
                'content' => $disposisi,
            ];
            return response()->json($respon);
        }

        $respon = [
            'Msg' => 'success',
            'content' => $disposisi,
            ];
        return response()->json($respon);
    }
    public function allInfoDisposisiSuratKeluar(){
        $disposisi = DB::table('disposisi')
        ->join('surat_keluar','disposisi.ID_PENCATATAN','=','surat_keluar.ID_PENCATATAN')
        ->join('pencatatan','disposisi.ID_PENCATATAN','=','pencatatan.ID_PENCATATAN')
        // ->join('tujuan_pencatatan','surat_keluar.ID_PENCACATAN','=','tujuan_pencatatan.ID_PENCATATAN')
        // ->join('kode_unit_kerja','tujuan_pencatatan.ID_KODE_UNIT_KERJA','=','kode_unit_kerja.ID_KODE_UNIT_KERJA')
        ->where('disposisi.JENIS_DISPOSISI',2)
        // ->select('disposisi.*','surat_keluar.*','pencatatan.*','tujuan_pencatatan.*','kode_unit_kerja.*')
        ->select('disposisi.*','surat_keluar.*','pencatatan.*')
        ->orderBy('ID_DISPOSISI','desc')
        ->get();
        if(!$disposisi){
            $respon=[
                'Msg' => 'error',
                'content' => $disposisi,
            ];
            return response()->json($respon);
        }

        $respon = [
            'Msg' => 'success',
            'content' => $disposisi,
            ];
        return response()->json($respon);


    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createDisposisis(Request $request)
    {
        $data = [
            'ID_PENGGUNA'=>$request->id_pengguna,
            'ID_PENCATATAN'=>$request->id_pencatatan,
            'TANGGAL_DISPOSISI'=>$request->tanggal_disposisi,
            'NOMOR_DISPOSISI'=>$request->nomor_disposisi,
            // tujuan
            'ID_KODE_UNIT_KERJA'=>$request->id_kode_unit,
            'INFORMASI'=>$request->informasi,
            'PROSES_SELANJUTNYA'=>$request->proses_selanjutnya,
            'NOMOR_AGENDA'=>$request->nomor_agenda,
            'JENIS_DISPOSISI'=>$request->jenis_disposisi,
            'NAMA_FILE_DISPOSISI'=>$request->nama_file_disposisi,
        ];
        $disposisi = Disposisi::create($data);
        if($disposisi ==null){
            $respon=[
                'Msg' => 'error',
                'content' => $disposisi,
                ];
                return response()->json($respon);
        }
        $date = now()->toDateTimeString();
        $data = [
            'WAKTU' => $date,
            'DESKRIPSI' => "Disposisi dengan nomor agenda: ". $request->nomor_agenda. " telah di catat"
        ];
        $log = Log::create($data);
        $respon = [
            'Msg' => 'success',
            'content' => $disposisi,
            ];
        return response()->json($respon);
    }

    public function getDisposisi($id){
        $disposisi = DB::table('disposisi')
        ->join('surat_masuk','disposisi.ID_PENCATATAN','=','surat_masuk.ID_PENCATATAN')
        ->where('ID_DISPOSISI',$id)
        ->select('disposisi.*','surat_masuk.*')

        ->get();
        return response()->json($disposisi);
    }

    public function getDisposisiByID($id){
        $disposisi = Disposisi::where('ID_PENCATATAN', $id)->first();     if($disposisi==null){
            $respon = [
                'content' => null
            ];
            return response()->json($respon);
        }
        $respon = [
            'content' => $disposisi
        ];
        return response()->json($respon);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editDisposisi(Request $request)
    {
        $disposisi = Disposisi::where('ID_DISPOSISI', $request->id)
        ->update([
            // 'ID_PENGGUNA'=>$request->id_pengguna,
            'ID_PENCATATAN'=>$request->id_pencatatan,
            // 'ID_DISPOSISI'=>$request->id,
            'TANGGAL_DISPOSISI'=>$request->tanggal_disposisi,
            // 'NOMOR_DISPOSISI'=>$request->nomor_disposisi,
            'PROSES_SELANJUTNYA'=>$request->proses_selanjutnya,
            'INFORMASI'=>$request->informasi,
            // 'PROSES_SELANJUTNYA'=>$request->keteranganDisposisi,
            // 'INFORMASI'=>$request->informasiDisposisi,
            'NOMOR_AGENDA'=>$request->nomor_agenda,
            'NAMA_FILE_DISPOSISI'=>$request->nama_file_disposisi,
        ]);
        if(!$disposisi){
            $respon =[
                'Msg' => 'failed',
                'content' => $request->id_pencatatan,
                ];
            return response()->json($respon);
        }
        $respon =[
            'Msg' => 'success',
            'content' => $disposisi,
            ];
        return response()->json($respon,200);
    }
    public function showData()
    {
        $count = DB::table('surat_masuk')->count();
        $respon =[
            'Msg' => 'success',
            'content' => $count,
            ];
        return response()->json($respon);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDisposisi(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteDisposisi($id)
    {
        try{
            $disposisi = TujuanDisposisi::where('ID_DISPOSISI',$id);
            $disposisi->delete();
        } catch(\Exception $ex){
            $respon = [
                'Msg' => 'error1',
                'content' => $id,
                ];
                return response()->json($respon);
        }
        try{
            $disposisi = Disposisi::where('ID_DISPOSISI',$id);
            $disposisi->delete();
            $respon = [
                'Msg' => 'succes',
                'content' => $disposisi,
                ];
                return response()->json($respon);
            }
            catch(\Exception $ex){
                $respon = [
                    'Msg' => 'error3',
                    'content' => $id,
                    ];
                    return response()->json($respon,200);
            }
        // $disposisi = Disposisi::where('ID_PENCATATAN',$id);
        // $disposisi->delete();
        // if($disposisi = null){
        //     $respon =[
        //     'Msg' => 'error',
        //     'content' => $id,
        //     ];
        // return response()->json($respon);
        // }

        // $respon =[
        //     'Msg' => 'Belum terhapus',
        //     'content' => $id,
        //     ];
        // return response()->json($respon);
    }
    public function getCountDis(){
        try{
            $result = Disposisi::all();
            $count= $result->count();
            $respon = [
                'Msg' => 'success',
                'content' => $count,
            ];
            return response()->json($respon);

        }catch(\Exception $ex){
            $respon = [
                'Msg' => 'error',
                'content' => null,
                ];
                return response()->json($respon);
        }
    }
    // public function searchDisposisi(Request $request){
    //     $key = $request->key;
    //     try{
    //         $resul = DB::table('disposisi')
    //         ->join('surat_masuk','disposisi.ID_PENCATATAN','=','surat_masuk.ID_PENCATATAN')
    //         ->join('surat_keluar','disposisi.ID_PENCATATAN', '=','surat_keluar.ID_PENCATATAN')
    //         ->join('pencatatan','disposisi.ID_PENCATATAN','=','pencatatan.ID_PENCATATAN')
    //         ->join('pengguna','disposisi.ID_PENGGUNA','=','pengguna.ID_PENGGUNA')
    //         ->join('kode_unit_kerja','kode_unit_kerja.ID_KODE_UNIT_KERJA','=','pencatatan.ID_KODE_UNIT_KERJA')

    //         ->where('TANGGAL_DISPOSISI','like','%'.$key.'%')
    //         ->orWhere('PROSES_SELANJUTNYA','like','%'.$key.'%')
    //         ->orWhere('INFORMASI','like','%'.$key.'%')
    //         ->orWhere('NOMOR_AGENDA','like','%'.$key.'%')
    //         ->select('disposisi.*','pencatatan.PERIHAL','pencatatan.KODE_ARSIP_KOM','pencatatan.KODE_ARSIP_HLM','pencatatan.KODE_ARSIP_MANUAL','pencatatan.TGL_SURAT','pencatatan.PENANDATANGAN','jenis_surat.*','derajat_surat.*','surat_masuk.*','kode_unit_kerja.KODE_UNIT_KERJA','kode_unit_kerja.NAMA_UNIT_KERJA','kode_sifat_naskah.KODE_SIFAT_NASKAH','kode_sifat_naskah.SIFAT_NASKAH','pengguna.NAMA')
    //         ->get();
    //     }
    // }
    public function addMsgDisposisi(Request $request)
    {
        try {
            $disposisi = Disposisi::where('ID_DISPOSISI', $request->id)
            ->update([
                'KOMENTAR_DISPOSISI'=>$request->komentar_disposisi,
            ]);
            $respon =[
                'Msg' => 'success',
                'content' => $disposisi,
                'request' => $request->id,
                'msg_request'=> $request->komentar_disposisi
                ];
            $date = now()->toDateTimeString();
            $data = [
                    'WAKTU' => $date,
                    'DESKRIPSI' => "Komentar telah ditambahkan pada nomor agenda: ". $disposisi->NOMOR_AGENDA
            ];
            $log = Log::create($data);
            return response()->json($respon);
        } catch (\Throwable $th) {
            $respon =[
                'Msg' => 'failed',
                'content' => $disposisi,
                ];
            return response()->json($respon);
        }
    }
}
