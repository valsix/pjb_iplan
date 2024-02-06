<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Entities\PgdlPljprkAi;
use App\Entities\PgdlPljprkAo;
use App\Entities\Distrik;

class QueryPljprkAiController extends Controller
{
    public function index()
    {
    	ini_set('max_execution_time', 0); //unlimited

    	DB::table('pgdl_pljprk_ai')->where('years', date("Y"))->truncate();

    	// if(date("m")=='01') {
    		$start_month = date("Y")."01";
    	// }
    	// else {
    		// $start_month = date("Y").date("m")-1;
    	// }
    	
    	// sementara start month dimulai dari januari
    	// $start_month = "201901";

    	$finish_month = date("Y").date("m");
    	// $finish_month = '2019'.date("m");
    	// $finish_month = $start_month;
    	// $finish_month = "20190505";
    	$distrik = Distrik::select('code1')->distinct()->get();
    	foreach ($distrik as $key => $value1) {
	    	$query_ai = $this->query_pljprk_ai($value1->code1, $start_month, $finish_month);
	    	// $query_ai = $this->query_pljprk_ai('UJPT', $start_month, $finish_month);
	    	// dump($query_ai);

			$n = 0;
			$insert_data = array();
			foreach ($query_ai as $qai => $data_ai) {
				if((trim($data_ai->dstrct_code) != "" && trim($data_ai->dstrct_code) != NULL)
					&& (trim($data_ai->tran_type) != "" && trim($data_ai->tran_type) != NULL)
					&& (trim($data_ai->account_code) != "" && trim($data_ai->account_code) != NULL)
					&& (trim($data_ai->years) != "" && trim($data_ai->years) != NULL)
					&& (trim($data_ai->months) != "" && trim($data_ai->months) != NULL)
					&& (trim($data_ai->project_no) != "" && trim($data_ai->project_no) != NULL)
					&& (trim($data_ai->creation_date) != "" && trim($data_ai->creation_date) != NULL)) {
					array_push($insert_data, $data_ai);	
				}
			}
			// dump($insert_data);

			$insert = array();
			foreach ($insert_data as $key => $value) {
				$insert[] = [
					'dstrct_code' => trim($value->dstrct_code),
					'tran_type' => trim($value->tran_type),
					'account_code' => trim($value->account_code),
					'years' => trim($value->years),
					'months' => intval(trim($value->months)),
					'tran_amount' => trim($value->tran_amount) == '' ? NULL : trim($value->tran_amount),
					'preq_no' => trim($value->preq_no) == '' ? NULL : trim($value->preq_no),
					'preq_item_no' => trim($value->preq_item_no) == '' ? NULL : trim($value->preq_item_no),
					'description' => trim($value->description) == '' ? NULL : trim($value->description),
					'po_no' => trim($value->po_no) == '' ? NULL : trim($value->po_no),
					'po_item' => trim($value->po_item) == '' ? NULL : trim($value->po_item),
					'val_required' => trim($value->val_required) == '' ? NULL : trim($value->val_required),
					'val_received' => trim($value->val_received) == '' ? NULL : trim($value->val_received),
					'project_no' => trim($value->project_no),
					'creation_date' => trim($value->creation_date),
				];

				// if(trim($value->project_no)=='194A0301') {
				// if(trim($value->dstrct_code)=='UJKT') {
				// 	dump($insert);
				// }
			}
			// dump($value1->code1, count($query_ai));
			// dump(count($query_ai));
			// die();
			
			if(!empty($insert)) {
				$insert_chunk = array_chunk($insert, 3000, true); //dipisah per 3000 array, karena pdo postgresqlnya tidak bisa insert data banyak sekaligus
				// dump($insert_chunk);

				foreach ($insert_chunk as $chunk_data) {
					PgdlPljprkAi::insert($chunk_data);
				}
			}
		}
			// die();

  //   	$pljprkai = new PgdlPljprkAi;
  //   	$pljprkai->dstrct_code = ''; //not null
		// $pljprkai->tran_type = ''; //not null
		// $pljprkai->account_code = ''; //not null
		// $pljprkai->years = 2020; //integer not null
		// $pljprkai->months = 2; //integer not null
		// $pljprkai->tran_amount = '100';
		// $pljprkai->preq_no = null; >> diganti varchar
		// $pljprkai->preq_item_no = null; >> diganti varchar
		// $pljprkai->description = '';
		// $pljprkai->po_no = '';
		// $pljprkai->po_item = '1';
		// $pljprkai->val_required = null;
		// $pljprkai->val_received = null;
		// $pljprkai->project_no = ''; //not null
		// $pljprkai->description_1 = ''; >> tidak dipakai
		// $pljprkai->creation_date = date('Y-m-d'); //date not null
		// $pljprkai->save();

    	// dd($query_ai);
    	dd('finish');
    }

