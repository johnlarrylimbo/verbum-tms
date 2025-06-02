<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class ParishService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadParishLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_parish_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting parish list', 500, $exception);
			}
	}

	public function loadParishLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_parish_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting parish list by keyword', 500, $exception);
		}
	}

  public function addParish(int $param_diocese_id, int $param_vicariate_id, string $param_name, string $param_address, string $param_contact_number, int $param_parish_priest_id, string $param_established_year, int $param_user_id)
	{
		try {
      $diocese_id = $param_diocese_id ?? 0;
			$vicariate_id = $param_vicariate_id ?? 0;
			$name  = $param_name ?? '';
			$address  = $param_address ?? '';
			$contact_number  = $param_contact_number ?? '';
			$parish_priest_id = $param_parish_priest_id ?? 0;
			$established_year  = $param_established_year ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_parish_ins')
								->stored_procedure_params([':p_diocese_id, :p_vicariate_id, :p_name, :p_address, :p_contact_number, :p_parish_priest_id, :p_established_year, :result_id'])
								->stored_procedure_values([ $diocese_id, $vicariate_id, $name, $address, $contact_number, $parish_priest_id, $established_year, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new parish:', [
                    'diocese_id' => $diocese_id,
										'vicariate_id' => $vicariate_id,
										'name' => $name,
										'address' => $address,
										'contact_number' => $contact_number,
										'parish_priest_id' => $parish_priest_id,
										'established_year' => $established_year,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding parish', 500, $exception);
		}
	}

	public function getParishById(int $param_parish_id)
	{
		try {
			$parish_id = $param_parish_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_parish_by_id_sel')
								->stored_procedure_params([':p_parish_id'])
								->stored_procedure_values([ $parish_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting parish by id', 500, $exception);
		}
	}

	public function updateParishById(int $param_parish_id, int $param_diocese_id, int $param_vicariate_id, string $param_name, string $param_address, string $param_contact_number, int $param_parish_priest_id, string $param_established_year, int $param_user_id)
	{
		try {
			$parish_id = $param_parish_id ?? 0;
			$diocese_id = $param_diocese_id ?? 0;
			$vicariate_id = $param_vicariate_id ?? 0;
			$name = $param_name ?? '';
			$address = $param_address ?? '';
			$contact_number = $param_contact_number ?? '';
      $parish_priest_id = $param_parish_priest_id ?? 0;
			$established_year = $param_established_year ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_parish_by_id_upd')
								->stored_procedure_params([':p_parish_id, :p_diocese_id, :p_vicariate_id, :p_name, :p_address, :p_contact_number, :p_parish_priest_id, :p_established_year, :result_id'])
								->stored_procedure_values([ $parish_id, $diocese_id, $vicariate_id, $name, $address, $contact_number, $parish_priest_id, $established_year, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated parish:', [
										'parish_id' => $parish_id,
                    'diocese_id' => $diocese_id,
										'vicariate_id' => $vicariate_id,
										'name' => $name,
										'address' => $address,
										'contact_number' => $contact_number,
										'parish_priest_id' => $parish_priest_id,
										'established_year' => $established_year,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating parish by id', 500, $exception);
		}
	}

	public function updateParishStatusById(int $param_parish_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$parish_id = $param_parish_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_parish_status_by_id_upd')
								->stored_procedure_params([':p_parish_id, :p_statuscode, :result_id'])
								->stored_procedure_values([ $parish_id, $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated parish status:', [
										'parish_id' => $parish_id, 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating parish status by id', 500, $exception);
		}
	}
    
}
