<?php

namespace App\Services;

use Exception;

use MagsLabs\LaravelStoredProc\StoredProcedure as SP;

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
					throw new Exception('Error getting clearance area options', 500, $exception);
			}
    }

    
}
