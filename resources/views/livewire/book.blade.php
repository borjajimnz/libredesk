<div class="w-full h-full">
    <div class="w-full h-full"
        x-data="{
    counter: 0,
    editMode: $wire.entangle('editMode'),
    auth: $wire.entangle('auth'),
    selectedCircle: null,
    showDropdown: false,
    dropdownPosition: { x: 0, y: 0 },
    points: $wire.entangle('desks'),
    clearMap() {
        if (this.circles) {
            this.circles.forEach(circle => circle.remove());
        }
        this.circles = [];
    }
}">
        <div class="w-full h-full"
             wire:ignore
             x-data="{
            map: null,
            circles: [],
            getNextUnplacedPoint() {
                return $data.points
                    .filter(p => p.placedInMap === false)
                    .sort((a, b) => a.order - b.order)[0];
            },
            placeCircle(latlng, pointData) {
                let circleOptions = {
                    color: 'green',
                    fillColor: 'green',
                    fillOpacity: 0.5,
                    radius: 30
                };

                @if(!$editMode)
                    circleOptions = {
                        color: 'green',
                        fillColor: 'green',
                        fillOpacity: 0.5,
                        radius: 30
                    };

                    if (pointData.bookings.length > 0) {
                        circleOptions = {
                            color: 'red',
                            fillColor: 'red',
                            fillOpacity: 0.5,
                            radius: 30
                        };
                    }
                @endif

                const circle = L.circle(latlng, circleOptions).addTo(this.map);
                circle.pointData = pointData;
                pointData.placedInMap = true;

                @if($editMode)
                    circle.bindTooltip(pointData.name, {
                            permanent: true,
                            direction: 'top'
                        });

                @else
                if (pointData.bookings.length > 0) {
                    circle.bindTooltip(pointData.bookings[0]?.user.name, {
                        permanent: true,
                        direction: 'top'
                    });
                } else {
                    circle.bindTooltip(pointData.name, {
                        permanent: true,
                        direction: 'top'
                    });
                }
                @endif

                circle.on('click', (event) => {
                    L.DomEvent.stopPropagation(event);
                    const circleLatLng = circle.getLatLng();
                    const point = this.map.latLngToContainerPoint(circleLatLng);

                    $data.dropdownPosition = {
                        x: point.x,
                        y: point.y
                    };

                    $data.selectedCircle = circle;
                    $data.showDropdown = true;
                });

                this.circles.push(circle);
            },
            refreshMap() {
                $data.clearMap();
                this.initializePoints();
            },
            initializePoints() {
                $data.points.forEach((point) => {
                    if (point.placedInMap) {
                        this.placeCircle([point.lat, point.lng], point);
                    }
                });
            },
            initMap() {
                this.map = L.map('map', {
                    crs: L.CRS.Simple,
                    minZoom: -1.5,
                    scrollWheelZoom: false,
                    maxZoom: 10,
                    zoomControl: false,
                    attributionControl: true,
                    center: [513, 1000]
                });

                const bounds = L.latLngBounds([
                    [0, 0],
                    [1026, 2000]
                ]);

                L.imageOverlay('{{ $image }}', bounds).addTo(this.map);
                this.map.fitBounds(bounds);

                L.control.zoom({
                    position: 'topright'
                }).addTo(this.map);

                this.initializePoints();

                this.map.on('click', (e) => {
                    if ($data.showDropdown) {
                        $data.showDropdown = false;
                        return;
                    }

                    @if($editMode)
                        const nextPoint = this.getNextUnplacedPoint();
                        if (!nextPoint) {
                            alert('Ya se han colocado todos los puntos disponibles');
                            return;
                        }

                        this.placeCircle(e.latlng, nextPoint);
                        $wire.saveDesk(nextPoint, e.latlng)
                    @endif
                });

                this.map.on('movestart', () => {
                    $data.showDropdown = false;
                });

                this.map.on('zoomstart', () => {
                    $data.showDropdown = false;
                });
            }
        }"
             x-init="
            initMap();
            $watch('points', (newValue, oldValue) => {
                if (JSON.stringify(newValue) !== JSON.stringify(oldValue)) {
                    refreshMap();
                }
            });
        "
        >
            @if($editMode)
                <div x-cloak x-show="points.filter(p => p.placedInMap === false).length > 0" class="absolute top-3 left-3 bg-white p-2 rounded shadow z-[1000]">
                    <h3 class="font-bold mb-2">Puestos pendientes:</h3>
                    <template x-cloak x-for="point in points.filter(p => p.placedInMap === false)" :key="point.id">
                        <div class="mb-1">
                            <span x-text="`${point.name}`"></span>
                        </div>
                    </template>
                </div>
            @endif

            <div id="map" class="w-full h-full" style="z-index: 13"></div>

            <div x-show="showDropdown"
                 x-cloak
                 @click.away="showDropdown = false"
                 :style="`position: absolute; left: ${dropdownPosition.x}px; top: ${dropdownPosition.y - 40}px;`"
                 class="flex flex-col bg-white shadow-lg rounded-md p-4 gap-2 z-[1000] transform -translate-x-1/2">

                <div class="flex flex-row justify-between items-center min-w-[200px]">
                    <span x-text="selectedCircle.pointData.name" class="font-semibold"></span>
                    <button @click="
                    selectedCircle = null;
                    showDropdown = false;"
                            class="whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>

                    </button>
                </div>
                <div class="flex flex-col justify-between items-center min-w-[200px] min-h-[70px]">
                    <template x-if="selectedCircle && selectedCircle.pointData.attributes.image">
                        <img :src="selectedCircle.pointData.imageStorage" width="120px" class="rounded-xl" />
                    </template>
                    <template x-if="selectedCircle && selectedCircle.pointData.attributes?.description">
                        <span x-text="selectedCircle.pointData.attributes.description"></span>
                    </template>
                @if($editMode)
                    <button @click="
                    selectedCircle.pointData.placedInMap = false;
                    selectedCircle.remove();
                    $wire.deleteDesk(selectedCircle.pointData);
                    circles = circles.filter(c => c !== selectedCircle);
                    selectedCircle = null;
                    showDropdown = false;"
                            class="text-red-600 hover:text-red-800 whitespace-nowrap">
                        @translate('delete')
                    </button>
                @else
                    <template x-if="selectedCircle && selectedCircle.pointData.bookings.length > 0">
                        <div class="flex flex-col gap-2">
                            <span>@translate('reserved_by') <span x-text="selectedCircle.pointData.bookings[0]?.user.name"></span></span>
                            <div x-cloak x-show="selectedCircle.pointData.bookings[0]?.user.id === auth.id">
                                <button @click="$wire.deleteBook(selectedCircle.pointData); $data.showDropdown = false;"
                                        class="text-red-600 hover:text-red-800 whitespace-nowrap">
                                    @translate('cancel_booking')
                                </button>
                            </div>
                        </div>
                    </template>
                    <template x-if="selectedCircle && selectedCircle.pointData.bookings.length === 0">
                        <button @click="$wire.bookDesk(selectedCircle.pointData);  $data.showDropdown = false;"
                                class="text-green-600 hover:text-green-800 whitespace-nowrap">
                            @translate('book_it')
                        </button>
                    </template>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
