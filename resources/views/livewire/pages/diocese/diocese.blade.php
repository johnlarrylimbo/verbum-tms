<div x-data="{ init: false }" x-init="if (!init) { init = true; $wire.diocese_lst() }">
  <x-mary-header title="SystemLib :: Diocese">
      <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Diocese..."  wire:model.live="search"/>
      </x-slot:middle>
      <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" @click="$wire.addDioceseModal = true" />
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
      {{ $this->diocese_lst->links() }}
    </div>
    <br />
  
    <!-- Wrap table in responsive container -->
    <div style="overflow-x: auto; width: 100%;">
      <table class="table mb-4 table-striped w-full" style="table-layout: fixed; min-width: 600px;">
        <thead>
          <tr class="fs-14 pink h-2rem">
            <th class="text-center bg-primary text-white" width="5%">#</th>
            <th class="text-center bg-primary text-white" width="40%">Diocese Name</th>
            <th class="text-center bg-primary text-white" width="25%">Address</th>
            <th class="text-center bg-primary text-white">Status</th>
            <th class="text-center bg-primary text-white">Manage</th>
          </tr>
        </thead>
        <tbody>
          @if(count($this->diocese_lst) == 0)
            <tr class="fs-13 border-btm content-tr">
              <td class="text-center" colspan="5">No diocese record(s) found.</td>
            </tr>
          @else
            @foreach ($this->diocese_lst as $result)
              <tr class="fs-13 border-btm content-tr">
                <td class="align-top text-center">{{ $result->row_num }}</td>
                <td class="text-left vertical-align-top" style="word-break: break-word;">
                  <strong>{{ $result->diocese_name }}</strong> <br />
                  {{ $result->diocese_code }} <br />
                  Email : {{ $result->email_address }} <br />
                  Contact No. : {{ $result->contact_number }} <br /> <br />
                  Archbishop  : {{ $result->archbishop_name }} <br />
                  Vicar General :{{ $result->vicar_general_name }} <br />
                  Chancellor :{{ $result->chancellor_name }} <br />
                </td>
                <td class="text-left vertical-align-top" style="word-break: break-word;">{{ $result->address }}</td>
                <td class="text-center vertical-align-top">
                  @if($result->statuscode == 1)
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-green-600 text-white" />
                  @else
                    <x-mary-badge value="{{ $result->statuscode_label }}" class="bg-red-600 text-white" />
                  @endif
                </td>
                <td class="text-center vertical-align-top">
                  <x-mary-button icon="o-pencil-square" 
                                  wire:click="openEditDioceseModal({{ $result->diocese_id }})" 
                                  wire:target="openEditDioceseModal"
                                  spinner 
                                  class="bg-green-600 text-white btn-sm align-center" />&nbsp;
                  @if($result->statuscode == 1)
                    <x-mary-button icon="o-eye-slash"
                                    wire:click="openUpdateDioceseStatusModal({{ $result->diocese_id }},{{ $result->statuscode }})"
                                    class="bg-enabled text-white btn-sm align-center"
                                    spinner
                                    />
                  @else
                    <x-mary-button icon="o-eye"
                                    wire:click="openUpdateDioceseStatusModal({{ $result->diocese_id }},{{ $result->statuscode }})"
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
      {{ $this->diocese_lst->links() }}
    </div>

  </x-mary-card>


  <x-mary-modal wire:model="addDioceseModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Add Diocese</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_diocese" no-separator>

      <x-mary-input label="Diocese Name" wire:model="name" id="name" />

      <x-mary-select
						label="Archbishop"
						:options="$this->load_priest_options"
						option-value="id"
						option-label="priest_name"
						placeholder="Select a diocese archbishop"
						placeholder-value=""
						hint="Select one, please."
						wire:model="archbishop_id" />

      <x-mary-select
						label="Vicar General"
						:options="$this->load_priest_options"
						option-value="id"
						option-label="priest_name"
						placeholder="Select a diocese vicar general"
						placeholder-value=""
						hint="Select one, please."
						wire:model="vicar_general_id" />

      <x-mary-select
						label="Chancellor"
						:options="$this->load_priest_options"
						option-value="id"
						option-label="priest_name"
						placeholder="Select a diocese chancellor"
						placeholder-value=""
						hint="Select one, please."
						wire:model="chancellor_id" />

      <x-mary-input label="Address" wire:model="address" id="address" />

      <x-mary-input label="Contact No." wire:model="contact_number" id="contact_number" />

      <x-mary-input label="Email Address" wire:model="email_address" id="email_address" />
   
      <x-slot:actions>
          <x-mary-button label="Cancel" @click="$wire.addDioceseModal = false"/>
          <x-mary-button 
                label="Save Record" 
                class="btn-primary" 
                type="submit" 
                spinner="save_diocese"
                wire:target="save_diocese" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="editDioceseModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Edit Diocese</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_diocese_record_changes" no-separator>

      <x-mary-input type="hidden" wire:model="diocese_id" id="diocese_id" />

      <x-mary-input label="Diocese Name" wire:model="edit_name" id="edit_name" />

      <x-mary-select
						label="Archbishop"
						:options="$this->load_priest_options"
						option-value="id"
						option-label="priest_name"
						placeholder="Select a diocese archbishop"
						placeholder-value=""
						hint="Select one, please."
						wire:model="edit_archbishop_id" />

      <x-mary-select
						label="Vicar General"
						:options="$this->load_priest_options"
						option-value="id"
						option-label="priest_name"
						placeholder="Select a diocese vicar general"
						placeholder-value=""
						hint="Select one, please."
						wire:model="edit_vicar_general_id" />

      <x-mary-select
						label="Chancellor"
						:options="$this->load_priest_options"
						option-value="id"
						option-label="priest_name"
						placeholder="Select a diocese chancellor"
						placeholder-value=""
						hint="Select one, please."
						wire:model="edit_chancellor_id" />

      <x-mary-input label="Address" wire:model="edit_address" id="edit_address" />

      <x-mary-input label="Contact No." wire:model="edit_contact_number" id="edit_contact_number" />

      <x-mary-input label="Email Address" wire:model="edit_email_address" id="edit_email_address" />

      <x-slot:actions>
        <x-mary-button label="Cancel" @click="$wire.editDioceseModal = false"/>
        <x-mary-button 
              label="Save Record" 
              class="btn-primary" 
              type="submit" 
              spinner="save_diocese_record_changes"
              wire:target="save_diocese_record_changes" />
      </x-slot:actions>
    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="updateDioceseStatusModal" class="backdrop-blur" title="Please Confirm Action?" separator>

    <p>Are you sure want to perform this action?</p>

    <x-slot:actions>
        <x-mary-button label="Cancel" wire:click="updateDioceseStatusModal = false" />
        <x-mary-button 
              label="Confirm" 
              class="btn-primary" 
              spinner="delete" 
              wire:click="update_diocese_status({{ $diocese_id }}, {{ $statuscode }})"
              wire:target="update_diocese_status"   />
    </x-slot:actions>

  </x-mary-modal>


  <!-- 
    Loader goes here 
  -->
  <x-livewire-loader target="diocese_lst" message="Please wait while the system loads all diocese records for you..." />

  <x-livewire-loader target="save_diocese,save_diocese_record_changes" message="Saving... please wait..." />

  <x-livewire-loader target="openEditDioceseModal" message="Please wait while the system retrieves the record for you..." />

  <x-livewire-loader target="update_diocese_status" message="Updating record status... please wait..." />


</div>