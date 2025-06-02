<div>
  <x-mary-header title="SystemLib :: Parishes">
      <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Parish..."  wire:model.live="search"/>
      </x-slot:middle>
      <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" @click="$wire.addParishModal = true" />
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
      {{ $this->parish_lst->links() }}
    </div>
    <br />
  
    <!-- Wrap table in responsive container -->
    <div style="overflow-x: auto; width: 100%;">
      <table class="table mb-4 table-striped w-full" style="table-layout: fixed; min-width: 600px;">
        <thead>
          <tr class="fs-14 pink h-2rem">
            <th class="text-center bg-primary text-white" width="5%">#</th>
            <th class="text-center bg-primary text-white" width="40%">Parish Name</th>
            <th class="text-center bg-primary text-white" width="30%">Address</th>
            <th class="text-center bg-primary text-white">Status</th>
            <th class="text-center bg-primary text-white">Manage</th>
          </tr>
        </thead>
        <tbody>
          @if(count($this->parish_lst) == 0)
            <tr class="fs-13 border-btm content-tr">
              <td class="text-center" colspan="5">No parish record(s) found.</td>
            </tr>
          @else
            @foreach ($this->parish_lst as $result)
              <tr class="fs-13 border-btm content-tr">
                <td class="text-center vertical-align-top">{{ $result->row_num }}</td>
                <td class="text-left vertical-align-top" style="word-break: break-word;">
                  <strong>{{ $result->parish_name }} </strong><br />
                  Parish Code : {{ $result->parish_code }}<br />
                  Parish Priest : {{ $result->parish_priest_name }}<br /><br />
                  Diocese : {{ $result->diocese_name }}<br />
                  Vicariate : {{ $result->vicariate_label }}<br />
                  Established On : {{ $result->established_year }}<br />
                  Contact No. : {{ $result->contact_number }}
                </td>
                <td class="text-left vertical-align-top" style="word-break: break-word;">
                  Primary Location : {{ $result->primary_location }}<br />
                  {{ $result->address }}
                </td>
                <td class="text-center vertical-align-top">
                  @if($result->statuscode == 1)
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-green-600 text-white" />
                  @else
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-red-600 text-white" />
                  @endif
                </td>
                <td class="text-center vertical-align-top">
                  <x-mary-button icon="o-pencil-square" 
                                  wire:click="openEditParishModal({{ $result->parish_id }})" 
                                  spinner 
                                  class="bg-green-600 text-white btn-sm align-center" />&nbsp;
                  @if($result->statuscode == 1)
                    <x-mary-button icon="o-eye-slash"
                                    wire:click="openUpdateParishStatusModal({{ $result->parish_id }},{{ $result->statuscode }})"
                                    class="bg-enabled text-white btn-sm align-center"
                                    spinner
                                    />
                  @else
                    <x-mary-button icon="o-eye"
                                    wire:click="openUpdateParishStatusModal({{ $result->parish_id }},{{ $result->statuscode }})"
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
      {{ $this->parish_lst->links() }}
    </div>

  </x-mary-card>


  <x-mary-modal wire:model="addParishModal" class="backdrop-blur">
    <x-mary-form wire:submit.prevent="save" no-separator>

      <x-mary-select
            label="Diocese"
            :options="$this->load_diocese_options"
            option-value="id"
            option-label="diocese_label"
            placeholder="Select a diocese"
            placeholder-value=""
            hint="Select one, please."
            wire:model="diocese_id"
            wire:change="dioceseChanged" />

      <x-mary-select
          label="Vicariate"
          :options="$vicariate_options"
          option-value="id"
          option-label="label"
          placeholder="Select a vicariate"
          placeholder-value=""
					hint="Select one, please."
          wire:model="vicariate_id"
      />

      <x-mary-input label="Parish Name" wire:model="name" id="name" />

      <x-mary-input label="Primary Location (e.g. Mintal)" wire:model="primary_location" id="primary_location" />

      <x-mary-input label="Address (Complete)" wire:model="address" id="address" />

      <x-mary-input label="Contact No." wire:model="contact_number" id="contact_number" />

      <div class="flex items-center gap-2">
        <x-mary-select
              label="Parish Priest"
              :options="$this->load_priest_options"
              option-value="id"
              option-label="priest_name"
              placeholder="Select a parish priest"
              placeholder-value=""
              hint="Select one, please."
              wire:model="parish_priest_id" />

        <button 
                    type="button"
                    class="btn btn-sm btn-primary" 
                    wire:click="$set('addPriestModal', true)"
                    >+ Add</button>
      </div>

       <x-mary-input label="Established Year" wire:model="established_year" id="established_year" />
   
      <x-slot:actions>
          <x-mary-button label="Cancel" @click="$wire.addParishModal = false"/>
          <x-mary-button label="Save Record" class="btn-primary" type="submit" spinner="save" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="editParishModal" class="backdrop-blur">
    <x-mary-form wire:submit.prevent="save_parish_record_changes" no-separator>

      <x-mary-input type="hidden" wire:model="parish_id" id="parish_id" />

      <x-mary-select
						label="Diocese"
						:options="$this->load_diocese_options"
						option-value="id"
						option-label="diocese_label"
						placeholder="Select a diocese"
						placeholder-value=""
						hint="Select one, please."
						wire:model="edit_diocese_id"
            wire:change="edit_dioceseChanged" />

      <x-mary-select
          label="Vicariate"
          :options="$vicariate_options"
          option-value="id"
          option-label="label"
          placeholder="Select a vicariate"
          placeholder-value=""
					hint="Select one, please."
          wire:model="edit_vicariate_id"
      />

      <x-mary-input label="Parish Name" wire:model="edit_name" id="edit_name" />

      <x-mary-input label="Primary Location (e.g. Mintal)" wire:model="edit_primary_location" id="edit_primary_location" />

      <x-mary-input label="Address (Complete)" wire:model="edit_address" id="edit_address" />

      <x-mary-input label="Contact No." wire:model="edit_contact_number" id="edit_contact_number" />

      <div class="flex items-center gap-2">
        <x-mary-select
						label="Parish Priest"
						:options="$this->load_priest_options"
						option-value="id"
						option-label="priest_name"
						placeholder="Select a parish priest"
						placeholder-value=""
						hint="Select one, please."
						wire:model="edit_parish_priest_id" />

            <button 
                    type="button"
                    class="btn btn-sm btn-primary" 
                    wire:click="$set('addPriestModal', true)"
                    >+ Add</button>
      </div>

       <x-mary-input label="Established Year" wire:model="edit_established_year" id="edit_established_year" />

      <x-slot:actions>
        <x-mary-button label="Cancel" @click="$wire.editParishModal = false"/>
        <x-mary-button label="Save Record" class="btn-primary" type="submit" spinner="save_parish_record_changes" />
      </x-slot:actions>
    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="addPriestModal" class="backdrop-blur">
    <x-mary-form wire:submit.prevent="save_new_priest" no-separator>

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
          <x-mary-button label="Save Record" class="btn-primary" type="submit" spinner="save_new_priest" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>
  

  <x-mary-modal wire:model="updateParishStatusModal" class="backdrop-blur" title="Please Confirm Action?" separator>

    <p>Are you sure want to perform this action?</p>

    <x-slot:actions>
        <x-mary-button label="Cancel" wire:click="updateParishStatusModal = false" />
        <x-mary-button label="Confirm" class="btn-primary" spinner="delete" wire:click="update_parish_status({{ $parish_id }}, {{ $statuscode }})"  />
    </x-slot:actions>

  </x-mary-modal>


</div>