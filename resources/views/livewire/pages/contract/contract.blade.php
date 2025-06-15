<div x-data="{ init: false }" x-init="if (!init) { init = true; $wire.contract_lst() }">
  <x-mary-header title="::.. Contract Management">
      <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-magnifying-glass" placeholder="Search Contract..."  wire:model.live="search"/>
      </x-slot:middle>
      <x-slot:actions>
          <x-mary-button label="Create" icon="m-folder-plus" class="btn-primary" wire:click="openCreateContractWindow()" />
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
      {{ $this->contract_lst->links() }}
    </div>
    <br />
  
    <!-- Wrap table in responsive container -->
    <div style="overflow-x: auto; width: 100%;">
      <table class="table mb-4 table-striped w-full" style="table-layout: fixed; min-width: 600px;">
        <thead>
          <tr class="fs-14 pink h-2rem">
            <th class="text-center bg-primary text-white" width="5%">#</th>
            {{-- <th class="text-center bg-primary text-white" width="20%">Contract #</th> --}}
            <th class="text-center bg-primary text-white" width="40%">Client Name</th>
            <th class="text-center bg-primary text-white" width="30%">Contract Details</th>
            <th class="text-center bg-primary text-white">Status</th>
            <th class="text-center bg-primary text-white">Manage</th>
          </tr>
        </thead>
        <tbody>
          @if(count($this->contract_lst) == 0)
            <tr class="fs-13 border-btm content-tr">
              <td class="text-center" colspan="5">No contract record(s) found.</td>
            </tr>
          @else
            @foreach ($this->contract_lst as $result)
              <tr class="fs-13 border-btm content-tr">
                <td class="align-top text-center">
                  @if($result->is_paid == 0)
                   <x-mary-button label=">>" class="bg-green-600 text-white btn-sm align-center" wire:click="openContractPaymentWindow({{ $result->contract_id }})" />
                  @endif
                </td>
                {{-- <td class="text-center vertical-align-top" style="word-break: break-word;">{{ $result->contract_account_no }}</td> --}}
                <td class="text-left vertical-align-top" style="word-break: break-word;">
                  <Strong>{{ $result->client_name }}</Strong> <br/>
                  <span class="inline-flex items-center gap-1 text-gray">
                    <x-mary-icon name="o-qr-code" />
                    {{ $result->contract_account_no }}
                  </span>
                  @if($result->is_paid == 1)
                    <br/><br/>
                    <x-mary-badge value="Contract Paid" class="bg-green-600 text-white" /><br/><br/>
                    @if($result->excess > 0) <span class="excess">Excess Payment  : {{ $result->excess }}</span> @endif 
                  @endif
                </td>
                <td class="text-left vertical-align-top" style="word-break: break-word;">
                  Contract Category : {{ $result->contract_category_label }} <br />
                  Contract Type     : {{ $result->contract_type_label }} <br />
                  Contract Detail   : {{ $result->contract_category_type_detail_label }} <br />
                  Contact Person    : {{ $result->contact_person }} <br /><br />
                  {{-- Contract Duration : {{ $result->contract_duration }} <br /><br /> --}}
                  Remarks           : {{ $result->remarks }}
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
                                  wire:click="openEditContractModal({{ $result->contract_id }})" 
                                  wire:target="openEditContractModal"
                                  spinner 
                                  class="bg-green-600 text-white btn-sm align-center" /><br/>
                  {{-- <x-mary-button icon="o-magnifying-glass-circle" 
                                  wire:click="openViewClientProfileModal({{ $result->contract_id }})" 
                                  wire:target="openViewClientProfileModal"
                                  spinner 
                                  class="bg-danger text-white btn-sm align-center" /><br/> --}}
                  <x-mary-button 
                      icon="o-magnifying-glass-circle"
                      onclick="window.open('{{ url('/print-payment-summary-by-id/' . $result->contract_id) }}', '_blank')"
                      class="bg-danger text-white btn-sm align-center"
                      spinner
                  /><br/>
                  <x-mary-button 
                      icon="m-printer"
                      onclick="window.open('{{ url('/print-contract-by-id/' . $result->contract_id) }}', '_blank')"
                      class="bg-primary text-white btn-sm align-center"
                      spinner
                  /><br/>
                  @if($result->statuscode == 1)
                    <x-mary-button icon="o-eye-slash"
                                    wire:click="openUpdateContractStatusModal({{ $result->contract_id }},{{ $result->statuscode }})"
                                    class="bg-enabled text-white btn-sm align-center"
                                    spinner
                                    />
                  @else
                    <x-mary-button icon="o-eye"
                                    wire:click="openUpdateContractStatusModal({{ $result->contract_id }},{{ $result->statuscode }})"
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
      {{ $this->contract_lst->links() }}
    </div>

  </x-mary-card>


  <x-mary-modal wire:model="addContractModal" class="backdrop-blur custom-modal top-modal">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div">
        <h2 class="text-lg font-semibold text-gray-800">Add New Contract</h2>
    </div>

    <!-- Modal Form -->
    <x-mary-form wire:submit.prevent="save_contract" no-separator>

      <div>
          <!-- Contract No Input -->
          <x-mary-input label="Contract No." wire:model="contract_no_disabled" id="contract_no_disabled" disabled="disabled"/>
          <x-mary-input type="hidden" wire:model="contract_no" id="contract_no" />

          <!-- Generate Button -->
          <x-mary-button wire:click="generateContractNo" label="Generate Contract No" icon="c-hashtag" class="mt-2" />
      </div>

      {{-- <x-mary-input label="Citizenship Description" wire:model="label" id="label" />

      <x-mary-input label="Nationality Description" wire:model="nationality" id="nationality" /> --}}
   
      <x-slot:actions>
          <x-mary-button label="Cancel" @click="$wire.addContractModal = false"/>
          <x-mary-button 
                  label="Save Record" 
                  class="btn-primary" 
                  type="submit" 
                  spinner="save_contract"
                  wire:target="save_contract" />
      </x-slot:actions>

    </x-mary-form>
  </x-mary-modal>

  {{-- Right --}}
  <x-mary-drawer wire:model="showDrawer2" right style="width: 39%;">
    <!-- Manual Header -->
    <div class="px-6 pt-4 pb-2 border-b border-gray-200 custom-modal-header-div" style="margin-bottom: 0px !important;">
        <h2 class="text-lg font-semibold text-gray-800">Payment Transaction</h2>
    </div>
    {{-- <x-mary-card> --}}
      <x-mary-form wire:submit.prevent="save_payment" no-separator>

        

        <x-mary-card title="Contract Information" separator progress-indicator>
          <x-mary-input type="hidden" wire:model="contract_id" id="contract_id" />
          <div class="p-4 bg-white rounded-lg shadow-md">
            <table width="100%">
              <tr>
                <td width="25%">Contract Code</td>
                <td width="3%">:</td>
                <td><x-mary-input wire:model="contract_no_disabled" id="contract_no_disabled" disabled="disabled"/></td>
              </tr>
              <tr>
                <td width="25%">Client Name</td>
                <td width="3%">:</td>
                <td><x-mary-input wire:model="client_name" id="client_name" disabled="disabled"/></td>
              </tr>
              <tr>
                <td width="25%">Category</td>
                <td width="3%">:</td>
                <td><x-mary-input wire:model="contract_category_label" id="contract_category_label" disabled="disabled"/></td>
              </tr>
              <tr>
                <td width="25%">Type</td>
                <td width="3%">:</td>
                <td><x-mary-input wire:model="contract_type_label" id="contract_type_label" disabled="disabled"/></td>
              </tr>
              <tr>
                <td width="25%">Detail</td>
                <td width="3%">:</td>
                <td><x-mary-input wire:model="contract_category_type_detail_label" id="contract_category_type_detail_label" disabled="disabled"/></td>
              </tr>
              <tr>
                <td width="25%">Amount</td>
                <td width="3%">:</td>
                <td>
                  {{-- <x-mary-input wire:model="account_amount" id="account_amount" disabled="disabled"/> --}}
                  <x-mary-input 
                        prefix="PHP"
                        {{-- label="Amount"  --}}
                        wire:model.lazy="account_amount" 
                        id="account_amount"
                        type="text" 
                        inputmode="decimal" 
                        pattern="^\d+(\.\d{1,2})?$"
                        disabled="disabled"
                    />
                </td>
              </tr>
            </table>
          </div>
        </x-mary-card>

        <x-mary-card title="Payment Information" separator progress-indicator>

          <div class="p-4 bg-white rounded-lg shadow-md">

            <div class="flex flex-col md:flex-row">

              <div class="w-full md:flex-1" style="margin-bottom: 4px; width: 50% !important;">
                {{-- <x-mary-input label="Balance" wire:model="balance" id="balance" disabled="disabled"/> --}}
                 <x-mary-input 
                        prefix="PHP"
                        label="Balance" 
                        wire:model.lazy="balance" 
                        id="balance"
                        type="text" 
                        inputmode="decimal" 
                        pattern="^\d+(\.\d{1,2})?$"
                        disabled="disabled"
                    />
              </div>&nbsp;

              <div class="w-full md:flex-1" style="margin-bottom: 4px; width: 50% !important;">
                {{-- <x-mary-input label="Amount to be paid" wire:model="amount_to_be_paid" id="amount_to_be_paid" /> --}}
                <x-mary-input 
                        prefix="PHP"
                        label="Amount to be paid" 
                        wire:model.lazy="amount_to_be_paid" 
                        id="amount_to_be_paid"
                        type="text" 
                        inputmode="decimal" 
                        pattern="^\d+(\.\d{1,2})?$"
                    />
              </div>&nbsp;

              <div class="w-full md:flex-1" style="margin-bottom: 4px;">
                <x-mary-select
                  label="Payment Type"
                  :options="$this->load_payment_type_options"
                  option-value="id"
                  option-label="label"
                  placeholder="Select a payment type"
                  placeholder-value="0"
                  hint="Select one, please."
                  wire:model="payment_type_id"
                  wire:change="paymentTypeChanged" />
              </div>&nbsp;

            </div>

            <div class="flex flex-col md:flex-row">

              <div class="w-full md:flex-1" style="margin-bottom: 4px; width: 50% !important;">
                {{-- <x-mary-input label="Payment Amount" wire:model="amount" id="amount" /> --}}
                <x-mary-input 
                        prefix="PHP"
                        label="Payment Amount" 
                        wire:model.lazy="amount" 
                        id="amount"
                        type="text" 
                        inputmode="decimal" 
                        pattern="^\d+(\.\d{1,2})?$"
                    />
              </div>&nbsp;

              <!-- Conditionally Show Check No. Field -->
              @if($show_check_no)
                <div class="w-full md:flex-1" style="margin-bottom: 4px; width: 50% !important;">
                  <x-mary-input label="Check Bank" wire:model="check_bank" id="check_bank" />
                </div>&nbsp;

                <div class="w-full md:flex-1" style="margin-bottom: 4px; width: 50% !important;">
                  <x-mary-input label="Check No." wire:model="check_no" id="check_no" />
                </div>&nbsp;
              @endif
              

            </div>

            <div class="flex flex-col md:flex-row md:gap-4">

              <div class="w-full md:flex-1" style="margin-bottom: 4px; width: 50% !important;">
                <x-mary-input label="Remarks" wire:model="receipt_remarks" id="receipt_remarks" />
              </div>&nbsp;
              

            </div>

          </div>

        </x-mary-card>

        <x-slot:actions>
            <x-mary-button label="Close" @click="$wire.showDrawer2 = false" />
            <x-mary-button 
                    label="Save Payment" 
                    class="btn-primary" 
                    type="submit" 
                    spinner="save_payment"
                    wire:target="save_payment" />
        </x-slot:actions>
      </x-mary-form>
    {{-- </x-mary-card> --}}
  </x-mary-drawer>

  <!-- 
    Loader goes here 
  -->
  <x-livewire-loader target="contract_lst" message="Please wait while the system loads all citizenship records for you..." />

  <x-livewire-loader target="save_payment" message="Saving payment... please wait..." />

  <x-livewire-loader target="openEditContractModal" message="Please wait while the system retrieves the record for you..." />

  <x-livewire-loader target="openCreateContractWindow" message="Please wait while the system loads the contract form for you..." />

</div>