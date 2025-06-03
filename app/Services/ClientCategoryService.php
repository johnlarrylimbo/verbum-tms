<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class ClientCategoryService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadClientCategoryLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_client_category_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting client category list', 500, $exception);
			}
	}

	public function loadClientCategoryLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_client_category_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting client category list by keyword', 500, $exception);
		}
	}

  public function addClientCategory(string $param_label, int $param_user_id)
	{
		try {
			$label  = $param_label ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_client_category_ins')
								->stored_procedure_params([':p_label, :result_id'])
								->stored_procedure_values([ $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new client category:', [
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding client category', 500, $exception);
		}
	}

	public function getClientCategoryById(int $param_client_category_id)
	{
		try {
			$client_category_id = $param_client_category_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_client_category_by_id_sel')
								->stored_procedure_params([':p_client_category_id'])
								->stored_procedure_values([ $client_category_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting client category by id', 500, $exception);
		}
	}

	public function updateClientCategoryById(int $param_client_category_id, string $param_label, int $param_user_id)
	{
		try {
			$client_category_id = $param_client_category_id ?? 0;
			$label = $param_label ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_client_category_by_id_upd')
								->stored_procedure_params([':p_client_category_id, :p_label, :result_id'])
								->stored_procedure_values([ $client_category_id, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated client category:', [
										'client_category_id' => $client_category_id,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating client category by id', 500, $exception);
		}
	}

	public function updateClientCategoryStatusById(int $param_client_category_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$client_category_id  = $param_client_category_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_client_category_status_by_id_upd')
								->stored_procedure_params([':p_client_category_id , :p_statuscode, :result_id'])
								->stored_procedure_values([ $client_category_id , $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated client category status:', [
										'client_category_id ' => $client_category_id , 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating client category status by id', 500, $exception);
		}
	}
    
}
