<div>
    <div x-data="{
    counter: 0,
    editMode: $wire.entangle('editMode'),
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
        <div class="h-screen w-full mt-0.5 relative"
             wire:ignore
             x-data="{
            map: null,
            circles: [],
            zoom: $wire.entangle('zoom'),
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
                    circle.bindTooltip('Reservado', {
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
                    minZoom: -2,
                    scrollWheelZoom: false,
                    maxZoom: this.zoom,
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

            <div id="map" class="w-full h-full"></div>

            <div x-show="showDropdown"
                 x-cloak
                 @click.away="showDropdown = false"
                 :style="`position: absolute; left: ${dropdownPosition.x}px; top: ${dropdownPosition.y - 40}px;`"
                 class="flex flex-col bg-white shadow-lg rounded-md p-4 gap-2 z-[1000] transform -translate-x-1/2">

                @if($editMode)
                    <button @click="
                    selectedCircle.pointData.placedInMap = false;
                    selectedCircle.remove();
                    $wire.deleteDesk(selectedCircle.pointData);
                    circles = circles.filter(c => c !== selectedCircle);
                    selectedCircle = null;
                    showDropdown = false;"
                            class="text-red-600 hover:text-red-800 whitespace-nowrap">
                        Eliminar
                    </button>
                @else
                    <template x-if="selectedCircle.pointData.bookings.length > 0">
                        <div class="flex flex-col gap-2">
                            <span>Reservado por Usuario</span>
                            <button @click="$wire.deleteBook(selectedCircle.pointData);  $data.showDropdown = false;"
                                    class="text-red-600 hover:text-red-800 whitespace-nowrap">
                                Cancelar Reserva
                            </button>
                        </div>
                    </template>
                    <template x-if="selectedCircle && selectedCircle.pointData.bookings.length === 0">
                        <button @click="$wire.bookDesk(selectedCircle.pointData);  $data.showDropdown = false;"
                                class="text-green-600 hover:text-green-800 whitespace-nowrap">
                            Reservar
                        </button>
                    </template>
                @endif
            </div>
        </div>
    </div>
</div>
