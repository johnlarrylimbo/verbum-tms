<div>
  <x-mary-header title="SystemLib :: Contract Category Type">
      <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Category Type..."  wire:model.live="search"/>
      </x-slot:middle>
      <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" @click="$wire.addContractCategoryTypeModal = true" />
      </x-slot:actions>
  </x-mary-header>


  @if ($showSuccessMessage)
    <div 
    x-data="{ show: true }" 
    x-show="show" 
    x-init="setTimeout(() => { show = false; @this.set('showSuccessMessage', false) }, 3000)"
    x-transition
    class="fixed top-4 right-4 z-50">
      <x-mary-alert icon="s-check-circle" class="alert-success text-white">
          Record updated successfully!
      </x-mary-alert>
    </div>
  @endif

  @if ($showAddSuccessMessage)
    <div 
    x-data="{ show: true }" 
    x-show="show" 
    x-init="setTimeout(() => { show = false; @this.set('showAddSuccessMessage', false) }, 3000)"
    x-transition
    class="fixed top-4 right-4 z-50">
      <x-mary-alert icon="s-check-circle" class="alert-success text-white">
          Record added successfully!
      </x-mary-alert>
    </div>
  @endif

  @if ($showAddErrorMessage)
    <div 
    x-data="{ show: true }" 
    x-show="show" 
    x-init="setTimeout(() => { show = false; @this.set('showAddErrorMessage', false) }, 3000)"
    x-transition
    class="fixed top-4 right-4 z-50">
      <x-mary-alert icon="c-x-circle" class="bg-danger text-white">
        Failed to add new record. Record already exists in our database.
      </x-mary-alert>
    </div>
  @endif

  @if ($showErrorMessage)
    <div 
    x-data="{ show: true }" 
    x-show="show" 
    x-init="setTimeout(() => { show = false; @this.set('showErrorMessage', false) }, 3000)"
    x-transition
    class="fixed top-4 right-4 z-50">
      <x-mary-alert icon="c-x-circle" class="bg-danger text-white">
        Failed to update record. Record does not exists.
      </x-mary-alert>
    </div>
  @endif

  <x-mary-card>

    <div class="my-4">
      {{ $this->contract_category_type_lst->links() }}
    </div>
    <br />
  
    <!-- Wrap table in responsive container -->
    <div style="overflow-x: auto; width: 100%;">
      <table class="table mb-4 table-striped w-full" style="table-layout: fixed; min-width: 600px;">
        <thead>
          <tr class="fs-14 pink h-2rem">
            <th class="text-center bg-primary text-white" width="5%">#</th>
            <th class="text-center bg-primary text-white" width="17%">Contract Category</th>
            <th class="text-center bg-primary text-white" width="17%">Contract Type</th>
            <th class="text-center bg-primary text-white" width="30%">Contract Detail Description</th>
            <th class="text-center bg-primary text-white">Status</th>
            <th class="text-center bg-primary text-white">Manage</th>
          </tr>
        </thead>
        <tbody>
          @if(count($this->contract_category_type_lst) == 0)
            <tr class="fs-13 border-btm content-tr">
              <td class="text-center" colspan="6">No contract category type record(s) found.</td>
            </tr>
          @else
            @foreach ($this->contract_category_type_lst as $result)
              <tr class="fs-13 border-btm content-tr">
                <td class="text-center vertical-align-top">{{ $result->row_num }}</td>
                <td class="text-left vertical-align-top" style="word-break: break-word;">{{ $result->contract_categories_label }}</td>
                <td class="text-left vertical-align-top" style="word-break: break-word;">{{ $result->contract_type_label }}</td>
                <td class="text-left vertical-align-top" style="word-break: break-word;">{{ $result->label }}</td>
                <td class="text-center vertical-align-top">
                  @if($result->statuscode == 1)
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-green-600 text-white" />
                  @else
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-red-600 text-white" />
                  @endif
                </td>
                <td class="text-center vertical-align-top">
                  <x-mary-button icon="o-pencil-square" 
                                  wire:click="openEditContractCategoryTypeModal({{ $result->contract_category_type_id }})" 
                                  spinner 
                                  class="bg-green-600 text-white btn-sm align-center" />&nbsp;
                  @if($result->statuscode == 1)
                    <x-mary-button icon="o-eye-slash"
                                    wire:click="openUpdateContractCategoryTypeStatusModal({{ $result->contract_category_type_id }},{{ $result->statuscode }})"
                                    class="bg-enabled text-white btn-sm align-center"
                                    spinner
                                    />
                  @else
                    <x-mary-button icon="o-eye"
                                    wire:click="openUpdateContractCategoryTypeStatusModal({{ $result->contract_category_type_id }},{{ $result->statuscode }})"
                                    class="bg-disabled text-white btn-sm align-center"
                                    spinner
                                    />
                  @endif
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>

    <div class="my-4">
      {{ $this->contract_category_type_lst->links() }}
    </div>

  </x-mary-card>


  <x-mary-modal wire:model="addContractCategoryTypeModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Add Contract Category Type</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_contract_category_type" no-separator>

      <div class="flex items-center gap-2">
        <x-mary-select
              label="Diocese"
              :options="$this->load_contract_category_options"
              option-value="id"
              option-label="label"
              placeholder="Select a contract category"
              placeholder-value=""
              hint="Select one, please."
              wire:model="contract_category_id" />

        <button 
              type="button"
              class="btn btn-sm btn-primary" 
              wire:click="$set('addContractCategoryModal', true)"
              > + </button>
      </div>

      <div class="flex items-center gap-2">
        <x-mary-select
              label="Diocese"
              :options="$this->load_contract_type_options"
              option-value="id"
              option-label="label"
              placeholder="Select a contract type"
              placeholder-value=""
              hint="Select one, please."
              wire:model="contract_type_id" />

        <button 
              type="button"
              class="btn btn-sm btn-primary" 
              wire:click="$set('addContractTypeModal', true)"
              > + </button>
      </div>

      <x-mary-input label="Description" wire:model="label" id="label" />
   
      <x-slot:actions>
          <x-mary-button label="Cancel" @click="$wire.addContractCategoryTypeModal = false"/>
          <x-mary-button label="Save Record" class="btn-primary" type="submit" spinner="save_contract_category_type" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="editContractCategoryTypeModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Edit Contract Type</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_contract_category_type_record_changes" no-separator>

      <x-mary-input type="hidden" wire:model="contract_category_type_id" id="contract_category_type_id" />

      <div class="flex items-center gap-2">
        <x-mary-select
              label="Diocese"
              :options="$this->load_contract_category_options"
              option-value="id"
              option-label="label"
              placeholder="Select a contract category"
              placeholder-value=""
              hint="Select one, please."
              wire:model="edit_contract_category_id" />

        <button 
              type="button"
              class="btn btn-sm btn-primary" 
              wire:click="$set('addContractCategoryModal', true)"
              > + </button>
      </div>

      <div class="flex items-center gap-2">
        <x-mary-select
              label="Diocese"
              :options="$this->load_contract_type_options"
              option-value="id"
              option-label="label"
              placeholder="Select a contract type"
              placeholder-value=""
              hint="Select one, please."
              wire:model="edit_contract_type_id" />

        <button 
              type="button"
              class="btn btn-sm btn-primary" 
              wire:click="$set('addContractTypeModal', true)"
              > + </button>
      </div>

      <x-mary-input label="Description" wire:model="edit_label" id="edit_label" />

      <x-slot:actions>
        <x-mary-button label="Cancel" @click="$wire.editContractCategoryTypeModal = false"/>
        <x-mary-button label="Save Record" class="btn-primary" type="submit" spinner="save_contract_category_type_record_changes" />
      </x-slot:actions>
    </x-mary-form>
  </x-mary-modal>  

  <x-mary-modal wire:model="updateContractCategoryTypeStatusModal" class="backdrop-blur" title="Please Confirm Action?" separator>

    <p>Are you sure want to perform this action?</p>

    <x-slot:actions>
        <x-mary-button label="Cancel" wire:click="updateContractCategoryTypeStatusModal = false" />
        <x-mary-button label="Confirm" class="btn-primary" spinner="delete" wire:click="update_contract_category_type_status({{ $contract_category_type_id }}, {{ $statuscode }})"  />
    </x-slot:actions>

  </x-mary-modal>

  <x-mary-modal wire:model="addContractCategoryModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Add Contract Category</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_contract_category" no-separator>

      <x-mary-input label="Abbreviation" wire:model="contract_category_abbreviation" id="contract_category_abbreviation" />

      <x-mary-input label="Description" wire:model="contract_category_label" id="contract_category_label" />
   
      <x-slot:actions>
          <x-mary-button label="Cancel" @click="$wire.addContractCategoryModal = false"/>
          <x-mary-button label="Save Record" class="btn-primary" type="submit" spinner="save_contract_category" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="addContractTypeModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Add Contract Type</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_contract_type" no-separator>

      <x-mary-input label="Description" wire:model="contract_type_label" id="contract_type_label" />
   
      <x-slot:actions>
          <x-mary-button label="Cancel" @click="$wire.addContractTypeModal = false"/>
          <x-mary-button label="Save Record" class="btn-primary" type="submit" spinner="save_contract_type" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>


</div>