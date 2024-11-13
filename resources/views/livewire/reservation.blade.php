<div>
    <form wire:submit="create" class="mb-6 flex flex-col gap-6">
        {{ $this->form }}

        <span>
            <button type="submit" class="bg-blue-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-blue-700">
                {{ __('book_now') }}
            </button>
        </span>
    </form>

    <x-filament-actions::modals />
</div>
