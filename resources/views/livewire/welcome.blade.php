<div>
    @auth
        <!-- Reservation -->
        <div class="max-w-4xl mx-auto px-4 py-16 text-center">
            <h2 class="text-3xl font-bold mb-4">
                Hola {{ $name }}, ¿Quieres venir a la oficina?
            </h2>
            <p class="text-xl text-primary-600 mb-8">
                Reserva tu puesto de trabajo de manera rápida y sencilla y asegúrate de tener tu espacio listo.
            </p>
            @livewire('reservation')
            @livewire('bookings-table')
        </div>
    @endauth

    @guest
        <div class="bg-white">
            <div class="max-w-6xl mx-auto px-4 py-16">
                <div class="flex flex-col items-center">

                    <h1 class="text-4xl font-bold text-gray-900 mb-4">
                        Reserva tu puesto
                    </h1>
                    <p class="text-xl text-gray-600 mb-8">
                        Reserva tu escritorio de forma fácil y rápida
                    </p>
                    <div class="flex justify-center">
                        @guest
                            <a href="{{ route('login') }}"
                               class="bg-blue-600 text-white px-8 py-4 rounded-lg text-lg hover:bg-blue-700">
                                {{ __('book_your_seat') }}
                            </a>
                        @else
                            <button class="bg-blue-600 text-white px-8 py-4 rounded-lg text-lg hover:bg-blue-700">
                                Comenzar ahora
                            </button>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    @endguest

</div>
