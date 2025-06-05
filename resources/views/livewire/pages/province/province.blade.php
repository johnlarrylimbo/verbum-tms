<div>
  <x-mary-header title="SystemLib :: Province">
      <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Province..."  wire:model.live="search"/>
      </x-slot:middle>
      <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" @click="$wire.addProvinceModal = true" />
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
      {{ $this->province_lst->links() }}
    </div>
    <br />
  
    <!-- Wrap table in responsive container -->
    <div style="overflow-x: auto; width: 100%;">
      <table class="table mb-4 table-striped w-full" style="table-layout: fixed; min-width: 600px;">
        <thead>
          <tr class="fs-14 pink h-2rem">
            <th class="text-center bg-primary text-white" width="5%">#</th>
            <th class="text-center bg-primary text-white" width="20%">Region</th>
            <th class="text-center bg-primary text-white" width="30%">Province Description</th>
            <th class="text-center bg-primary text-white">Status</th>
            <th class="text-center bg-primary text-white">Manage</th>
          </tr>
        </thead>
        <tbody>
          @if(count($this->province_lst) == 0)
            <tr class="fs-13 border-btm content-tr">
              <td class="text-center" colspan="5">No province record(s) found.</td>
            </tr>
          @else
            @foreach ($this->province_lst as $result)
              <tr class="fs-13 border-btm content-tr">
                <td class="align-top text-center">{{ $result->row_num }}</td>
                <td class="text-left vertical-align-top" style="word-break: break-word;">{{ $result->region_label }}</td>
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
                                  wire:click="openEditProvinceModal({{ $result->province_id }})" 
                                  spinner 
                                  class="bg-green-600 text-white btn-sm align-center" />&nbsp;
                  @if($result->statuscode == 1)
                    <x-mary-button icon="o-eye-slash"
                                    wire:click="openUpdateProvinceStatusModal({{ $result->province_id }},{{ $result->statuscode }})"
                                    class="bg-enabled text-white btn-sm align-center"
                                    spinner
                                    />
                  @else
                    <x-mary-button icon="o-eye"
                                    wire:click="openUpdateProvinceStatusModal({{ $result->province_id }},{{ $result->statuscode }})"
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
      {{ $this->province_lst->links() }}
    </div>

  </x-mary-card>


  <x-mary-modal wire:model="addProvinceModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Add Province</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_province" no-separator>

      <x-mary-input label="Description" wire:model="label" id="label" />

      <x-mary-select
						label="Island Group"
						:options="$this->load_island_group_options"
						option-value="id"
						option-label="label"
						placeholder="Select a island group"
						placeholder-value=""
						hint="Select one, please."
						wire:model="island_group_id" />

      <x-mary-select
						label="Region"
						:options="$this->load_region_options"
						option-value="id"
						option-label="abbreviation"
						placeholder="Select a region"
						placeholder-value=""
						hint="Select one, please."
						wire:model="region_id" />
   
      <x-slot:actions>
          <x-mary-button label="Cancel" @click="$wire.addProvinceModal = false"/>
          <x-mary-button label="Save Record" class="btn-primary" type="submit" spinner="save_province" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="editProvinceModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Edit Province</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_province_record_changes" no-separator>

      <x-mary-input type="hidden" wire:model="province_id" id="province_id" />

      <x-mary-input label="Description" wire:model="edit_label" id="edit_label" />

     <x-mary-select
						label="Island Group"
						:options="$this->load_island_group_options"
						option-value="id"
						option-label="label"
						placeholder="Select a island group"
						placeholder-value=""
						hint="Select one, please."
						wire:model="edit_island_group_id" />

      <x-mary-select
						label="Region"
						:options="$this->load_region_options"
						option-value="id"
						option-label="abbreviation"
						placeholder="Select a region"
						placeholder-value=""
						hint="Select one, please."
						wire:model="edit_region_id" />

      <x-slot:actions>
        <x-mary-button label="Cancel" @click="$wire.editProvinceModal = false"/>
        <x-mary-button label="Save Record" class="btn-primary" type="submit" spinner="save_province_record_changes" />
      </x-slot:actions>
    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="updateProvinceStatusModal" class="backdrop-blur" title="Please Confirm Action?" separator>

    <p>Are you sure want to perform this action?</p>

    <x-slot:actions>
        <x-mary-button label="Cancel" wire:click="updateProvinceStatusModal = false" />
        <x-mary-button label="Confirm" class="btn-primary" spinner="delete" wire:click="update_province_status({{ $province_id }}, {{ $statuscode }})"  />
    </x-slot:actions>

  </x-mary-modal>


</div>