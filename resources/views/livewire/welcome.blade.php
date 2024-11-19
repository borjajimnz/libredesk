<div class="bg-gray-50 flex-grow">
    @auth
        <!-- Reservation -->
        <div class="max-w-4xl mx-auto px-4 py-16 text-center">
            <h2 class="text-3xl font-bold mb-4">
                @translate('welcome_title', ['name' => $name])
            </h2>
            <p class="text-xl text-primary-600 mb-8">
                @translate('welcome_title_desc')
            </p>
            @livewire('reservation')
            @livewire('bookings-table')
        </div>
    @endauth

    @guest
        <div class="bg-white">
            <div class="max-w-6xl mx-auto px-4 py-16">
                <div class="flex flex-col items-center">
                    @setting('name', config('app.name'))

                    <h1 class="text-4xl font-bold text-gray-900 mb-4">
                        @translate('welcome_guest_title')
                    </h1>
                    <p class="text-xl text-gray-600 mb-8">
                        @translate('welcome_guest_desc')
                    </p>
                    <div class="flex justify-center">
                        @guest
                            <a href="{{ route('filament.app.auth.login') }}"
                               class="bg-primary-600 text-white px-8 py-4 rounded-lg text-lg hover:bg-primary-700">
                                @translate('book_your_seat')
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    @endguest

</div>
