<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Entities\MSF900;
use App\Entities\Distrik;
use App\Entities\Template;

class InsertMSF900 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:querymsf900';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert Query MSF900';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('max_execution_time', 0); //unlimited

        MSF900::query()->truncate();

        $start_month = date("Y")."01"; // dimulai dari 01 januari tahun existing
        // $start_month = "202001";
        $finish_month = date("Y").date("m"); // diakhiri di tanggal existing
        // $finish_month = "202012";
        
        $distrik = Distrik::select('code1')->distinct()->get();

        foreach ($distrik as $key => $value1) {
            $query_msf900 = $this->queryMSF900($value1->code1, $start_month, $finish_month);
            
            $insert_data = array();
            foreach ($query_msf900 as $qao => $data_ao) {
                if((trim($data_ao->dstrct_code) != "" && trim($data_ao->dstrct_code) != NULL)
                && (trim($data_ao->account_code) != "" && trim($data_ao->account_code) != NULL)
                && (trim($data_ao->expense_element) != "" && trim($data_ao->expense_element) != NULL)
                && (trim($data_ao->tran_amount) != "" && trim($data_ao->tran_amount) != NULL)
                && (trim($data_ao->full_period) != "" && trim($data_ao->full_period) != NULL)) {
                    array_push($insert_data, $data_ao);	
                }
            }
            
            $insert = array();
            foreach ($insert_data as $key => $value) {
                $insert[] = [
                    'dstrct_code' => trim($value->dstrct_code),
                    'account_code' => trim($value->account_code),
                    'expense_element' => trim($value->expense_element),
                    'tran_amount' => intval(trim($value->tran_amount)),
                    'full_period' => trim($value->full_period),
                ];
            }
            
            if(!empty($insert)) {
                $insert_chunk = array_chunk($insert, 3000, true); //dipisah per 3000 array, karena pdo postgresqlnya tidak bisa insert data banyak sekaligus
                // dump($insert_chunk);

                foreach ($insert_chunk as $chunk_data) {
                    MSF900::insert($chunk_data);
                }
            }
        }
    }

    public function queryMSF900($distrik, $start_month, $finish_month)
    {
        # code...
        $query_msf900 = DB::connection('oracle')->
    				select("SELECT 
                        a.ACCOUNT_CODE,
                        Substr(a.ACCOUNT_CODE,16,4) AS EXPENSE_ELEMENT,
                        a.DSTRCT_CODE,
                        a.TRAN_AMOUNT,
                        a.FULL_PERIOD
                    FROM
                        msf900 a
                    WHERE
                        --a.CREATION_DATE IN '20210111' AND
                        --a.ACCOUNT_CODE IN 'BKP330069999911F199' AND
                        --a.TRAN_TYPE IN 'MPJ' --AND
                        a.DSTRCT_CODE IN '".$distrik."' AND
                        --a.TRAN_TYPE IN 'RFB'
                        --a.MIMS_SL_KEY IN 'BOKLBS' AND
                        a.FULL_PERIOD BETWEEN '".$start_month."' AND '".$finish_month."'
                        --a.ACCOUNT_CODE IN '100607328' --AND
                        --a.FULL_PERIOD IN '202010' ---AND '202009'
                        --a.TRAN_GROUP_KEY IN ()
                    ");
        return $query_msf900;	
    }
}
