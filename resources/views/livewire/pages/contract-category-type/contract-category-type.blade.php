<div x-data="{ init: false }" x-init="if (!init) { init = true; $wire.contract_category_type_lst() }">
  <x-mary-header title="SystemLib :: Contract Category Type">
      <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Category Type..."  wire:model.live="search"/>
      </x-slot:middle>
      <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" @click="$wire.addContractCategoryTypeModal = true" />
      </x-slot:actions>
  </x-mary-header>


  @if ($showMessageToast)
    <div 
      x-data="{ show: true }" 
      x-show="show" 
      x-init="setTimeout(() => { show = false; @this.set('showMessageToast', false) }, 3000)" 
      x-transition 
      class="fixed top-4 right-4 z-50"
    >
      <x-mary-alert 
        :icon="$is_success ? 's-check-circle' : 'c-x-circle'" 
        :class="$is_success ? 'alert-success text-white' : 'bg-danger text-white'"
      >
        {{ $addMessage }}
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
                                  wire:target="openEditContractCategoryTypeModal"
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
              label="Contract Category"
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
              label="Contract Type"
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
          <x-mary-button 
                label="Save Record" 
                class="btn-primary" 
                type="submit" 
                spinner="save_contract_category_type"
                wire:target="save_contract_category_type" />
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
        <x-mary-button 
              label="Save Record" 
              class="btn-primary" 
              type="submit" 
              spinner="save_contract_category_type_record_changes"
              wire:target="save_contract_category_type_record_changes" />
      </x-slot:actions>
    </x-mary-form>
  </x-mary-modal>  

  <x-mary-modal wire:model="updateContractCategoryTypeStatusModal" class="backdrop-blur" title="Please Confirm Action?" separator>

    <p>Are you sure want to perform this action?</p>

    <x-slot:actions>
        <x-mary-button label="Cancel" wire:click="updateContractCategoryTypeStatusModal = false" />
        <x-mary-button 
              label="Confirm" 
              class="btn-primary" 
              spinner="delete" 
              wire:click="update_contract_category_type_status({{ $contract_category_type_id }}, {{ $statuscode }})"
              wire:target="update_contract_category_type_status"  />
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
          <x-mary-button 
                label="Save Record" 
                class="btn-primary" 
                type="submit" 
                spinner="save_contract_category"
                wire:target="save_contract_category" />
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
          <x-mary-button 
                label="Save Record" 
                class="btn-primary" 
                type="submit" 
                spinner="save_contract_type"
                wire:target="save_contract_type" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>


  <!-- 
    Loader goes here 
  -->
  <x-livewire-loader target="contract_category_type_lst" message="Please wait while the system loads all contract category type records for you..." />

  <x-livewire-loader target="save_contract_category_type,save_contract_category,save_contract_type,save_contract_category_type_record_changes,save_contract_category_type_record_changes" message="Saving... please wait..." />

  <x-livewire-loader target="openEditContractCategoryTypeModal" message="Please wait while the system retrieves the record for you..." />

  <x-livewire-loader target="update_contract_category_type_status" message="Updating record status... please wait..." />

</div>