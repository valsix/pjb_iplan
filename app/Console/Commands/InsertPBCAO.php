<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Entities\PBCAO;
use App\Entities\Distrik;
use App\Entities\Template;

class InsertPBCAO extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:querypbcao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert Query PBC AO';

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

        PBCAO::query()->truncate();

        $tahun = Template::select('tahun')->where('jenis_id', 2)->orWhere('jenis_id',1)->orWhere('jenis_id',3)->distinct()->get();

        $distrik = Distrik::select('code1')->distinct()->get();

        // dd($distrik);
        foreach ($distrik as $key => $value1) {
            for ($i=0; $i < count($tahun); $i++) {
                $query_ao = $this->queryPBCAO($value1->code1, substr($tahun[$i]['tahun'], -2));
                // $query_ao = $this->queryPBCAO($value1->code1);

                $n = 0;
                $insert_data = array();
                foreach ($query_ao as $qao => $data_ao) {
                    if((trim($data_ao->dstrct_code) != "" && trim($data_ao->dstrct_code) != NULL)
                        && (trim($data_ao->prk_kegiatan) != "" && trim($data_ao->prk_kegiatan) != NULL)
                        && (trim($data_ao->prk_inti) != "" && trim($data_ao->prk_inti) != NULL)
                        && (trim($data_ao->parent_proj) != "" && trim($data_ao->parent_proj) != NULL)
                        && (trim($data_ao->proj_desc) != "" && trim($data_ao->proj_desc) != NULL)
                        && (trim($data_ao->tot_est_cost) != "" && trim($data_ao->tot_est_cost) != NULL)
                        && (trim($data_ao->commitments) != "" && trim($data_ao->commitments) != NULL)
                        && (trim($data_ao->actuals) != "" && trim($data_ao->actuals) != NULL)) {
                        array_push($insert_data, $data_ao);
                    }
                }

                $insert = array();
                foreach ($insert_data as $key => $value) {
                    $insert[] = [
                        'dstrct_code' => trim($value->dstrct_code),
                        'prk_kegiatan' => trim($value->prk_kegiatan),
                        'prk_inti' => intval(trim($value->prk_inti)),
                        'parent_proj' => trim($value->parent_proj),
                        'proj_desc' => trim($value->proj_desc),
                        'tot_est_cost' => trim($value->tot_est_cost),
                        'commitments' => trim($value->commitments),
                        'actuals' => trim($value->actuals),
                    ];
                }

                if(!empty($insert)) {
                    $insert_chunk = array_chunk($insert, 3000, true); //dipisah per 3000 array, karena pdo postgresqlnya tidak bisa insert data banyak sekaligus
                    // dump($insert_chunk);

                    foreach ($insert_chunk as $chunk_data) {
                        PBCAO::insert($chunk_data);
                    }
                }
            }
        }
    }

    public function queryPBCAO($distrik, $tahun)
    {
        # code...
        $query_ao = DB::connection('oracle')->
    				select("SELECT
                    a.DSTRCT_CODE,a.PROJECT_NO AS prk_kegiatan,b.PARENT_PROJ AS prk_inti,
                    prk_parent.PARENT_PROJ,
                    b.PROJ_DESC,a.TOT_EST_COST
                    ,NVL(c.COMMITMENTS_IR,0) +
                    NVL(d.COMMITMENTS_PR,0) AS COMMITMENTS
                    ,NVL(e.ACTUALS,0) AS ACTUALS
                FROM MSF667 a JOIN MSF660 b ON a.DSTRCT_CODE = b.DSTRCT_CODE AND  a.PROJECT_NO = b.PROJECT_NO
                LEFT JOIN msf660 prk_parent ON b.DSTRCT_CODE  = PRK_PARENT.DSTRCT_CODE  AND rtrim(b.PARENT_PROJ) = rtrim(PRK_PARENT.PROJECT_NO)
                -----JOIN SUBQUERY ACTUAL
                LEFT JOIN (
                SELECT
                    a.DSTRCT_CODE,NVL(rtrim(a.PROJECT_NO),rtrim(b.PROJECT_NO)) AS project_no,SUM(a.TRAN_AMOUNT) AS ACTUALS
                FROM
                    msf900 a LEFT JOIN msf620 b ON a.DSTRCT_CODE = b.DSTRCT_CODE AND rtrim(a.WORK_ORDER) = rtrim(b.WORK_ORDER)
                WHERE
                    (Length(Trim(account_code)) = 19 OR Trim(account_code) IN ('100100000','100300000','100401001','100402009','100402001','100607201')
                    OR trim(ACCOUNT_CODE) LIKE '1__3__000')
                    AND a.full_period >= to_char(trunc(add_months(SYSDATE,-12*3),'YEAR'),'YYYYMM')
                      AND  a.full_period <= to_char(SYSDATE,'YYYYMM')
                      AND tran_type IN ('PRD','SVR','ISS','ISU','NOI','CHG','CHQ','MPJ','ADJ','SAD','ISU','MRJ')
                      AND SUBSTR(NVL(rtrim(a.PROJECT_NO),rtrim(b.PROJECT_NO)),3,2) NOT IN ('4A','4B','3Y')
                      AND a.DSTRCT_CODE  = '".$distrik."'
                      AND NVL(rtrim(a.PROJECT_NO),rtrim(b.PROJECT_NO)) LIKE '".$tahun."' || '%'
                GROUP BY
                    a.DSTRCT_CODE,NVL(rtrim(a.PROJECT_NO),rtrim(b.PROJECT_NO))
                ) e ON a.DSTRCT_CODE  = e.DSTRCT_CODE AND a.PROJECT_NO  = e.PROJECT_NO
                -----JOIN SUBQUERY COMMITMENT DARI ISSUE REQUISITION
                LEFT JOIN (
                SELECT dstrct_code,project_no,sum(total) as COMMITMENTS_IR FROM (
                SELECT
                    a.DSTRCT_CODE,
                    NVL(rtrim(item.PROJECT_NO),NVL(rtrim(header.PROJECT_NO),NVL(rtrim(wo_item.PROJECT_NO),rtrim(wo_header.PROJECT_NO)))) AS project_no,
                    (a.QTY_REQ-a.QTY_ISSUED)*a.ITEM_PRICE AS total
                FROM
                msf141 a JOIN msf140 b ON a.DSTRCT_CODE = b.DSTRCT_CODE AND a.IREQ_NO = b.IREQ_NO
                LEFT JOIN msf232 header ON a.DSTRCT_CODE = header.DSTRCT_CODE AND rtrim(header.REQUISITION_NO) = a.IREQ_NO|| '  0000' AND header.REQ_232_TYPE = 'I'
                LEFT JOIN msf232 item ON a.DSTRCT_CODE = item.DSTRCT_CODE AND rtrim(item.REQUISITION_NO) = a.IREQ_NO|| '  0' || a.IREQ_ITEM AND item.REQ_232_TYPE = 'I'
                LEFT JOIN msf620 wo_item ON wo_item.DSTRCT_CODE = item.DSTRCT_CODE AND wo_item.WORK_ORDER = item.WORK_ORDER
                LEFT JOIN msf620 wo_header ON wo_header.DSTRCT_CODE = header.DSTRCT_CODE AND wo_header.WORK_ORDER = header.WORK_ORDER
                WHERE a.ITEM_141_STAT IN ('0','1','2','3')
                AND to_date(b.CREATION_DATE,'YYYYMMDD') > trunc(add_months(SYSDATE,-12*2),'YEAR')
                AND substr(NVL(rtrim(item.PROJECT_NO),NVL(rtrim(header.PROJECT_NO),NVL(rtrim(wo_item.PROJECT_NO),rtrim(wo_header.PROJECT_NO)))),3,2) NOT IN ('4A','4B','3Y')
                AND a.DSTRCT_CODE = '".$distrik."'
                AND NVL(rtrim(item.PROJECT_NO),NVL(rtrim(header.PROJECT_NO),NVL(rtrim(wo_item.PROJECT_NO),rtrim(wo_header.PROJECT_NO)))) LIKE '".$tahun."' || '%'
                ) GROUP BY dstrct_code,project_no) c ON rtrim(a.PROJECT_NO) = rtrim(c.PROJECT_NO)  AND a.DSTRCT_CODE  = c.DSTRCT_CODE
                -----JOIN SUBQUERY COMMITMENT DARI PURCHASE REQUISITION
                LEFT JOIN (
                SELECT dstrct_code,project_no,sum(total) as COMMITMENTS_PR FROM (
                SELECT
                    a.DSTRCT_CODE,
                    NVL(rtrim(item.PROJECT_NO),NVL(rtrim(header.PROJECT_NO),NVL(rtrim(wo_item.PROJECT_NO),rtrim(wo_header.PROJECT_NO)))) AS project_no,
                    CASE WHEN a.status_231 = '0' THEN
                        CASE
                            WHEN a.REQ_TYPE IN 'G' THEN  a.EST_PRICE * a.PR_QTY_REQD
                            ELSE a.EST_PRICE
                        END
                     WHEN a.status_231 = '2' THEN
                        CASE WHEN a.REQ_TYPE IN 'G' THEN  b.CURR_NET_PR_P * b.CURR_QTY_P
                            ELSE b.CURR_NET_PR_P END
                     WHEN a.status_231 = '3' THEN
                        CASE WHEN a.REQ_TYPE IN 'G' THEN  b.CURR_NET_PR_P*(b.CURR_QTY_P-a.PR_QTY_RCVD)
                        ELSE b.CURR_NET_PR_P - b.VAL_RCPT_FOR END
                     ELSE 0
                END as TOTAL
                FROM
                msf231 a
                LEFT JOIN msf221 b ON a.DSTRCT_CODE = b.DSTRCT_CODE AND a.PO_NO = b.PO_NO AND a.PO_ITEM_NO = b.PO_ITEM_NO
                LEFT JOIN msf232 item ON a.DSTRCT_CODE = item.DSTRCT_CODE AND rtrim(item.REQUISITION_NO) = a.PREQ_NO||'  '|| a.PREQ_ITEM_NO AND item.REQ_232_TYPE = 'P'
                LEFT JOIN msf232 header ON a.DSTRCT_CODE = header.DSTRCT_CODE AND rtrim(header.REQUISITION_NO) = a.PREQ_NO||'  000' AND header.REQ_232_TYPE = 'P'
                LEFT JOIN msf620 wo_item ON wo_item.DSTRCT_CODE = item.DSTRCT_CODE AND wo_item.WORK_ORDER = item.WORK_ORDER
                LEFT JOIN msf620 wo_header ON wo_header.DSTRCT_CODE = header.DSTRCT_CODE AND wo_header.WORK_ORDER = header.WORK_ORDER
                WHERE a.STATUS_231 IN  ('0','1','2','3')
                AND to_date(a.CREATION_DATE,'YYYYMMDD') > trunc(add_months(SYSDATE,-12*2),'YEAR')
                AND substr(NVL(rtrim(item.PROJECT_NO),NVL(rtrim(header.PROJECT_NO),NVL(rtrim(wo_item.PROJECT_NO),rtrim(wo_header.PROJECT_NO)))),3,2) NOT IN ('4A','4B','3Y')
                AND a.DSTRCT_CODE = '".$distrik."'
                AND NVL(rtrim(item.PROJECT_NO),NVL(rtrim(header.PROJECT_NO),NVL(rtrim(wo_item.PROJECT_NO),rtrim(wo_header.PROJECT_NO)))) LIKE '".$tahun."' || '%'
                ) GROUP BY dstrct_code,project_no
                ) d ON a.PROJECT_NO = d.PROJECT_NO  AND a.DSTRCT_CODE  = d.DSTRCT_CODE
                WHERE
                     LENGTH(trim(a.PROJECT_NO)) = 8 AND
                        a.CATEGORY_CODE = ' ' AND
                    a.EXP_REV_IND = 'E' AND
                    (999999-a.revsd_period) = '999999' AND
                    TRIM(a.dstrct_code) = '".$distrik."' AND
                    substr(a.PROJECT_NO,4,1) NOT IN ('A','B','C','D','E','F','Y') AND
                    a.PROJECT_NO LIKE '".$tahun."' || '%'");
        return $query_ao;
    }
}
