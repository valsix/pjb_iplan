<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Entities\Jabatan;
// use App\Entities\Strategi_bisnis;
// use App\Entities\Lokasi;
use GuzzleHttp\Client;

use DB;

class JabatanController extends Controller
{
    public function index()
    {
        // $this->data['strategi_bisnis_id'] = Input::get('strategi_bisnis_id');
        // $this->data['name_distrik'] = Input::get('name_distrik');

        // $this->data['strategi_bisnis'] = Strategi_bisnis::get();

        // $this->data['distrik'] = Distrik::all();
        // $Sb = Strategi_bisnis::all();

        $this->data['jabatan'] = Jabatan::all();
        $Sb = array();

        return view('jabatan/daftar', $this->data, compact('Sb'));
    }

    // public function Ajax($id)
    //  {
    //     $ds = Distrik::where('strategi_bisnis_id', $id)->select("name","id")->get();

    //     return json_encode($ds);
    //  }

    // public function myformAjax2($id)
    //  {
    //     $lokasi = Lokasi::where('distrik_id', $id)->select("name", "id")->get();

    //     return json_encode($lokasi);
    //  }

    // public function lokasi()
    // {
    //     $lokasi = Lokasi::all();
    //     $data = ['lokasi' => $lokasi];
    //     return view('lokasi.daftar_lokasi', $data);
    // }

    public function create(Request $request)
    {
        $id=null;
        if ($request->isMethod('get')) 
        {
            // $item ['strategi_bisnis'] = Strategi_bisnis::all();
            $item['jabatan']= null;
            $item['disabled']= '';
            return view('jabatan/tambah',$item);
        }
        elseif ($request->isMethod('post')) 
        {
            $this->validate($request, [
                    'kode' => 'required|unique:jabatan,kode',
                    'name' => 'required:jabatan,name',
                ]);
            $item = array(
                        'kode' => Input::get('kode'),
                        'name' => Input::get('name'),
                        'keterangan' => Input::get('keterangan')
                    );
            // Distrik::create($item);

            $transaction = Jabatan::create($item);

            if($transaction)
            {
                $request->session()->flash('success', 'Data berhasil ditambahkan');
            }

            return redirect('jabatan/daftar');
        }
    }

    public function update(Request $request, $id)
    {
        if($request->isMethod("get"))
        {
            $reqIdDetil = $this->input->get("reqIdDetil");
            $statement = " AND A.POSITION_ID = '".$reqIdDetil."' ";
            
            $sorder= "";
            $parameter= array('TOP');
            $statement= " A.POSITION_ID = ?";

            $set = new Jabatan();
            $query= $set->SelectByParamsView($reqId)->first();
            $query= $set->selectbyparams($parameter, $statement, $sorder)->first();

            $item['jabatan'] = Jabatan::find($id);
            $item['disabled']= '';
            // $item['strategi_bisnis'] = Strategi_bisnis::all();
            return view('jabatan/tambah',$item);
        }
        elseif ($request->isMethod('post')) 
        {
            $this->validate($request, [
                    'kode' => 'required|unique:jabatan,kode',
                    'name' => 'required:jabatan,name',
                ]);
            $item = Jabatan::find($id);
            $item->kode = Input::get('kode');
            $item->name = Input::get('name');
            $item->keterangan = Input::get('keterangan');
            $item->save();

            $request->session()->flash('success', 'Data berhasil diubah');

            return redirect('jabatan/daftar');
        }
    }

    public function tree(Request $request)
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;//
        $id = isset($_POST['id']) ? $_POST['id'] : 0;//

        $result = $items= array();

        $reqSearch= Input::get('reqSearch');
        // print_r($reqSearch);exit;
        
        $statementunit= "";

        $statement= $statementperiode= "";

        if(empty($reqSearch))
        {
            if ($id == "0")
            {
                $sorder= "";
                $parameter= array('TOP');
                $statement= " A.SUPERIOR_ID = ?";
                
                $jabatan= new Jabatan();
                $datas= $jabatan->selectbyparams($parameter, $statement, $sorder)->get();

                $result["total"] = 0;
                $i=0;
                foreach ($datas as $key => $val) 
                {
                    $valinfoid= trim($val->position_id);
                    $items[$i]['ID'] = $valinfoid;
                    $items[$i]['NAMA'] = $val->nama_posisi;
                    $items[$i]['UNIT'] = $val->unit;
                    $items[$i]['LINK_URL_INFO'] = $val->link_url_info;
                    $items[$i]['state'] = $this->hasunitchild($valinfoid) ? 'closed' : 'open';
                    $i++;
                }
                
                $result["rows"] = $items;
            } 
            // else 
            // {
            //     $sorder= " ORDER BY A.NAMA_POSISI";
            //     $parameter= array("TRIM('".$id."')");
            //     $statement= " TRIM(A.SUPERIOR_ID) = ?";

            //     $jabatan= new Jabatan();
            //     $datas= $jabatan->selectbyparams($parameter, $statement, $sorder)->get();

            //     $result["total"] = 0;
            //     $i=0;
            //     foreach ($datas as $key => $val) 
            //     {
            //         $valinfoid= trim($val->position_id);
            //         $result[$i]['ID'] = $valinfoid;
            //         $result[$i]['NAMA'] = $val->nama_posisi;
            //         $result[$i]['UNIT'] = $val->unit;
            //         $result[$i]['LINK_URL_INFO'] = $val->link_url_info;
            //         $result[$i]['state'] = $this->hasunitchild($valinfoid) ? 'closed' : 'open';
            //         $i++;
            //     }
            // }

        }

