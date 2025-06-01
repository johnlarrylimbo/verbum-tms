<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class DioceseService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadDioceseLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_diocese_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting diocese list', 500, $exception);
			}
	}

	public function loadDioceseLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_diocese_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting diocese list by keyword', 500, $exception);
		}
	}

  public function addDiocese(string $param_name, int $param_archbishop_id, int $param_vicar_general_id, int $param_chancellor_id, string $param_address, string $param_contact_number, string $param_email_address, int $param_user_id)
	{
		try {
      $name  = $param_name ?? '';
      $archbishop_id = $param_archbishop_id ?? 0;
      $vicar_general_id = $param_vicar_general_id ?? 0;
      $chancellor_id = $param_chancellor_id ?? 0;
			$address = $param_address ?? '';
      $contact_number = $param_contact_number ?? '';
      $email_address = $param_email_address ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_diocese_ins')
								->stored_procedure_params([':p_name, :p_archbishop_id, :p_vicar_general_id, :p_chancellor_id, :p_address, :p_contact_number, :p_email_address, :p_result_id'])
								->stored_procedure_values([ $name, $archbishop_id, $vicar_general_id, $chancellor_id, $address, $contact_number, $email_address, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new diocese:', [
                    'name' => $name,
										'archbishop_id' => $archbishop_id,
										'vicar_general_id' => $vicar_general_id,
                    'chancellor_id' => $chancellor_id,
                    'address' => $address,
                    'contact_number' => $contact_number,
                    'email_address' => $email_address,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding diocese', 500, $exception);
		}
	}

	public function getDioceseById(int $param_diocese_id)
	{
		try {
			$diocese_id = $param_diocese_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_diocese_by_id_sel')
								->stored_procedure_params([':p_diocese_id'])
								->stored_procedure_values([$diocese_id])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting diocese by id', 500, $exception);
		}
	}

	public function updateDioceseById(int $param_diocese_id, string $param_name, int $param_archbishop_id, int $param_vicar_general_id, int $param_chancellor_id, string $param_address, string $param_contact_number, string $param_email_address, int $param_user_id)
	{
		try {
			$diocese_id = $param_diocese_id ?? 0;
			$name = $param_name ?? '';
      $archbishop_id = $param_archbishop_id ?? 0;
      $vicar_general_id = $param_vicar_general_id ?? 0;
      $chancellor_id = $param_chancellor_id ?? 0;
			$address = $param_address ?? '';
      $contact_number = $param_contact_number ?? '';
      $email_address = $param_email_address ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_diocese_by_id_upd')
								->stored_procedure_params([':p_diocese_id, :p_name, :p_archbishop_id, :p_vicar_general_id, :p_chancellor_id, :p_address, :p_contact_number, :p_email_address, :result_id'])
								->stored_procedure_values([ $diocese_id, $name, $archbishop_id, $vicar_general_id, $chancellor_id, $address, $contact_number, $email_address, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated diocese:', [
                    'diocese_id' => $diocese_id,
										'name' => $name,
										'archbishop_id' => $archbishop_id,
										'vicar_general_id' => $vicar_general_id,
                    'chancellor_id' => $chancellor_id,
                    'address' => $address,
                    'contact_number' => $contact_number,
                    'email_address' => $email_address,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating diocese by id', 500, $exception);
		}
	}

	public function updateDioceseStatusById(int $param_diocese_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$diocese_id = $param_diocese_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_diocese_status_by_id_upd')
								->stored_procedure_params([':p_diocese_id, :p_statuscode, :result_id'])
								->stored_procedure_values([ $diocese_id, $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated diocese status:', [
										'diocese_id' => $diocese_id, 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating diocese status by id', 500, $exception);
		}
	}
    
}
