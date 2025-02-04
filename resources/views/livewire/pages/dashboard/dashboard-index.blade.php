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

  {{-- {{ auth()->user()->hasClearanceAreaRole(1) }}--}}
  {{-- {{ auth()->user() }} --}}
  {{-- {{ auth()->user()->account_role }} --}}

  {{-- <div class="grid grid-cols-4 gap-6 sm:grid-cols-2 lg:grid-cols-4">
      <x-mary-stat title="Messages" value="44" icon="o-envelope" tooltip="Hello" />

      <x-mary-stat
          title="Sales"
          description="This month"
          value="22.124"
          icon="o-arrow-trending-up"
          tooltip-bottom="There" />

      <x-mary-stat
          title="Lost"
          description="This month"
          value="34"
          icon="o-arrow-trending-down"
          tooltip-left="Ops!" />

      <x-mary-stat
          title="Sales"
          description="This month"
          value="22.124"
          icon="o-arrow-trending-down"
          class="text-orange-500"
          color="text-pink-500"
          tooltip-right="Gosh!" />
  </div> --}}

</div>