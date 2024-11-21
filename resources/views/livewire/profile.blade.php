<div class="flex-grow bg-gray-50">
        <!-- Reservation -->
        <div class="max-w-4xl mx-auto px-4 py-16">
            <h2 class="text-3xl font-bold mb-10">
                @translate('edit_profile')
            </h2>

            <form wire:submit="create">
                {{ $this->form }}
            </form>

            <x-filament-actions::modals />
        </div>
</div>
