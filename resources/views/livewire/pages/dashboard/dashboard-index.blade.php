<div>
  <x-mary-header title="Dashboard"
      {{-- subtitle="Check this on mobile" --}}
      >
      {{-- <x-slot:middle class="!justify-end">
          <x-mary-input icon="o-bolt" placeholder="Search..." />
      </x-slot:middle>
      <x-slot:actions>
          <x-mary-button icon="o-funnel" />
          <x-mary-button icon="o-plus" class="btn-primary" />
      </x-slot:actions> --}}
  </x-mary-header>

  @if ($showSuccessMessage)
    <div 
    x-data="{ show: true }" 
    x-show="show" 
    x-init="setTimeout(() => { show = false; @this.set('showSuccessMessage', false) }, 3000)"
    x-transition
    class="fixed top-4 right-4 z-50">
      <x-mary-alert icon="s-check-circle" class="alert-success text-white">
          {{ $addMessage }}
      </x-mary-alert>
    </div>
  @endif

  <x-mary-card>

    <button wire:click="sendMail('{{ auth()->user()->email }}')" class="btn btn-primary" wire.target="sendMail">
      Send Mail
    </button>

    
  {{-- {{ auth()->user()->hasClearanceAreaRole(1) }}--}}
  {{-- {{ auth()->user() }} --}}
  {{-- {{ auth()->user()->account_role }} --}}


  </x-mary-card>

	<!-- Loader using Blade Component -->
	<div
			wire:loading
			wire:target="sendMail"
			class="fixed inset-0 flex items-center justify-center bg-white/10 backdrop-blur-sm z-50 rounded-lg"
	>
			<div class="text-center">
					<x-mary-loading class="progress-primary w-10 h-10" style="margin-top: 400px;" />
					<p class="mt-2 text-gray-700 font-medium">Please wait while the system loads all the transaction logs for you...</p>
			</div>
	</div>

</div>