        // $sorder= "";
        // $parameter= array('TOP');
        // $statement= " A.SUPERIOR_ID = ?";

        // $jabatan= new Jabatan();
        // $datas= $jabatan->selectbyparams($parameter, $statement, $sorder)->get();

        // $result["total"] = 0;
        // $i=0;
        // foreach ($datas as $key => $val) 
        // {
        //     $valinfoid= trim($val->position_id);
        //     $items[$i]['ID'] = $valinfoid;
        //     $items[$i]['NAMA'] = $val->nama_posisi;
        //     $items[$i]['UNIT'] = $val->unit;
        //     $items[$i]['LINK_URL_INFO'] = $val->link_url_info;
        //     $items[$i]['state'] = $this->hasunitchild($valinfoid) ? 'closed' : 'open';
        //     $i++;
        // }
        // $result["rows"] = $items;

        return response()->json($result);
        
        // $this->data['jabatan'] = Jabatan::all();
        // $Sb = array();

        // return view('jabatan/daftar', $this->data, compact('Sb'));
    }

    public function hasunitchild($id)
    {
        $infosuperiorid= trim($id);

        $sorder= "";
        $parameter= array($infosuperiorid);
        $statement= " A.SUPERIOR_ID = ?";

        // $statement= " AND TRIM(A.SUPERIOR_ID) = TRIM('".$infosuperiorid."')";
            
        $child = new Jabatan();
        // DB::enableQueryLog();
        $datas= $child->selectbyparams($parameter, $statement, $sorder)->first();
        // dd(DB::getQueryLog());
        // print_r($datas);exit();
        // echo $child->query;exit;
        // $child->firstRow();
        $tempId= "";
        if (count($datas)>0) 
        {
            $tempId= $datas->position_id;
        }
        
        // echo $tempId;exit;
        if($tempId == "")
        return false;
        else
        return true;
        unset($child);
    }

    public function sinkron(Request $request)
    {
        $client = new Client();
        $response = $client->get('https://talentman.plnnusantarapower.co.id/api/daftar_jabatan_erm'); // Ganti URL sesuai dengan API yang Anda inginkan.

        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody(), true);
            // return $data;
            for ($i=0; $i < count($data); $i++) { 
                $position_id= $data[$i]['POSITION_ID'];
                $nama_posisi= $data[$i]['NAMA_POSISI'];
                $superior_id= $data[$i]['SUPERIOR_ID'];
                $kode_kategori= $data[$i]['KODE_KATEGORI'];
                $kategori= $data[$i]['KATEGORI'];
                $kode_kelompok_jabatan= $data[$i]['KODE_KELOMPOK_JABATAN'];
                $kelompok_jabatan= $data[$i]['KELOMPOK_JABATAN'];
                $kode_jenjang_jabatan= $data[$i]['KODE_JENJANG_JABATAN'];
                $jenjang_jabatan= $data[$i]['JENJANG_JABATAN'];
                $kode_klasifikasi_unit= $data[$i]['KODE_KLASIFIKASI_UNIT'];
                $klasifikasi_unit= $data[$i]['KLASIFIKASI_UNIT'];
                $kode_unit= $data[$i]['KODE_UNIT'];
                $unit= $data[$i]['UNIT'];
                $kode_ditbid= $data[$i]['KODE_DITBID'];
                $ditbid= $data[$i]['DITBID'];
                $kode_bagian= $data[$i]['KODE_BAGIAN'];
                $bagian= $data[$i]['BAGIAN'];
                $occup_status= $data[$i]['OCCUP_STATUS'];
                $nama_lengkap= $data[$i]['NAMA_LENGKAP'];
                $email= $data[$i]['EMAIL'];
                $nid= $data[$i]['NID'];
                $posisi= $data[$i]['POSISI'];
                $change_reason= $data[$i]['CHANGE_REASON'];

                // $kodes= str_replace(" ", "", $kode);

                // // $statement= " AND A.POSITION_ID LIKE '%".$reqPositionId."%'";
                // $statement= " AND TRIM(POSITION_ID) = TRIM('".$reqPositionId."')";
                // $check->selectByParamsCheckJabatan(array(), -1, -1, $statement);
                // // echo $check->query;exit;
                // $check->firstRow();
                // $checkkode= $check->getField("POSITION_ID");

                $cek= Jabatan::where('position_id', $position_id)->count();

                if ($cek) 
                {
                    $appr_kkpupd= Jabatan::where('position_id', $position_id)
                    ->update([
                        'nama_posisi' => $nama_posisi,
                        'superior_id' => $superior_id,
                        'kode_kategori' => $kode_kategori,
                        'kategori' => $kategori,
                        'kode_kelompok_jabatan' => $kode_kelompok_jabatan,
                        'kelompok_jabatan' => $kelompok_jabatan,
                        'kode_jenjang_jabatan' => $kode_jenjang_jabatan,
                        'jenjang_jabatan' => $jenjang_jabatan,
                        'kode_klasifikasi_unit' => $kode_klasifikasi_unit,
                        'klasifikasi_unit' => $klasifikasi_unit,
                        'kode_unit' => $kode_unit,
                        'unit' => $unit,
                        'kode_ditbid' => $kode_ditbid,
                        'ditbid' => $ditbid,
                        'kode_bagian' => $kode_bagian,
                        'bagian' => $bagian,
                        'occup_status' => $occup_status,
                        'nama_lengkap' => $nama_lengkap,
                        'email' => $email,
                        'nid' => $nid,
                        'posisi' => $posisi,
                        'change_reason' => $change_reason,
                        'updated_at' => date("Y-m-d H:i:s"),
                    ]);
                }
                else
                {
                    $item = array(
                        'position_id' => $position_id,
                        'nama_posisi' => $nama_posisi,
                        'superior_id' => $superior_id,
                        'kode_kategori' => $kode_kategori,
                        'kategori' => $kategori,
                        'kode_kelompok_jabatan' => $kode_kelompok_jabatan,
                        'kelompok_jabatan' => $kelompok_jabatan,
                        'kode_jenjang_jabatan' => $kode_jenjang_jabatan,
                        'jenjang_jabatan' => $jenjang_jabatan,
                        'kode_klasifikasi_unit' => $kode_klasifikasi_unit,
                        'klasifikasi_unit' => $klasifikasi_unit,
                        'kode_unit' => $kode_unit,
                        'unit' => $unit,
                        'kode_ditbid' => $kode_ditbid,
                        'ditbid' => $ditbid,
                        'kode_bagian' => $kode_bagian,
                        'bagian' => $bagian,
                        'occup_status' => $occup_status,
                        'nama_lengkap' => $nama_lengkap,
                        'email' => $email,
                        'nid' => $nid,
                        'posisi' => $posisi,
                        'change_reason' => $change_reason,
                        'created_at' => date("Y-m-d H:i:s"),
                    );

                    // $transaction = Jabatan::create($item);
                    // $dataToInsert = [
                    //     'field1' => 'value1',
                    //     'field2' => 'value2',
                    //     // Tambahkan kolom dan nilai yang ingin Anda sisipkan di sini
                    // ];

                    DB::table('jabatan')->insert($item);
                }
            }

            // return $response->getBody();

            // Lakukan sesuatu dengan data yang diterima dari API.
            // Misalnya, tampilkan data atau simpan ke database.
        } else {
            // Handle kesalahan jika diperlukan.
        }

        $request->session()->flash('success', 'Data berhasil disinkronkan');

        return redirect('jabatan/daftar');
    }

    public function delete(Request $request, $id)
    {
        $item = Jabatan::find($id);
        $item->delete();

        $request->session()->flash('success', 'Data berhasil dihapus');

        return redirect('jabatan/daftar');
    }

    public function search(Request $request, $id)
    {
        $search = $request->get('search');
        $jabatan = pjb::where('name','LIKE', '%'.$search.'%')->paginate(10);
        return view('jabatan/daftar.index', compact('jabatan'));
    }

    public function detail(Request $request, $id)
    {
        if($request->isMethod("get"))
        {
            $item['jabatan'] = Jabatan::find($id);
            $item['disabled']= 'disabled';
            // $item['strategi_bisnis'] = Strategi_bisnis::all();
            return view('jabatan/tambah',$item);
        }
        elseif ($request->isMethod('post')) 
        {
            # code...
            $item = Jabatan::find($id);
            $item->save();
            return redirect('jabatan/daftar');
        }
    }
}
