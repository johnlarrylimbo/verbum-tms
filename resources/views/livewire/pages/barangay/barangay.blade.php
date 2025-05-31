<div>
  <x-mary-header title="SystemLib :: Barangay">
      <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Barangay..."  wire:model.live="search"/>
      </x-slot:middle>
      <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" @click="$wire.addBarangayModal = true" />
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
      <x-mary-alert icon="s-check-circle" class="alert-danger text-white">
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
      <x-mary-alert icon="s-check-circle" class="alert-danger text-white">
        Failed to update record. Record does not exists.
      </x-mary-alert>
    </div>
  @endif

  <x-mary-card>

    <div class="my-4">
      {{ $this->barangay_lst->links() }}
    </div>
    <br />
  
    <!-- Wrap table in responsive container -->
    <div style="overflow-x: auto; width: 100%;">
      <table class="table mb-4 table-striped w-full" style="table-layout: fixed; min-width: 600px;">
        <thead>
          <tr class="fs-14 pink h-2rem">
            <th class="text-center bg-primary text-white" width="8%">#</th>
            <th class="text-center bg-primary text-white" width="20%">Description</th>
            <th class="text-center bg-primary text-white" width="20%">City / Municipality</th>
            <th class="text-center bg-primary text-white" width="20%">Province</th>
            <th class="text-center bg-primary text-white">Status</th>
            <th class="text-center bg-primary text-white">Manage</th>
          </tr>
        </thead>
        <tbody>
          @if(count($this->barangay_lst) == 0)
            <tr class="fs-13 border-btm content-tr">
              <td class="text-center" colspan="6">No barangay record(s) found.</td>
            </tr>
          @else
            @foreach ($this->barangay_lst as $result)
              <tr class="fs-13 border-btm content-tr">
                <td class="align-center vertical-align-top">{{ $result->row_num }}</td>
                <td class="align-left vertical-align-top" style="word-break: break-word;">{{ $result->label }}</td>
                <td class="align-left vertical-align-top" style="word-break: break-word;">{{ $result->city_unicipality_label }}</td>
                <td class="align-left vertical-align-top" style="word-break: break-word;">{{ $result->province_label }}</td>
                <td class="text-center vertical-align-top">
                  @if($result->statuscode == 1)
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-green-600 text-white" />
                  @else
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-red-600 text-white" />
                  @endif
                </td>
                <td class="text-center vertical-align-top">
                  <x-mary-button icon="o-pencil-square" 
                                  wire:click="openEditBarangayModal({{ $result->barangay_id }})" 
                                  spinner 
                                  class="bg-green-600 text-white btn-sm align-center" />&nbsp;
                  @if($result->statuscode == 1)
                    <x-mary-button icon="o-eye-slash"
                                    wire:click="openUpdateBarangayStatusModal({{ $result->barangay_id }},{{ $result->statuscode }})"
                                    class="bg-enabled text-white btn-sm align-center"
                                    spinner
                                    />
                  @else
                    <x-mary-button icon="o-eye"
                                    wire:click="openUpdateBarangayStatusModal({{ $result->barangay_id }},{{ $result->statuscode }})"
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
      {{ $this->barangay_lst->links() }}
    </div>

  </x-mary-card>


  <x-mary-modal wire:model="addBarangayModal" class="backdrop-blur">
    <x-mary-form wire:submit.prevent="save" no-separator>

      <x-mary-select
						label="Province"
						:options="$this->load_province_options"
						option-value="id"
						option-label="label"
						placeholder="Select a province"
						placeholder-value=""
						hint="Select one, please."
						wire:model="province_id" />

      <x-mary-select
						label="City / Municipality"
						:options="$this->load_city_municipality_options"
						option-value="id"
						option-label="label"
						placeholder="Select a city or municipality"
						placeholder-value=""
						hint="Select one, please."
						wire:model="city_municipality_id" />

      <x-mary-input label="Description" wire:model="label" id="label" />
   
      <x-slot:actions>
          <x-mary-button label="Cancel" @click="$wire.addBarangayModal = false"/>
          <x-mary-button label="Save Record" class="btn-primary" type="submit" spinner="save" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="editBarangayModal" class="backdrop-blur">
    <x-mary-form wire:submit.prevent="save_barangay_record_changes" no-separator>

      <x-mary-input type="hidden" wire:model="barangay_id" id="barangay_id" />
      
      <x-mary-select
						label="Province"
						:options="$this->load_province_options"
						option-value="id"
						option-label="label"
						placeholder="Select a province"
						placeholder-value=""
						hint="Select one, please."
						wire:model="edit_province_id" />

      <x-mary-select
						label="City / Municipality"
						:options="$this->load_city_municipality_options"
						option-value="id"
						option-label="label"
						placeholder="Select a city or municipality"
						placeholder-value=""
						hint="Select one, please."
						wire:model="edit_city_municipality_id" />

      <x-mary-input label="Description" wire:model="edit_label" id="edit_label" />

      <x-slot:actions>
        <x-mary-button label="Cancel" @click="$wire.editBarangayModal = false"/>
        <x-mary-button label="Save Record" class="btn-primary" type="submit" spinner="save_barangay_record_changes" />
      </x-slot:actions>
    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="updateBarangayStatusModal" class="backdrop-blur" title="Please Confirm Action" separator>

    <p>Are you sure want to perform this action?</p>

    <x-slot:actions>
        <x-mary-button label="Cancel" wire:click="updateBarangayStatusModal = false" />
        <x-mary-button label="Confirm" class="btn-primary" spinner="delete" wire:click="update_barangay_status({{ $barangay_id }}, {{ $statuscode }})"  />
    </x-slot:actions>

  </x-mary-modal>


</div>