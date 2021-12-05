<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\JenisSurat;
use App\Exports\SuratMasukExporter;
use App\Imports\SuratMasukImporter;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Pencatatan;
use App\Models\TujuanPencatatan;
use App\Models\Pengingat;
use App\Models\Log;

class SuratMasukController extends Controller
{
    //
    public function index()
    {
        try {
            //code...
            $last = SuratMasuk::all()->last()->NOMOR_AGENDA;
            $respon = [
            'Msg' => 'success',
            'content' => $last,
            ];
            return response()->json($respon,200);
        } catch (\Throwable $th) {
            //throw $th;
            $respon = [
            'Msg' => 'success',
            'content' => 0,
            ];
            return response()->json($respon,200);
        }

    }
    public function setSuratMasuk(Request $request)
    {
        $data = [
            'ID_PENCATATAN' =>$request->id_pencatatan,
            'ID_PENGGUNA' =>$request->id_pengguna,
            'ID_KODE_UNIT_KERJA'=>$request->id_kode_unit,
            'ID_SIFAT_NASKAH'=>$request->id_sifat_naskah,
            'NOMOR_SURAT'=>$request->nomor_surat,
            'NAMA_PENGIRIM'=>$request->nama_pengirim,
            'TGL_DITERIMA'=>$request->tgl_diterima,
            'NOMOR_AGENDA'=>$request->no_agenda,
        ];
        $suratMasuk = SuratMasuk::create($data);
        $date = now()->toDateTimeString();
        $data = [
            'WAKTU' => $date,
            'DESKRIPSI' => "Surat Masuk dengan nomor: ". $request->nomor_surat. " dikirim oleh " . $request->nama_pengirim. " telah di catat"
        ];
        $log = Log::create($data);
        if(!$suratMasuk){
            $respon = [
                'Msg' => 'error',
                'content' => $suratMasuk,
                ];
                return response()->json($respon);
        }
        $respon = [
            'Msg' => 'success',
            'content' => $suratMasuk,
            ];
            return response()->json($respon,200);
    }
    public function getSuratMasuk($id)
    {
        $suratMasuk = SuratMasuk::where('ID_PENCATATAN', $id)->first();
        if($suratMasuk == null){
            $respon =[
            'Msg' => 'error',
            'content' =>  $id,
            ];
        return response()->json($respon);
        }
        $respon =[
            'Msg' => 'success',
            'content' =>  $suratMasuk,
            ];
        return response()->json($respon);
    }
    public function delSuratMasuk($id)
    {
        //revisi
       try{
        $suratMasuk = SuratMasuk::where('ID_PENCATATAN', $id);
        $suratMasuk->delete();
          try{
            $tujuanSurat = TujuanPencatatan::where('ID_PENCATATAN', $id);
            $tujuanSurat->delete();
            try{
                $pengingat = Pengingat::where('ID_PENCATATAN', $id);
                if($pengingat!=null){
                    $pengingat->delete();
                }
                    try{
                        $pencatatan= Pencatatan::where('ID_PENCATATAN', $id);
                        $pencatatan->delete();
                        $respon = [
                            'Msg' => 'success',
                            'content' => $id,
                            ];
                            return response()->json($respon,200);
                    }
                    catch(\Exception $ex){
                        $respon = [
                            'Msg' => 'error hapus pencatatan',
                            'content' => $id,
                            ];
                            return response()->json($respon);
                    }
            }catch(\Exception $ex){
                try{
                    $pencatatan= Pencatatan::where('ID_PENCATATAN', $id);
                    $pencatatan->delete();
                    $respon = [
                        'Msg' => 'success',
                        'content' => $id,
                        ];
                        return response()->json($respon,200);
                }
                catch(\Exception $ex){
                    $respon = [
                        'Msg' => 'error hapus pencatatan',
                        'content' => $id,
                        ];
                        return response()->json($respon);
                }
                $respon = [
                    'Msg' => 'error hapus pengingat',
                    'content' => $id,
                    ];
                    return response()->json($respon);
            }
            }catch(\Exception $ex){
            $respon = [
                'Msg' => 'error hapus tujuan ',
                'content' => $id,
                ];
                return response()->json($respon);
        }
        } catch(\Exception $ex){
        $respon = [
            'Msg' => 'error hapus surat masuk',
            'content' => $id,
            ];
            return response()->json($respon);
       }

    }
    public function updateSuratMasuk(Request $request)
    {
        $suratMasuk = SuratMasuk::where('ID_PENCATATAN', $request->id_pencatatan)
        ->update([
            'ID_PENCATATAN' =>$request->id_pencatatan,
            'ID_PENGGUNA' =>$request->id_pengguna,
            'ID_KODE_UNIT_KERJA'=>$request->id_kode_unit,
            'ID_SIFAT_NASKAH'=>$request->id_sifat_naskah,
            'NOMOR_SURAT'=>$request->nomor_surat,
            'NAMA_PENGIRIM'=>$request->nama_pengirim,
            'TGL_DITERIMA'=>$request->tgl_diterima,
            'NOMOR_AGENDA'=>$request->no_agenda,
            ]);
        if(!$suratMasuk){
            $respon = [
                'Msg' => 'error',
                'content' => $request->id_pencatatan,
                ];
                return response()->json($respon);
        }
        $respon = [
            'Msg' => 'success',
            'content' => $suratMasuk,
            ];
            return response()->json($respon,200);

    }
    public function getAllSuratMasuk()
    {
        $suratMasuk = SuratMasuk::orderBy('ID_PENCATATAN','desc')->get();
        if(!$suratMasuk){
            $respon = [
                'Msg' => 'error',
                'content' => $suratMasuk,
                ];
                return response()->json($respon);
        }
        $respon = [
            'Msg' => 'success',
            'content' => $suratMasuk,
            ];
            return response()->json($respon,200);
    }
    public function delAllSuratMasuk()
    {
        $suratMasuk = SuratMasuk::truncate();
        if(!$suratMasuk){
            $respon = [
                'Msg' => 'error',
                'content' => $suratMasuk,
                ];
                return response()->json($respon);
        }
        $respon = [
            'Msg' => 'success',
            'content' => $suratMasuk,
            ];
            return response()->json($respon,200);
    }
    public function getSuratDetail(){
        $suratMasuk = DB::table('surat_masuk')
        ->join('pencatatan','pencatatan.ID_PENCATATAN','=','surat_masuk.ID_PENCATATAN')
        ->join('jenis_surat','jenis_surat.ID_JENIS_SURAT','=','pencatatan.ID_JENIS_SURAT')
        ->join('derajat_surat','derajat_surat.ID_DERAJAT_SURAT','=','pencatatan.ID_DERAJAT_SURAT')
        ->join('kode_unit_kerja','surat_masuk.ID_KODE_UNIT_KERJA','=','kode_unit_kerja.ID_KODE_UNIT_KERJA')
        ->join('kode_sifat_naskah','kode_sifat_naskah.ID_SIFAT_NASKAH', '=','surat_masuk.ID_SIFAT_NASKAH')
        ->join('pengguna','pengguna.ID_PENGGUNA','=','surat_masuk.ID_PENGGUNA')
        // ->join('disposisi','disposisi.ID_PENCATATAN','=','surat_masuk.ID_PENCATATAN')
        ->select('pencatatan.PERIHAL','pencatatan.KODE_ARSIP_KOM','pencatatan.KODE_ARSIP_HLM','pencatatan.KODE_ARSIP_MANUAL','pencatatan.NAMA_FILE_SURAT','pencatatan.NAMA_FILE_LAMPIRAN','pencatatan.TGL_SURAT','pencatatan.PENANDATANGAN','jenis_surat.*','derajat_surat.*','surat_masuk.*','kode_unit_kerja.KODE_UNIT_KERJA','kode_unit_kerja.NAMA_UNIT_KERJA','kode_sifat_naskah.KODE_SIFAT_NASKAH','kode_sifat_naskah.SIFAT_NASKAH','pengguna.NAMA')
        ->orderBy('ID_PENCATATAN','desc')
        ->get();
        if(!$suratMasuk){
            $respon = [
                'Msg' => 'error',
                'content' => $suratMasuk,
                ];
                return response()->json($respon);
        }
        $respon = [
            'Msg' => 'success',
            'content' => $suratMasuk,
            ];
        return response()->json($respon);
    }
    public function searchSuratMasuk(Request $request){
        $key = $request->key;
        try{
            $result= DB::table('surat_masuk')
            ->join('pencatatan','pencatatan.ID_PENCATATAN','=','surat_masuk.ID_PENCATATAN')
            ->join('jenis_surat','jenis_surat.ID_JENIS_SURAT','=','pencatatan.ID_JENIS_SURAT')
            ->join('derajat_surat','derajat_surat.ID_DERAJAT_SURAT','=','pencatatan.ID_DERAJAT_SURAT')
            ->join('kode_unit_kerja','surat_masuk.ID_KODE_UNIT_KERJA','=','kode_unit_kerja.ID_KODE_UNIT_KERJA')
            ->join('kode_sifat_naskah','kode_sifat_naskah.ID_SIFAT_NASKAH', '=','surat_masuk.ID_SIFAT_NASKAH')
            ->join('pengguna','pengguna.ID_PENGGUNA','=','surat_masuk.ID_PENGGUNA')
            ->where('PERIHAL', 'like','%'.$key.'%')
            ->orWhere('NAMA_UNIT_KERJA','like','%'.$key.'%')
            ->orWhere('KODE_ARSIP_KOM','like','%'.$key.'%')
            ->orWhere('KODE_ARSIP_MANUAL','like','%'.$key.'%')
            ->orWhere('KODE_ARSIP_HLM','like','%'.$key.'%')
            ->orWhere('TGL_SURAT','like','%'.$key.'%')
            ->orWhere('PENANDATANGAN','like','%'.$key.'%')
            ->orWhere('JENIS_SURAT','like','%'.$key.'%')
            ->orWhere('DERAJAT_SURAT','like','%'.$key.'%')
            ->orWhere('NOMOR_SURAT','like','%'.$key.'%')
            ->orWhere('NAMA_PENGIRIM','like','%'.$key.'%')
            ->orWhere('TGL_DITERIMA','like','%'.$key.'%')
            ->orWhere('NOMOR_AGENDA','like','%'.$key.'%')
            ->orWhere('KODE_UNIT_KERJA','like','%'.$key.'%')
            ->orWhere('KODE_SIFAT_NASKAH','like','%'.$key.'%')
            ->orWhere('SIFAT_NASKAH','like','%'.$key.'%')
            ->orWhere('NAMA','%','like','%'.$key.'%')
            ->select('pencatatan.PERIHAL','pencatatan.KODE_ARSIP_KOM','pencatatan.KODE_ARSIP_HLM','pencatatan.KODE_ARSIP_MANUAL','pencatatan.NAMA_FILE_SURAT','pencatatan.NAMA_FILE_LAMPIRAN','pencatatan.TGL_SURAT','pencatatan.PENANDATANGAN','jenis_surat.*','derajat_surat.*','surat_masuk.*','kode_unit_kerja.KODE_UNIT_KERJA','kode_unit_kerja.NAMA_UNIT_KERJA','kode_sifat_naskah.KODE_SIFAT_NASKAH','kode_sifat_naskah.SIFAT_NASKAH','pengguna.NAMA')
            ->orderBy('ID_PENCATATAN','desc')
            ->get();
            $respon = [
                'Msg' => 'success',
                'key' => $key,
                'content' => $result,
                ];

            if(count($result)==0||count($result)==null){
                $str = explode(" ", $key);
                $found = false;
                $i = 0;
                while($found!= true){
                try{
                    $result= DB::table('surat_masuk')
                    ->join('pencatatan','pencatatan.ID_PENCATATAN','=','surat_masuk.ID_PENCATATAN')
                    ->join('jenis_surat','jenis_surat.ID_JENIS_SURAT','=','pencatatan.ID_JENIS_SURAT')
                    ->join('derajat_surat','derajat_surat.ID_DERAJAT_SURAT','=','pencatatan.ID_DERAJAT_SURAT')
                    ->join('kode_unit_kerja','surat_masuk.ID_KODE_UNIT_KERJA','=','kode_unit_kerja.ID_KODE_UNIT_KERJA')
                    ->join('kode_sifat_naskah','kode_sifat_naskah.ID_SIFAT_NASKAH', '=','surat_masuk.ID_SIFAT_NASKAH')
                    ->join('pengguna','pengguna.ID_PENGGUNA','=','surat_masuk.ID_PENGGUNA')
                    ->where('PERIHAL', 'like','%'.$str[$i].'%')
                    ->orWhere('NAMA_UNIT_KERJA','like','%'.$str[$i].'%')
                    ->orWhere('KODE_ARSIP_KOM','like','%'.$str[$i].'%')
                    ->orWhere('KODE_ARSIP_MANUAL','like','%'.$str[$i].'%')
                    ->orWhere('KODE_ARSIP_HLM','like','%'.$str[$i].'%')
                    ->orWhere('TGL_SURAT','like','%'.$str[$i].'%')
                    ->orWhere('PENANDATANGAN','like','%'.$str[$i].'%')
                    ->orWhere('JENIS_SURAT','like','%'.$str[$i].'%')
                    //->orWhere('KETERANGAN','like','%'.$str[$i].'%')
                    ->orWhere('DERAJAT_SURAT','like','%'.$str[$i].'%')
                    //->orWhere('DESKRIPSI','like','%'.$str[$i].'%')
                    ->orWhere('NOMOR_SURAT','like','%'.$str[$i].'%')
                    ->orWhere('NAMA_PENGIRIM','like','%'.$str[$i].'%')
                    ->orWhere('TGL_DITERIMA','like','%'.$str[$i].'%')
                    ->orWhere('NOMOR_AGENDA','like','%'.$str[$i].'%')
                    ->orWhere('KODE_UNIT_KERJA','like','%'.$str[$i].'%')
                    ->orWhere('KODE_SIFAT_NASKAH','like','%'.$str[$i].'%')
                    ->orWhere('SIFAT_NASKAH','like','%'.$str[$i].'%')
                    ->orWhere('NAMA','%','like','%'.$str[$i].'%')
                    ->select('pencatatan.PERIHAL','pencatatan.KODE_ARSIP_KOM','pencatatan.KODE_ARSIP_HLM','pencatatan.KODE_ARSIP_MANUAL','pencatatan.NAMA_FILE_SURAT',
                    'pencatatan.NAMA_FILE_LAMPIRAN','pencatatan.TGL_SURAT','pencatatan.PENANDATANGAN','jenis_surat.*','derajat_surat.*','surat_masuk.*','kode_unit_kerja.KODE_UNIT_KERJA',
                    'kode_unit_kerja.NAMA_UNIT_KERJA','kode_sifat_naskah.KODE_SIFAT_NASKAH','kode_sifat_naskah.SIFAT_NASKAH','pengguna.NAMA')
                    ->orderBy('ID_PENCATATAN','desc')
                    ->get();
                    if(count($result)!=0||count($result)!=null){
                        $respon = [
                            'Msg' => 'success',
                            'key' => $str[$i],
                            'content' => $result,
                            ];
                            $found= true;
                    }else{
                        if($i > count($str)){
                            $respon = [
                                'Msg' => 'success',
                                'key' => $str[$i],
                                'content' => $result,
                                ];
                                $found = true;
                        }
                        $i++;
                    }
                } catch(\Exception $ex){
                    $respon = [
                        'Msg' => 'error',
                        'content' => [],
                        ];
                        $found = true;
                }
            }
            }

        } catch(\Exception $ex){
            $respon = [
                'Msg' => 'error',
                'content' => [],
                ];
                return response()->json($respon);
        }

            return response()->json($respon);
    }
    public function exportDataSuratMasuk(){
        ob_end_clean();
        ob_start();
        // return Excel::download(new SuratMasukExporter, 'Pencatatan Surat Masuk.xlsx');
        return (new SuratMasukExporter)->download('Pencatatan Surat Masuk per ' .date("d-m-Y").'.xlsx');
    }
    public function getCountSM(){
        try{
            $result = SuratMasuk::all();
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
    public function importDataSuratMasuk(Request $request){
        // $surat = $request->file;
        // $nama_file = $surat->getClientOriginalName();
        // $localfolder = public_path('import-temp') .'/';
        // if ($surat->move($localfolder, $nama_file)) {
            // $import = Excel::import(new SuratMasukImporter, $request->file);
            // $import = Excel::import(new SuratMasukImporter, public_path('import-temp/'.$nama_file));
                // if(Excel::import(new SuratMasukImporter, $request->file)){
                //     return response()->json([
                //         'status' => 'success',
                //         'message' => 'Data berhasil diimport',
                //     ], 200);
                // }
                // else{
                //     return response()->json([
                //         'status' => 'error',
                //         'message' => 'Data gagal diimport',
                //     ], 500);
                // }
        // }
        Excel::import(new SuratMasukImporter, $request->file);
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diimport',
        ], 200);
    }
}
