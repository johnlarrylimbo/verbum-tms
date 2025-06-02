<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class BasicEcclesialCommunityService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadBasicEcclesialCommunityLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_bec_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting bec list', 500, $exception);
			}
	}

	public function loadBasicEcclesialCommunityLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_bec_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting bec list by keyword', 500, $exception);
		}
	}

  public function addBasicEcclesialCommunity(int $param_parish_id, string $param_name, string $param_address, int $param_user_id)
	{
		try {
      $parish_id = $param_parish_id ?? 0;
			$name  = $param_name ?? '';
			$address  = $param_address ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_bec_ins')
								->stored_procedure_params([':p_parish_id, :p_name, :p_address, :result_id'])
								->stored_procedure_values([ $parish_id, $name, $address, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new basic ecclesial community:', [
                    'affiliated_parish_id' => $parish_id,
										'name' => $name,
										'address' => $address,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding bec', 500, $exception);
		}
	}

	public function getBasicEcclesialCommunityById(int $param_bec_id)
	{
		try {
			$bec_id = $param_bec_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_bec_by_id_sel')
								->stored_procedure_params([':p_bec_id'])
								->stored_procedure_values([ $bec_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting bec by id', 500, $exception);
		}
	}

	public function updateBasicEcclesialCommunityById(int $param_bec_id, int $param_parish_id, string $param_name, string $param_address, int $param_user_id)
	{
		try {
			$bec_id = $param_bec_id ?? 0;
			$parish_id = $param_parish_id ?? 0;
			$name = $param_name ?? '';
			$address = $param_address ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_bec_by_id_upd')
								->stored_procedure_params([':p_bec_id, :p_parish_id, :p_name, :p_address, :result_id'])
								->stored_procedure_values([ $bec_id, $parish_id, $name, $address, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated basic ecclesial community:', [
										'bec_id' => $bec_id,
                    'parish_id' => $parish_id,
										'name' => $name,
										'address' => $address,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating bec by id', 500, $exception);
		}
	}

	public function updateBasicEcclesialCommunityStatusById(int $param_bec_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$bec_id  = $param_bec_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_bec_status_by_id_upd')
								->stored_procedure_params([':p_bec_id , :p_statuscode, :result_id'])
								->stored_procedure_values([ $bec_id , $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated bec status:', [
										'bec_id ' => $bec_id , 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating bec status by id', 500, $exception);
		}
	}
    
}
