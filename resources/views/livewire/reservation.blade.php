<div>
    <form wire:submit="create" class="mb-6 flex flex-col gap-6">
        {{ $this->form }}
    </form>

    <x-filament-actions::modals />
</div>
