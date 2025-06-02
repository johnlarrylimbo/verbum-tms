<div>
  <x-mary-header title="SystemLib :: Basic Ecclesial Community">
      <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Community..."  wire:model.live="search"/>
      </x-slot:middle>
      <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" @click="$wire.addBasicEcclesialCommunityModal = true" />
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


  <x-mary-modal wire:model="addBasicEcclesialCommunityModal" class="backdrop-blur">
    <x-mary-form wire:submit.prevent="save" no-separator>

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
          <x-mary-button label="Save Record" class="btn-primary" type="submit" spinner="save" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="editBasicEcclesialCommunityModal" class="backdrop-blur">
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
        <x-mary-button label="Save Record" class="btn-primary" type="submit" spinner="save_bec_record_changes" />
      </x-slot:actions>
    </x-mary-form>
  </x-mary-modal>  

  <x-mary-modal wire:model="updateBasicEcclesialCommunityStatusModal" class="backdrop-blur" title="Please Confirm Action?" separator>

    <p>Are you sure want to perform this action?</p>

    <x-slot:actions>
        <x-mary-button label="Cancel" wire:click="updateBasicEcclesialCommunityStatusModal = false" />
        <x-mary-button label="Confirm" class="btn-primary" spinner="delete" wire:click="update_bec_status({{ $bec_id }}, {{ $statuscode }})"  />
    </x-slot:actions>

  </x-mary-modal>


</div>