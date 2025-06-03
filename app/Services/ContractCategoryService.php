<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class ContractCategoryService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadContractCategoryLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_contract_category_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting contract category list', 500, $exception);
			}
	}

	public function loadContractCategoryLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_contract_category_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting contract category list by keyword', 500, $exception);
		}
	}

  public function addContractCategory(string $param_abbreviation, string $param_label, int $param_user_id)
	{
		try {
      $abbreviation  = $param_abbreviation ?? '';
			$label  = $param_label ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_contract_category_ins')
								->stored_procedure_params([':p_abbreviation, :p_label, :result_id'])
								->stored_procedure_values([ $abbreviation, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new contract category:', [
                    'abbreviation' => $abbreviation,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding contract category', 500, $exception);
		}
	}

	public function getContractCategoryById(int $param_contract_category_id)
	{
		try {
			$contract_category_id = $param_contract_category_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_contract_category_by_id_sel')
								->stored_procedure_params([':p_contract_category_id'])
								->stored_procedure_values([ $contract_category_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting contract category by id', 500, $exception);
		}
	}

	public function updateContractCategoryById(int $param_contract_category_id, string $param_abbreviation, string $param_label, int $param_user_id)
	{
		try {
			$contract_category_id = $param_contract_category_id ?? 0;
      $abbreviation = $param_abbreviation ?? '';
			$label = $param_label ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_contract_category_by_id_upd')
								->stored_procedure_params([':p_contract_category_id, :p_abbreviation, :p_label, :result_id'])
								->stored_procedure_values([ $contract_category_id, $abbreviation, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated contract category:', [
										'contract_category_id' => $contract_category_id,
                    'abbreviation' => $abbreviation,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating contract category by id', 500, $exception);
		}
	}

	public function updateContractCategoryStatusById(int $param_contract_category_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$contract_category_id  = $param_contract_category_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_contract_category_status_by_id_upd')
								->stored_procedure_params([':p_contract_category_id, :p_statuscode, :result_id'])
								->stored_procedure_values([ $contract_category_id , $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated contract category status:', [
										'contract_category_id ' => $contract_category_id , 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating contract category status by id', 500, $exception);
		}
	}
    
}
