<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\NomorSuratKeluar;
class NomorSuratKeluarController extends Controller
{
    public function index(){
        $year = date("Y");

            $result = NomorSuratKeluar::where('TAHUN',  $year)->orderBy('NOMOR_URUT','desc')->first();
          if($result==null){
            $result = [
                'Msg' => 'kosong',
                'content'=> null
                ];
          }
          $result = [
            'Msg' => 'kosong',
            'content'=> $result
            ];
        return response()->json($result);

    }
    public function setNomorSurat(Request $request){
        $year = date("Y");
        $tahun = 0;
        $no_urut = 0;
        if($request->tahun!=null && $request->no_urut!=null) {
            $no_urut = $request->no_urut;
            $tahun = $request->tahun;
        }else{
            try{
                $last = NomorSuratKeluar::where('TAHUN',  $year)->orderBy('NOMOR_URUT','desc')->first();
                $no_urut = $last->NOMOR_URUT + 1;
                $tahun = $year;
            } catch(\Exception $ex){
                    $no_urut = 1;
                    $tahun = $year;
            }
        }
        $data = [
            'ID_KODE_UNIT_KERJA'=>1,
            'ID_KODE_HAL'=>$request->id_kode_hal,
            'ID_KODE_PERGURUAN_TINGGI'=>1,
            'ID_SIFAT_NASKAH'=>$request->id_sifat_naskah,
            'NOMOR_URUT' =>$no_urut,
            'TAHUN'=>$tahun,

        ];
        try{
            $nomorSurat = NomorSuratKeluar::create($data);
            $respon = [
                'Msg' => 'success',
                'content' => $nomorSurat,
                ];
                return response()->json($respon,200);
        } catch(\Exception $ex){
            $respon = [
                'Msg' => 'error',
                'content' => $nomorSurat,
                ];
                return response()->json($respon);
        }


    }
    public function getNomorSurat($id)
    {
          try{
            $nomorSurat = NomorSuratKeluar::where('ID_NOMOR_SURAT', $id)->first();
            $respon =[
                'Msg' => 'success',
                'content' =>   $nomorSurat,
                ];
                 return response()->json($respon);

        } catch(\Exception $ex){
            $respon =[
            'Msg' => 'error',
            'content' =>  $id,
            ];
        return response()->json($respon);
        }
    }
    public function delNomorSurat($id){
        try{
            $nomorSurat = NomorSuratKeluar::where('ID_NOMOR_SURAT', $id);
            $nomorSurat->delete();
            $respon = [
                'Msg' => 'success',
                'content' => $id,
                ];
                return response()->json($respon,200);
        } catch(\Exception $ex){
            $respon = [
                'Msg' => 'error',
                'content' => $id,
                ];
                return response()->json($respon);
        }
    }
    public function updateNomorSurat(Request $request){
        try{
            $nomorSurat = NomorSuratKeluar::where('ID_NOMOR_SURAT', $request->id_nomor_surat)
            ->update([
                'ID_KODE_UNIT_KERJA'=>$request->id_kode_unit,
                'ID_KODE_HAL'=>$request->id_kode_hal,
                'ID_KODE_PERGURUAN_TINGGI'=>$request->id_kode_pt,
                'ID_SIFAT_NASKAH'=>$request->id_sifat_naskah,
                'NOMOR_URUT' =>$request->no_urut,
                'TAHUN'=>$request->tahun,
            ]);
            if($nomorSurat!=0 || $nomorSurat!= null){
                $respon = [
                    'Msg' => 'success',
                    'content' => $nomorSurat,
                ];
                }else{
                $respon = [
                    'Msg' => 'error',
                    'content' => null,
                ];
            }
            return response()->json($respon);

        } catch(\Exception $ex){
            $respon = [
                'Msg' => 'error',
                'content' => null,
                ];
                return response()->json($respon);
        }
    }
    public function getDetailNomorSurat(){
        try{
            $nomorSurat = DB::table('nomor_surat')
            ->join('kode_unit_kerja','kode_unit_kerja.ID_KODE_UNIT_KERJA','=','nomor_surat.ID_KODE_UNIT_KERJA')
            ->join('kode_hal','kode_hal.ID_KODE_HAL','=','nomor_surat.ID_KODE_HAL')
            ->join('kode_perguruan_tinggi','kode_perguruan_tinggi.ID_KODE_PERGURUAN_TINGGI','=','nomor_surat.ID_KODE_PERGURUAN_TINGGI')
            ->join('kode_sifat_naskah','kode_sifat_naskah.ID_SIFAT_NASKAH','=','nomor_surat.ID_SIFAT_NASKAH')
            ->select('nomor_surat.*','kode_unit_kerja.*')
            ->orderBy('ID_NOMOR_SURAT','desc')
            ->get();
            $respon = [
                'Msg' => 'success',
                'content' => $nomorSurat,
                ];
            return response()->json($respon);
        } catch(\Exception $ex){
            $respon = [
                'Msg' => 'error',
                'content' => $nomorSurat,
                ];
                return response()->json($respon);
        }
    }
    public function getLastNomorUrut(){
        $year = date("Y");

        try{
            $last = NomorSuratKeluar::where('TAHUN',  $year)->orderBy('NOMOR_URUT','desc')->first();
            if ($last == null)
            {
                $content = [
                    'TAHUN' => $year,
                    'NOMOR_URUT'=>0
                ];
                $respon = [
                    'Msg' => 'success 1',
                    'content' => $content,
                    ];
                    return response()->json($respon);
            }
            $respon = [
                'Msg' => 'success',
                'content' => $last,
                ];
        }
     catch(\Exception $ex){
        $respon = [
            'Msg' => 'error',
            'content' => $last,
            ];
            return response()->json($respon);
    }
    return response()->json($respon);
    }

    public function delAllNomorSuratKeluar(){
        $del = NomorSuratKeluar::truncate();
        if($del){
            $respon=[
                'Msg' => 'succes',
                'content'=> $del
            ];
            return response()->json($respon,200);
        }
        $respon=[
            'Msg'=> 'error',
            'content'=> null
        ];
        return response()->json($respon);
    }
}
