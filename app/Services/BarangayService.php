<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class BarangayService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadBarangayLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_barangays_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting barangay list', 500, $exception);
			}
	}

	public function loadBarangayLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_barangays_lst_by_keyword')
									->stored_procedure_params([':keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting barangay list by keyword', 500, $exception);
		}
	}

	public function getBarangayById(int $param_barangay_id)
	{
		try {
			$barangay_id = $param_barangay_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_barangay_by_id_sel')
								->stored_procedure_params([':barangay_id'])
								->stored_procedure_values([$barangay_id])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting barangay by id', 500, $exception);
		}
	}

	public function updateBarangayById(int $param_barangay_id, int $param_province_id, int $param_city_municipality_id, string $param_label)
	{
		try {
			$barangay_id = $param_barangay_id ?? 0;
			$province_id = $param_province_id ?? 0;
			$city_municipality_id = $param_city_municipality_id ?? 0;
			$label = $param_label ?? '';
			$result = $this->sp
								->stored_procedure('pr_datims_barangay_by_id_upd')
								->stored_procedure_params([':barangay_id, :province_id, :city_municipality_id, :label, :result_id'])
								->stored_procedure_values([$barangay_id, $province_id, $city_municipality_id, $label, 0])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating barangay by id', 500, $exception);
		}
	}

	public function updateBarangayStatusById(int $param_barangay_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$barangay_id = $param_barangay_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_barangay_status_by_id_upd')
								->stored_procedure_params([':barangay_id, :statuscode, :result_id'])
								->stored_procedure_values([$barangay_id, $statuscode, 0])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated barangay status:', [
										'barangay_id' => $barangay_id, 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating barangay status by id', 500, $exception);
		}
	}
    
}
