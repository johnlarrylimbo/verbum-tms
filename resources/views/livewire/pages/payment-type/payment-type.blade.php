<div x-data="{ init: false }" x-init="if (!init) { init = true; $wire.payment_type_lst() }">
  <x-mary-header title="SystemLib :: Payment Type">
      <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Payment Type..."  wire:model.live="search"/>
      </x-slot:middle>
      <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" @click="$wire.addPaymentTypeModal = true" />
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
      {{ $this->payment_type_lst->links() }}
    </div>
    <br />
  
    <!-- Wrap table in responsive container -->
    <div style="overflow-x: auto; width: 100%;">
      <table class="table mb-4 table-striped w-full" style="table-layout: fixed; min-width: 600px;">
        <thead>
          <tr class="fs-14 pink h-2rem">
            <th class="text-center bg-primary text-white" width="5%">#</th>
            <th class="text-center bg-primary text-white" width="40%">Payment Type Description</th>
            <th class="text-center bg-primary text-white">Status</th>
            <th class="text-center bg-primary text-white">Manage</th>
          </tr>
        </thead>
        <tbody>
          @if(count($this->payment_type_lst) == 0)
            <tr class="fs-13 border-btm content-tr">
              <td class="text-center" colspan="4">No payment type record(s) found.</td>
            </tr>
          @else
            @foreach ($this->payment_type_lst as $result)
              <tr class="fs-13 border-btm content-tr">
                <td class="text-center vertical-align-top">{{ $result->row_num }}</td>
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
                                  wire:click="openEditPaymentTypeModal({{ $result->payment_type_id }})" 
                                  wire:target="openEditPaymentTypeModal"
                                  spinner 
                                  class="bg-green-600 text-white btn-sm align-center" />&nbsp;
                  @if($result->statuscode == 1)
                    <x-mary-button icon="o-eye-slash"
                                    wire:click="openUpdatePaymentTypeStatusModal({{ $result->payment_type_id }},{{ $result->statuscode }})"
                                    class="bg-enabled text-white btn-sm align-center"
                                    spinner
                                    />
                  @else
                    <x-mary-button icon="o-eye"
                                    wire:click="openUpdatePaymentTypeStatusModal({{ $result->payment_type_id }},{{ $result->statuscode }})"
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
      {{ $this->payment_type_lst->links() }}
    </div>

  </x-mary-card>


  <x-mary-modal wire:model="addPaymentTypeModal" class="backdrop-blur">
    <x-mary-form wire:submit.prevent="save_payment_type" no-separator>

      <x-mary-input label="Payment Type Description" wire:model="label" id="label" />
   
      <x-slot:actions>
          <x-mary-button label="Cancel" @click="$wire.addPaymentTypeModal = false"/>
          <x-mary-button 
                label="Save Record" 
                class="btn-primary" 
                type="submit" 
                spinner="save_payment_type"
                wire:target="save_payment_type" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>

  <x-mary-modal wire:model="editPaymentTypeModal" class="backdrop-blur">
    <x-mary-form wire:submit.prevent="save_payment_type_record_changes" no-separator>

      <x-mary-input type="hidden" wire:model="payment_type_id" id="payment_type_id" />

      <x-mary-input label="Payment Type Description" wire:model="edit_label" id="edit_label" />

      <x-slot:actions>
        <x-mary-button label="Cancel" @click="$wire.editPaymentTypeModal = false"/>
        <x-mary-button 
              label="Save Record" 
              class="btn-primary" 
              type="submit" 
              spinner="save_payment_type_record_changes"
              wire:target="save_payment_type_record_changes" />
      </x-slot:actions>
    </x-mary-form>
  </x-mary-modal>  

  <x-mary-modal wire:model="updatePaymentTypeStatusModal" class="backdrop-blur" title="Please Confirm Action?" separator>

    <p>Are you sure want to perform this action?</p>

    <x-slot:actions>
        <x-mary-button label="Cancel" wire:click="updatePaymentTypeStatusModal = false" />
        <x-mary-button 
              label="Confirm" 
              class="btn-primary" 
              spinner="delete" 
              wire:click="update_payment_type_status({{ $payment_type_id }}, {{ $statuscode }})"
              wire:target="update_payment_type_status"  />
    </x-slot:actions>

  </x-mary-modal>

  <!-- 
    Loader goes here 
  -->
  <x-livewire-loader target="payment_type_lst" message="Please wait while the system loads all payment type records for you..." />

  <x-livewire-loader target="save_payment_type,save_payment_type_record_changes" message="Saving... please wait..." />

  <x-livewire-loader target="openEditPaymentTypeModal" message="Please wait while the system retrieves the record for you..." />

  <x-livewire-loader target="update_payment_type_status" message="Updating record status... please wait..." />

</div>