    public function query_pljprk_ai($distrik, $start_month, $finish_month) {
    	$query_ai = DB::connection('oracle')->
    				select("SELECT 
						a.dstrct_code,
						  a.tran_type,
						  a.account_code,
						  a.currency_type,
						  substr(a.full_period,1,4) AS years,
						  substr(a.full_period,5,2) AS months,
						  a.tran_amount,
						  a.preq_no,
						  a.preq_item_no,
						  (SELECT x1.ITEM_DESCX1 || x1.ITEM_DESCX2 || x1.ITEM_DESCX3 || x1.ITEM_DESCX4 AS description FROM msf231 x1 WHERE x1.dstrct_code = a.dstrct_code AND x1.preq_no = a.preq_no AND x1.preq_item_no = a.preq_item_no ) AS description,
						  a.po_no,
						  a.po_item,
						  (SELECT 
						      CASE WHEN x2.PO_ITEM_TYPE <> 'S' 
						          THEN x2.GROSS_PRICE_P*x2.CURR_QTY_P ELSE x2.GROSS_PRICE_P END AS val_req FROM msf221 x2 WHERE x2.DSTRCT_CODE = a.DSTRCT_CODE AND x2.PO_NO = a.po_no AND x2.PO_ITEM_NO = a.po_item) AS val_required,
						  CASE WHEN a.tran_type = 'PRD' OR a.tran_type = 'SVR' THEN a.tran_amount END AS val_received,
						  a.work_order,
						  a.issue_req_no,
						  a.ireq_item_no,
						  a.stock_code,
						  a.project_no,
						  a.rec900_type,
						  a.description,
						  a.mims_sl_key,
						  a.bank_acct_no,
						  a.branch_code,
						  a.EXT_INV_NO,
						  a.cheque_no,
						  a.creation_date
						FROM 
						(
						SELECT
						  a.dstrct_code,
						  a.account_code,
						  a.currency_type,
						  a.full_period,
						  a.tran_amount,
						  a.preq_no,
						  a.preq_item_no,
						  a.po_no,
						  a.PO_ITEM,
						  a.QTY_RCV_UOP,
						  a.work_order,
						  a.issue_req_no,
						  a.ireq_item_no,
						  a.stock_code,
						  CASE
						    WHEN trim(a.project_no) IS NULL THEN 
						      CASE 
						        WHEN a.tran_type = 'PRD' AND trim(a.work_order) IS NULL
						          THEN Trim((join(cursor(
						                                      SELECT CASE WHEN trim(project_no) IS NULL AND trim(work_order) IS NOT NULL THEN Trim(join(CURSOR(SELECT project_no FROM msf620 x2 WHERE x2.work_order = x1.work_order AND x2.dstrct_code = x1.dstrct_code)))
						                                    ELSE project_no END AS project_no FROM msf232 x1 WHERE x1.dstrct_code = a.dstrct_code AND substr(trim(x1.REQUISITION_NO),1,6) = a.preq_no
						          ))))
						        WHEN a.tran_type = 'PRD' AND trim(a.project_no) IS null AND trim(a.work_order) IS NOT null
						              THEN Trim(join(CURSOR(SELECT project_no FROM msf620 x2 WHERE x2.work_order = a.work_order AND x2.dstrct_code = a.dstrct_code)))
						        WHEN a.tran_type = 'SVR' AND trim(a.work_order) IS NULL
						                THEN Trim((join(cursor(
						                                      SELECT CASE WHEN trim(project_no) IS NULL AND trim(work_order) IS NOT NULL THEN Trim(join(CURSOR(SELECT project_no FROM msf620 x2 WHERE x2.work_order = x1.work_order AND x2.dstrct_code = x1.dstrct_code)))
						                                    ELSE project_no END AS project_no FROM msf232 x1 WHERE x1.dstrct_code = a.dstrct_code AND substr(trim(x1.REQUISITION_NO),1,6) = a.preq_no
						          ))))
						        WHEN a.tran_type = 'SVR' AND trim(a.project_no) IS null AND trim(a.work_order) IS NOT null
						              THEN Trim(join(CURSOR(SELECT project_no FROM msf620 x2 WHERE x2.work_order = a.work_order AND x2.dstrct_code = a.dstrct_code)))      
						        WHEN tran_type = 'ISS' AND trim(work_order) IS NOT null
						              THEN Trim(join(CURSOR(SELECT project_no FROM msf620 x3 WHERE x3.work_order = a.work_order AND x3.dstrct_code = a.dstrct_code)) )
						        WHEN tran_type = 'ISS' AND trim(work_order) IS null
						              THEN Trim((join(cursor(SELECT x4.project_no FROM msf232 x4 WHERE a.dstrct_code = x4.dstrct_code AND trim(a.issue_req_no) = substr(trim(REQUISITION_NO),1,6) AND x4.req_232_type='I'))))
						        WHEN tran_type = 'CHG' AND trim(work_order) IS NOT  null
						             THEN Trim(join(CURSOR(SELECT project_no FROM msf620 x3 WHERE x3.work_order = a.work_order AND x3.dstrct_code = a.dstrct_code)) )
						        WHEN tran_type = 'MPJ' AND trim(work_order) IS NOT  null
						             THEN Trim(join(CURSOR(SELECT project_no FROM msf620 x3 WHERE x3.work_order = a.work_order AND x3.dstrct_code = a.dstrct_code)) )
						        WHEN tran_type = 'MRJ' AND trim(work_order) IS NOT  null
						             THEN Trim(join(CURSOR(SELECT project_no FROM msf620 x3 WHERE x3.work_order = a.work_order AND x3.dstrct_code = a.dstrct_code)) )
						        WHEN tran_type = 'NOI' AND trim(work_order) IS NOT  null
						             THEN Trim(join(CURSOR(SELECT project_no FROM msf620 x3 WHERE x3.work_order = a.work_order AND x3.dstrct_code = a.dstrct_code)) ) 
						        ELSE
						          a.project_no
						        END
						    ELSE
						        project_no
						    END AS project_no,
						  a.rec900_type,
						  a.tran_type,
						  CASE WHEN a.inv_item_desc <> ' ' THEN a.inv_item_desc
						    WHEN a.journal_desc <> ' ' THEN a.journal_desc
						    WHEN a.fa_tran_desc <> ' ' THEN a.fa_tran_desc
						    WHEN a.description <> ' ' THEN a.description
						    WHEN a.rloc_desc <> ' ' THEN a.rloc_desc
						    WHEN a.tran_type IN ('SVR', 'SRD', 'PRD') THEN a.po_no
						    WHEN a.tran_type IN ('ISS') THEN a.stock_code
						    ELSE a.desc_line
						  END description,
						  a.mims_sl_key,
						  a.bank_acct_no,
						  a.branch_code,
						  a.EXT_INV_NO,
						  a.cheque_no,
						  a.creation_date
						FROM
						  msf900 a
						WHERE
						    a.full_period >= ".$start_month."  AND
							  a.full_period <= ".$finish_month."
							  AND dstrct_code IN ('".$distrik."' )
						  AND (Length(Trim(account_code)) = 19 OR Trim(account_code) IN ('100100000','100300000','100401001','100402009','100402001'))
						  AND substr(project_no,3,2) IN ('4A','4B') 
						  --AND substr(project_no,3,2) IN ('3Y') 
						 -- AND a.po_no='OI0217'
						ORDER BY
						  a.dstrct_code,
						  a.creation_date
						) a");
		return $query_ai;
    }
}
