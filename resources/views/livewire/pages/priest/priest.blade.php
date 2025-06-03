<div>
  <x-mary-header title="SystemLib :: Priest Directory">
      <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Priest..."  wire:model.live="search"/>
      </x-slot:middle>
      <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" @click="$wire.addPriestModal = true" />
      </x-slot:actions>
  </x-mary-header>

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
      <x-mary-alert icon="s-check-circle" class="alert-danger text-white">
        Failed to add new record. Record already exists in our database.
      </x-mary-alert>
    </div>
  @endif

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

  @if ($showErrorMessage)
    <div 
    x-data="{ show: true }" 
    x-show="show" 
    x-init="setTimeout(() => { show = false; @this.set('showErrorMessage', false) }, 3000)"
    x-transition
    class="fixed top-4 right-4 z-50">
      <x-mary-alert icon="s-check-circle" class="alert-danger text-white">
        Failed to update record. Record does not exists.
      </x-mary-alert>
    </div>
  @endif

  <x-mary-card>

    <div class="my-4">
      {{ $this->priest_lst->links() }}
    </div>
    <br />
  
    <!-- Wrap table in responsive container -->
    <div style="overflow-x: auto; width: 100%;">
      <table class="table mb-4 table-striped w-full" style="table-layout: fixed; min-width: 600px;">
        <thead>
          <tr class="fs-14 pink h-2rem">
            <th class="text-center bg-primary text-white" width="8%">#</th>
            <th class="text-center bg-primary text-white" width="30%">Priest Name</th>
            <th class="text-center bg-primary text-white" width="15%">Congregation</th>
            <th class="text-center bg-primary text-white">Status</th>
            <th class="text-center bg-primary text-white">Manage</th>
          </tr>
        </thead>
        <tbody>
          @if(count($this->priest_lst) == 0)
            <tr class="fs-13 border-btm content-tr">
              <td class="text-center" colspan="5">No priest record(s) found.</td>
            </tr>
          @else
            @foreach ($this->priest_lst as $result)
              <tr class="fs-13 border-btm content-tr">
                <td class="text-center vertical-align-top">{{ $result->row_num }}</td>
                <td class="text-left vertical-align-top" style="word-break: break-word;">{{ $result->priest_name }}</td>
                <td class="text-center vertical-align-top" style="word-break: break-word;">{{ $result->congregation_abbreviation }}</td>
                <td class="text-center vertical-align-top">
                  @if($result->statuscode == 1)
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-green-600 text-white" />
                  @else
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-red-600 text-white" />
                  @endif
                </td>
                <td class="text-center vertical-align-top">
                  <x-mary-button icon="o-pencil-square" 
                                  wire:click="openEditPriestModal({{ $result->priest_id }})" 
                                  spinner 
                                  class="bg-green-600 text-white btn-sm align-center" />&nbsp;
                  @if($result->statuscode == 1)
                    <x-mary-button icon="o-eye-slash"
                                    wire:click="openUpdatePriestStatusModal({{ $result->priest_id }},{{ $result->statuscode }})"
                                    class="bg-enabled text-white btn-sm align-center"
                                    spinner
                                    />
                  @else
                    <x-mary-button icon="o-eye"
                                    wire:click="openUpdatePriestStatusModal({{ $result->priest_id }},{{ $result->statuscode }})"
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
      {{ $this->priest_lst->links() }}
    </div>

  </x-mary-card>


  <x-mary-modal wire:model="addPriestModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Add Priest</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save" no-separator>

      <x-mary-input label="First Name" wire:model="firstname" id="firstname" />

      <x-mary-input label="Middle Name" wire:model="middlename" id="middlename" />

      <x-mary-input label="Last Name" wire:model="lastname" id="lastname" />

      <x-mary-select
						label="Priest Congregation"
						:options="$this->load_congregation_options"
						option-value="id"
						option-label="congregation_label"
						placeholder="Select a congregation"
						placeholder-value=""
						hint="Select one, please."
						wire:model="congregation_id" />
   
      <x-slot:actions>
          <x-mary-button label="Cancel" @click="$wire.addPriestModal = false"/>
          <x-mary-button label="Save Record" class="btn-primary" type="submit" spinner="save" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="editPriestModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Edit Priest</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_priest_record_changes" no-separator>

      <x-mary-input type="hidden" wire:model="priest_id" id="priest_id" />
      
     <x-mary-input label="First Name" wire:model="edit_firstname" id="edit_firstname" />

      <x-mary-input label="Middle Name" wire:model="edit_middlename" id="edit_middlename" />

      <x-mary-input label="Last Name" wire:model="edit_lastname" id="edit_lastname" />

      <x-mary-select
						label="Priest Congregation"
						:options="$this->load_congregation_options"
						option-value="id"
						option-label="congregation_label"
						placeholder="Select a congregation"
						placeholder-value=""
						hint="Select one, please."
						wire:model="edit_congregation_id" />

      <x-slot:actions>
        <x-mary-button label="Cancel" @click="$wire.editPriestModal = false"/>
        <x-mary-button label="Save Record" class="btn-primary" type="submit" spinner="save_priest_record_changes" />
      </x-slot:actions>
    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="updatePriestStatusModal" class="backdrop-blur" title="Please Confirm Action?" separator>

    <p>Are you sure want to perform this action?</p>

    <x-slot:actions>
        <x-mary-button label="Cancel" wire:click="updatePriestStatusModal = false" />
        <x-mary-button label="Confirm" class="btn-primary" spinner="delete" wire:click="update_priest_status({{ $priest_id }}, {{ $statuscode }})"  />
    </x-slot:actions>

  </x-mary-modal>


</div>