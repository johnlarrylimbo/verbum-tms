<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;
use Illuminate\Support\Facades\Log;

class PaymentTypeService extends Service
{
	protected SP $sp;

	public function __construct(SP $sp)
	{
			$this->sp = $sp;
	}
	//instantiate brand model class

	public function loadPaymentTypeLst()
	{
			try {
					$result = $this->sp
							->stored_procedure('pr_datims_payment_type_lst')
							->execute();

					return $result->stored_procedure_result();
			} catch (Exception $exception) {
					throw new Exception('Error getting payment type list', 500, $exception);
			}
	}

	public function loadPaymentTypeLstByKeyword(string $search_query)
	{
		try {
			$search = $search_query ?? '';       
				$result = $this->sp
									->stored_procedure('pr_datims_payment_type_lst_by_keyword')
									->stored_procedure_params([':p_keyword'])
									->stored_procedure_values([$search])
									->execute();

				return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting payment type list by keyword', 500, $exception);
		}
	}

  public function addPaymentType(string $param_label, int $param_user_id)
	{
		try {
			$label  = $param_label ?? '';
			$user_id = $param_user_id ?? 0;

			$result = $this->sp
								->stored_procedure('pr_datims_payment_type_ins')
								->stored_procedure_params([':p_label, :result_id'])
								->stored_procedure_values([ $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Added new payment type:', [
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error adding payment type', 500, $exception);
		}
	}

	public function getPaymentTypeById(int $param_payment_type_id)
	{
		try {
			$payment_type_id = $param_payment_type_id ?? 0;
			$result = $this->sp
								->stored_procedure('pr_datims_payment_type_by_id_sel')
								->stored_procedure_params([':p_payment_type_id'])
								->stored_procedure_values([ $payment_type_id ])
								->execute();

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error getting payment type by id', 500, $exception);
		}
	}

	public function updatePaymentTypeById(int $param_payment_type_id, string $param_label, int $param_user_id)
	{
		try {
			$payment_type_id = $param_payment_type_id ?? 0;
			$label = $param_label ?? '';
			$user_id = $param_user_id ?? 0;
			
			$result = $this->sp
								->stored_procedure('pr_datims_payment_type_by_id_upd')
								->stored_procedure_params([':p_payment_type_id, :p_label, :result_id'])
								->stored_procedure_values([ $payment_type_id, $label, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated payment type:', [
										'payment_type_id' => $payment_type_id,
                    'label' => $label,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating payment type by id', 500, $exception);
		}
	}

	public function updatePaymentTypeStatusById(int $param_payment_type_id, int $param_statuscode, int $param_user_id)
	{
		try {
			$payment_type_id  = $param_payment_type_id ?? 0;
			$statuscode = $param_statuscode ?? 0;
			$user_id = $param_user_id ?? 0;

			if($statuscode == 0){
				$updated_to = 1;
			}else{
				$updated_to = 0;
			}

			$result = $this->sp
								->stored_procedure('pr_datims_payment_type_status_by_id_upd')
								->stored_procedure_params([':p_payment_type_id , :p_statuscode, :result_id'])
								->stored_procedure_values([ $payment_type_id , $statuscode, 0 ])
								->execute();

			Log::channel('transaction_audit_trail')->info('Updated bec status:', [
										'payment_type_id ' => $payment_type_id , 
										'from_status' => $statuscode,
										'to_status' => $updated_to,
										'updated_by' => $user_id]);

			return $result->stored_procedure_result();
		} catch (Exception $exception) {
				throw new Exception('Error updating payment type status by id', 500, $exception);
		}
	}
    
}
