@props(['isSuccess' => false, 'message' => ''])

<div 
  x-data="{ 
    show: false,
    message: @entangle('message'),
    isSuccess: @entangle('isSuccess'),
    init() {
      this.$watch('message', () => {
        if (this.message) {
          this.show = true;
          setTimeout(() => this.show = false, 3000);
        }
      })
    }
  }"
  x-init="init()"
  x-show="show"
  x-transition
  class="fixed top-4 right-4 z-50"
  style="display: none;"
>
  <x-mary-alert 
    :icon="$isSuccess ? 's-check-circle' : 'c-x-circle'" 
    :class="$isSuccess ? 'alert-success text-white' : 'bg-danger text-white'"
  >
    {{ $message }}
  </x-mary-alert>
</div>