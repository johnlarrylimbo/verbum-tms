<div x-data="{ init: false }" x-init="if (!init) { init = true; $wire.bec_lst() }">
  <x-mary-header title="SystemLib :: Basic Ecclesial Community">
      <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Community..."  wire:model.live="search"/>
      </x-slot:middle>
      <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" @click="$wire.addBasicEcclesialCommunityModal = true" />
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
      {{ $this->bec_lst->links() }}
    </div>
    <br />
  
    <!-- Wrap table in responsive container -->
    <div style="overflow-x: auto; width: 100%;">
      <table class="table mb-4 table-striped w-full" style="table-layout: fixed; min-width: 600px;">
        <thead>
          <tr class="fs-14 pink h-2rem">
            <th class="text-center bg-primary text-white" width="5%">#</th>
            <th class="text-center bg-primary text-white" width="40%">Basic Ecclesial Community Name</th>
            <th class="text-center bg-primary text-white" width="30%">Address</th>
            <th class="text-center bg-primary text-white">Status</th>
            <th class="text-center bg-primary text-white">Manage</th>
          </tr>
        </thead>
        <tbody>
          @if(count($this->bec_lst) == 0)
            <tr class="fs-13 border-btm content-tr">
              <td class="text-center" colspan="5">No basic ecclesial community record(s) found.</td>
            </tr>
          @else
            @foreach ($this->bec_lst as $result)
              <tr class="fs-13 border-btm content-tr">
                <td class="text-center vertical-align-top">{{ $result->row_num }}</td>
                <td class="text-left vertical-align-top" style="word-break: break-word;">
                  <strong>{{ $result->name }} </strong><br />
                  BEC Code : {{ $result->bec_code }}<br /><br />
                  Parish : {{ $result->parish_name }}
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
                                  wire:click="openEditBasicEcclesialCommunityModal({{ $result->bec_id }})" 
                                  wire:target="openEditBasicEcclesialCommunityModal"
                                  spinner 
                                  class="bg-green-600 text-white btn-sm align-center" />&nbsp;
                  @if($result->statuscode == 1)
                    <x-mary-button icon="o-eye-slash"
                                    wire:click="openUpdateBasicEcclesialCommunityStatusModal({{ $result->bec_id }},{{ $result->statuscode }})"
                                    class="bg-enabled text-white btn-sm align-center"
                                    spinner
                                    />
                  @else
                    <x-mary-button icon="o-eye"
                                    wire:click="openUpdateBasicEcclesialCommunityStatusModal({{ $result->bec_id }},{{ $result->statuscode }})"
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
      {{ $this->bec_lst->links() }}
    </div>

  </x-mary-card>


  <x-mary-modal wire:model="addBasicEcclesialCommunityModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Add Basic Ecclesial Community</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_bec" no-separator>

      <x-mary-select
            label="Affiliated Parish"
            :options="$this->load_parish_options"
            option-value="id"
            option-label="parish_name"
            placeholder="Select a parish"
            placeholder-value=""
            hint="Select one, please."
            wire:model="parish_id" />

      <x-mary-input label="GKK Name" wire:model="name" id="name" />

      <x-mary-input label="Address" wire:model="address" id="address" />
   
      <x-slot:actions>
          <x-mary-button label="Cancel" @click="$wire.addBasicEcclesialCommunityModal = false"/>
          <x-mary-button 
              label="Save Record" 
              class="btn-primary" 
              type="submit" 
              spinner="save_bec"
              wire:target="save_bec" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="editBasicEcclesialCommunityModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Edit Basic Ecclesial Community</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_bec_record_changes" no-separator>

      <x-mary-input type="hidden" wire:model="bec_id" id="bec_id" />

      <x-mary-select
            label="Affiliated Parish"
            :options="$this->load_parish_options"
            option-value="id"
            option-label="parish_name"
            placeholder="Select a parish"
            placeholder-value=""
            hint="Select one, please."
            wire:model="edit_parish_id" />

      <x-mary-input label="GKK Name" wire:model="edit_name" id="edit_name" />

      <x-mary-input label="Address" wire:model="edit_address" id="edit_address" />

      <x-slot:actions>
        <x-mary-button label="Cancel" @click="$wire.editBasicEcclesialCommunityModal = false"/>
        <x-mary-button 
              label="Save Record" 
              class="btn-primary" 
              type="submit" 
              spinner="save_bec_record_changes"
              wire:target="save_bec_record_changes" />
      </x-slot:actions>
    </x-mary-form>
  </x-mary-modal>  

  <x-mary-modal wire:model="updateBasicEcclesialCommunityStatusModal" class="backdrop-blur" title="Please Confirm Action?" separator>

    <p>Are you sure want to perform this action?</p>

    <x-slot:actions>
        <x-mary-button label="Cancel" wire:click="updateBasicEcclesialCommunityStatusModal = false" />
        <x-mary-button 
              label="Confirm" 
              class="btn-primary" 
              spinner="delete" 
              wire:click="update_bec_status({{ $bec_id }}, {{ $statuscode }})"
              wire:target="update_bec_status" />
    </x-slot:actions>

  </x-mary-modal>


  <!-- 
    Loader goes here 
  -->
  <x-livewire-loader target="bec_lst" message="Please wait while the system loads all basic ecclesial community records for you..." />

  <x-livewire-loader target="save_bec,save_bec_record_changes" message="Saving... please wait..." />

  <x-livewire-loader target="openEditBasicEcclesialCommunityModal" message="Please wait while the system retrieves the record for you..." />

  <x-livewire-loader target="update_bec_status" message="Updating record status... please wait..." />

</div>