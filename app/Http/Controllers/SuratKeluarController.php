<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratKeluar;
use Illuminate\Database\QueryException;
use App\Models\NomorSuratKeluar;
use App\Models\TujuanPencatatan;
use App\Models\Pencatatan;
use App\Exports\SuratKeluarExporter;
use App\Imports\SuratKeluarImporter;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Log;
class SuratKeluarController extends Controller
{
    public function index(){

        try{
            $last = SuratKeluar::all()->last();
            $respon = [
                'Msg'=> 'success',
                'content'=> $last,
            ];
            return response()->json($respon,200);

        } catch(\Exception $ex){
            $respon = [
                'Msg'=> 'error',
                'content'=> null,
            ];
            return response()->json($respon);
        }
    }
    public function getLastNoAgenda(Request $request){
        $year = date("Y");
        try{

            $no = 0;
            $tahun = 0;
            $suratKeluar = SuratKeluar::where('TAHUN_AGENDA',  $year)->orderBy('NOMOR_AGENDA','desc')->first();
            if( $suratKeluar->NOMOR_AGENDA == null)
            {
                $no = 0;
            }else{
                $no = $suratKeluar->NOMOR_AGENDA ;
            }
            if($suratKeluar->TAHUN_AGENDA == null)
            {
                $tahun = $year;
            }else{
                $tahun = $suratKeluar->TAHUN_AGENDA;
            }
            $respon = [
                'Msg'=> 'success',
                'tahun'=> $tahun,
                'no'=>$no
            ];
            return response()->json($respon,200);
        }
        catch(\Exception $x){
            $content = [
                'TAHUN' => $year,
                'NOMOR_URUT'=>0
            ];
            $respon = [
                'Msg'=> 'error',
                'content'=> $content,
            ];
            return response()->json($respon);
        }
    }
    public function setSuratKeluar(Request $request)
    {
        $nomor = str_replace("\/","/",$request->nomor_surat);
        $data = [
            'ID_PENCATATAN' =>$request->id_pencatatan,
            'ID_PENGGUNA' =>$request->id_pengguna,
            'ID_NOMOR_SURAT'=>$request->id_no_surat,
            'ID_PEMOHON'=>$request->id_pemohon,
            'TGL_KIRIM'=>$request->tgl_kirim,
            'NOMOR_SURAT'=>$nomor
        ];
        try{
               $suratKeluar = SuratKeluar::create($data);
                $respon = [
                    'Msg' => 'success',
                    'content' => $suratKeluar,
                ];
                $date = now()->toDateTimeString();
                $data = [
                    'WAKTU' => $date,
                    'DESKRIPSI' => "Surat Keluar dengan nomor: ". $nomor. " telah di catat"
                ];
                 $log = Log::create($data);
                return response()->json($respon);
        }catch(\Exception $ex){
                $respon = [
                    'Msg' => 'error',
                    'content' => $data,
                ];
                return response()->json($respon);
        }
    }
    public function getSuratKeluar($id){
        try{
            $suratKeluar = SuratKeluar::where('ID_PENCATATAN', $id)->first();
            if($suratKeluar!=null){
                $respon =[
                    'Msg' => 'success',
                    'content' =>  $suratKeluar,
                    ];

            }else{
                $respon =[
                    'Msg' => 'error',
                    'content' =>  null,
                    ];
            }
            return response()->json($respon);
        } catch(\Exception $ex){
            $respon =[
                'Msg' => 'error',
                'content' =>  $id,
                ];
            return response()->json($respon);
        }
    }
    public function delSuratKeluar($id , $no){
        try{
            $delTujuanPencatatan = TujuanPencatatan::where('ID_PENCATATAN', $id);
            $delTujuanPencatatan->delete();
        } catch(\Exception $ex){
            $respon = [
                'Msg' => 'error t',
                'content' => $id,
                ];
                return response()->json($respon);
        }
        try{
            $suratKeluar = SuratKeluar::where('ID_PENCATATAN', $id);
            $tempSuratKeluar = $suratKeluar;
            $suratKeluar->delete();
            try{
                $nomorSurat = NomorSuratKeluar::where('ID_NOMOR_SURAT', $no);
                $nomorSurat->delete();
                try{
                    $pencatatan = Pencatatan::where('ID_PENCATATAN', $id);
                    $tempPencatatan = $pencatatan;
                    $result =  $pencatatan->delete();
                    $respon = [
                        'Msg' => 'succes',
                        'content' => $result,
                        ];
                        // $date = now();
                        // $data = [
                        //     'ID_PENGGUNA' =>  $tempPencatatan->ID_PENGGUNA,
                        //     'ID_PENCATATAN' =>  $tempPencatatan->ID_PENCATATAN,
                        //     'WAKTU' => $date,
                        //     'DESKRIPSI' =>   'Surat keluar dengan nomor:'. $tempSuratKeluar->NOMOR_SURAT. ' telah dihapus'
                        // ];
                        // $log = Log::create($data);
                        return response()->json($respon);
                    }
                    catch(\Exception $ex){
                        $respon = [
                            'Msg' => 'error x',
                            'content' => $id,
                            ];
                            return response()->json($respon,200);
                        }
            }catch(\Exception $ex){
                $respon = [
                    'Msg' => 'error',
                    'content' => $id,
                    ];
                    return response()->json($respon);
            }
        } catch(\Exception $ex){
            $respon = [
                'Msg' => 'error',
                'content' => $id,
                ];
            return response()->json($respon);
        }

    }
    public function updateSuratKeluar(Request $request)
    {
        $data = [
            'ID_PENGGUNA' =>$request->id_pengguna,
            'ID_PENCATATAN' =>$request->id_pencatatan,
            'ID_NOMOR_SURAT'=>$request->id_no_surat,
            'ID_PEMOHON'=>$request->id_pemohon,
            'TGL_KIRIM'=>$request->tgl_kirim,
            'NOMOR_SURAT'=>$request->nomor_surat
        ];
        try{
            $suratKeluar = SuratKeluar::where('ID_PENCATATAN', $request->id_pencatatan)
            ->update([
                'ID_PENGGUNA' =>$request->id_pengguna,
                'ID_PENCATATAN' =>$request->id_pencatatan,
                'ID_NOMOR_SURAT'=>$request->id_no_surat,
                'ID_PEMOHON'=>$request->id_pemohon,
                'TGL_KIRIM'=>$request->tgl_kirim,
                'NOMOR_SURAT'=>$request->nomor_surat
            ]);

            if($suratKeluar!=0 || $suratKeluar!= null){
                $respon = [
                    'Msg' => 'success',
                    'content' => $suratKeluar,
                    ];

            }else{
                $respon = [
                    'Msg' => 'error',
                    'content' => $suratKeluar,
                    'data'=> $data
                    ];

            }
                return response()->json($respon,200);
        } catch(\Exception $ex){
            $respon = [
                'Msg' => 'error',
                'data'=> $data
                ];
                return response()->json($respon);
        }

    }
    public function getAllSuratKeluar(){
        try{
            $suratKeluar = SuratKeluar::orderBy('ID_PENCATATAN','desc')->get();
            $respon = [
                'Msg' => 'success',
                'content' => $suratKeluar,
                ];
                return response()->json($respon,200);
        } catch(\Exception $ex){
            $respon = [
                'Msg' => 'error',
                'content' => $suratKeluar,
                ];
                return response()->json($respon);
        }
    }
    public function delAllSuratKeluar(){
        $del = SuratKeluar::truncate();
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
        // try{
        //     $suratKeluar = SuratKeluar::truncate();
        //     $nomorSurat = NomorSuratKeluar::truncate();
        //     $respon = [
        //         'Msg' => 'success',
        //         'content' => $suratKeluar,
        //         'content2' => $nomorSurat
        //         ];
        //         return response()->json($respon,200);
        // } catch(\Exception $ex){
        //     $respon = [
        //         'Msg' => 'error',
        //         'content' => $suratKeluar,
        //         ];
        //         return response()->json($respon);
        // }
    }
    public function getSuratKeluarDetail(){
        try{
            $suratKeluar = DB::table('surat_keluar')
            ->join('pencatatan','pencatatan.ID_PENCATATAN','=','surat_keluar.ID_PENCATATAN')
            ->join('pengguna','pengguna.ID_PENGGUNA','=','surat_keluar.ID_PENGGUNA')
            ->join('pemohon','pemohon.ID_PEMOHON','=','surat_keluar.ID_PEMOHON')
            ->join('nomor_surat','nomor_surat.ID_NOMOR_SURAT','=','surat_keluar.ID_NOMOR_SURAT')
            // ->join('kode_unit_kerja','kode_unit_kerja.ID_KODE_UNIT_KERJA','=','nomor_surat.ID_KODE_UNIT_KERJA')
            // ->join('kode_hal','kode_hal.ID_KODE_HAL','=','nomor_surat.ID_KODE_HAL')
            // ->join('kode_perguruan_tinggi','kode_perguruan_tinggi.ID_KODE_PERGURUAN_TINGGI','=','nomor_surat.ID_KODE_PERGURUAN_TINGGI')
            // ->join('kode_sifat_naskah','kode_sifat_naskah.ID_SIFAT_NASKAH','=','nomor_surat.ID_SIFAT_NASKAH')
            ->join('derajat_surat','derajat_surat.ID_DERAJAT_SURAT','=','pencatatan.ID_DERAJAT_SURAT')
            ->join('jenis_surat','jenis_surat.ID_JENIS_SURAT','=','pencatatan.ID_JENIS_SURAT')
            ->select('pencatatan.PERIHAL','pencatatan.KODE_ARSIP_KOM',
            'pencatatan.KODE_ARSIP_HLM','pencatatan.KODE_ARSIP_MANUAL',
            'pencatatan.NAMA_FILE_SURAT','pencatatan.NAMA_FILE_LAMPIRAN',
            'pencatatan.TGL_SURAT','pencatatan.PENANDATANGAN',
            'surat_keluar.*',
            'pengguna.NAMA',
            'pemohon.*',
            'nomor_surat.*',
            // 'kode_unit_kerja.*',
            // 'kode_hal.*',
            // 'kode_perguruan_tinggi.*',
            // 'kode_sifat_naskah.*',
            'derajat_surat.*',
            'jenis_surat.*')
            ->orderBy('ID_PENCATATAN','desc')
            ->get();
            $respon = [
                'Msg' => 'success',
                'content' => $suratKeluar,
            ];
            return response()->json($respon);
            } catch(\Exception $ex){
                $respon = [
                    'Msg' => 'error',
                    'content' => $suratKeluar,
                    ];
                    return response()->json($respon);
            }
    }
    public function searchSuratKeluar(Request $request){
        $key = $request->key;
        try{
            $result = DB::table('surat_keluar')
            ->join('pencatatan','pencatatan.ID_PENCATATAN','=','surat_keluar.ID_PENCATATAN')
            ->join('pengguna','pengguna.ID_PENGGUNA','=','surat_keluar.ID_PENGGUNA')
            ->join('pemohon','pemohon.ID_PEMOHON','=','surat_keluar.ID_PEMOHON')
            ->join('nomor_surat','nomor_surat.ID_NOMOR_SURAT','=','surat_keluar.ID_NOMOR_SURAT')
            ->join('derajat_surat','derajat_surat.ID_DERAJAT_SURAT','=','pencatatan.ID_DERAJAT_SURAT')
            ->join('jenis_surat','jenis_surat.ID_JENIS_SURAT','=','pencatatan.ID_JENIS_SURAT')
            ->where('PERIHAL', 'like','%'.$key.'%')
            ->orWhere('KODE_ARSIP_KOM','like','%'.$key.'%')
            ->orWhere('KODE_ARSIP_HLM','like','%'.$key.'%')
            ->orWhere('KODE_ARSIP_MANUAL','like','%'.$key.'%')
            ->orWhere('TGL_SURAT','like','%'.$key.'%')
            ->orWhere('PENANDATANGAN','like','%'.$key.'%')
            ->orWhere('TGL_KIRIM','like','%'.$key.'%')
            ->orWhere('NOMOR_SURAT','like','%'.$key.'%')
            ->orWhere('NAMA','like','%'.$key.'%')
            ->orWhere('NAMA_PEMOHON','like','%'.$key.'%')
            ->orWhere('NOMOR_URUT','like','%'.$key.'%')
            ->orWhere('TAHUN','like','%'.$key.'%')
            ->orWhere('DERAJAT_SURAT','like','%'.$key.'%')
            ->orWhere('JENIS_SURAT','like','%'.$key.'%')
            ->select('pencatatan.PERIHAL','pencatatan.KODE_ARSIP_KOM',
            'pencatatan.KODE_ARSIP_HLM','pencatatan.KODE_ARSIP_MANUAL',
            'pencatatan.NAMA_FILE_SURAT','pencatatan.NAMA_FILE_LAMPIRAN',
            'pencatatan.TGL_SURAT','pencatatan.PENANDATANGAN',
            'surat_keluar.*',
            'pengguna.NAMA',
            'pemohon.*',
            'nomor_surat.*',
            'derajat_surat.*',
            'jenis_surat.*')
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
                    $result = DB::table('surat_keluar')
                    ->join('pencatatan','pencatatan.ID_PENCATATAN','=','surat_keluar.ID_PENCATATAN')
                    ->join('pengguna','pengguna.ID_PENGGUNA','=','surat_keluar.ID_PENGGUNA')
                    ->join('pemohon','pemohon.ID_PEMOHON','=','surat_keluar.ID_PEMOHON')
                    ->join('nomor_surat','nomor_surat.ID_NOMOR_SURAT','=','surat_keluar.ID_NOMOR_SURAT')
                    ->join('derajat_surat','derajat_surat.ID_DERAJAT_SURAT','=','pencatatan.ID_DERAJAT_SURAT')
                    ->join('jenis_surat','jenis_surat.ID_JENIS_SURAT','=','pencatatan.ID_JENIS_SURAT')
                    ->where('PERIHAL', 'like','%'.$str[$i].'%')
                    ->orWhere('KODE_ARSIP_KOM','like','%'.$str[$i].'%')
                    ->orWhere('KODE_ARSIP_HLM','like','%'.$str[$i].'%')
                    ->orWhere('KODE_ARSIP_MANUAL','like','%'.$str[$i].'%')
                    ->orWhere('TGL_SURAT','like','%'.$str[$i].'%')
                    ->orWhere('PENANDATANGAN','like','%'.$str[$i].'%')
                    ->orWhere('TGL_KIRIM','like','%'.$str[$i].'%')
                    ->orWhere('NOMOR_SURAT','like','%'.$str[$i].'%')
                    ->orWhere('NAMA','like','%'.$str[$i].'%')
                    ->orWhere('NAMA_PEMOHON','like','%'.$str[$i].'%')
                    ->orWhere('NOMOR_URUT','like','%'.$str[$i].'%')
                    ->orWhere('TAHUN','like','%'.$str[$i].'%')
                    ->orWhere('DERAJAT_SURAT','like','%'.$str[$i].'%')
                    ->orWhere('JENIS_SURAT','like','%'.$str[$i].'%')
                    //->orWhere('KETERANGAN','like','%'.$str[$i].'%')
                    ->select('pencatatan.PERIHAL','pencatatan.KODE_ARSIP_KOM',
                    'pencatatan.KODE_ARSIP_HLM','pencatatan.KODE_ARSIP_MANUAL',
                    'pencatatan.NAMA_FILE_SURAT','pencatatan.NAMA_FILE_LAMPIRAN',
                    'pencatatan.TGL_SURAT','pencatatan.PENANDATANGAN',
                    'surat_keluar.*',
                    'pengguna.NAMA',
                    'pemohon.*',
                    'nomor_surat.*',
                    'derajat_surat.*',
                    'jenis_surat.*')
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
                    'content' => $key,
                    ];
                    return response()->json($respon);
            }
            return response()->json($respon);
    }

    public function getCountSK(){
        try{
            $result = SuratKeluar::all();
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

    public function exportDataSuratKeluar(){
        ob_end_clean();
        ob_start();
        return (new SuratKeluarExporter)->download('Pencatatan Surat Keluar per ' .date("d-m-Y").'.xlsx');
    }

    public function importDataSuratKeluar(Request $request){
        Excel::import(new SuratKeluarImporter, $request->file);
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diimport',
        ], 200);
    }

}
