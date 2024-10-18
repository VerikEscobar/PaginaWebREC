var myMap;
var marcador;

/* CARGA EL MAPA */
cargarMapa();

/* FUNCION QUE DIBUJA EL MAPA SEGUN LOS PARAMETROS QUE LE PASES EN LATIT Y LONGIT */
function dibujarMapa(latit, longit, nombreMapa) {
    let campoLatitud = $('#lat_carga');
    let campoLongitud = $('#lon_carga');

    /* SELECCIONA EL TIPO DE MAPA A UTILIZAR */
    const TilesProvider = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

    /* SELECCIONA LA IMAGEN QUE SERA PUNTERO EN EL MAPA */
    let iconMarker = L.icon({
        iconUrl: '../mapaModal/images/marker.png',
        iconSize: [60, 60],
        iconAnchor: [30, 60]
    });

    /* SE DEBE AGREGAR ZOOMCONTROL FALSE PARA PODER MODIFICAR DE LUGAR LOS CONTROLES */
    /* let myMap = L.map('myMap', { zoomControl: false }).setView([latit, longit], 13); */
    //myMap = L.map(nombreMapa, { zoomControl: false , attributionControl: false}).setView([latit, longit], 13);
    myMap = L.map(nombreMapa, { zoomControl: false , attributionControl: false}).setView([latit, longit], 13);
    /* PASA LOS DATOS DE LATITUD Y LONGITUD A LOS CAMPOS OCULTOS */
    campoLatitud.val(latit);
    campoLongitud.val(longit);

    /* DESACTIVA EL ZOOM CON DOBLE CLICK */
    myMap.doubleClickZoom.disable();

    /* POSICIONA LOS CONTROLES DEL ZOOM A LA DERECHA */
    L.control.zoom({ position: "topright" }).addTo(myMap);

    /* SELECCIONA EL ZOOM A APLICAR AL MAPA */
    L.tileLayer(TilesProvider, {
        maxZoom: 18,
    }).addTo(myMap);

    /* AGREGA EL MARCADOR CON POP-UP AL MAPA */
    /* const marcador = L.marker([latit, longit], { icon: iconMarker, alt: 'Ubicacion', draggable: true, autoPan: true }).addTo(myMap).bindPopup('IA-Campo te encontro!'); */

    /* AGREGA EL MARCADOR SIMPLE AL MAPA */
    marcador = L.marker([latit, longit], { icon: iconMarker, alt: 'Ubicacion', draggable: true, autoPan: true }).addTo(myMap);

    /*MODIFICA LA POSICION DEL MARCADOR EN DOBLE CLICK*/
    /* myMap.on('dblclick', e => { */
    // myMap.on('click', e => {
    //     let latlong = myMap.mouseEventToLatLng(e.originalEvent);
    //     marcador.setLatLng([latlong.lat, latlong.lng])
    //     console.log('Latitud: ' + latlong.lat + ', Longitud: ' + latlong.lng);
    //     campoLatitud.val(latlong.lat);
    //     campoLongitud.val(latlong.lng);
    // });

    /* VERIFICA LA LATITUD Y LONGITUD CUANDO SE MUEVE EL PUNTERO */
    marcador.on("moveend", e => {
        let latlong = marcador.getLatLng();
        console.log('Latitud: ' + latlong.lat + ', Longitud: ' + latlong.lng);
        campoLatitud.val(latlong.lat);
        campoLongitud.val(latlong.lng);
    });
}

/* CONSULTA QUE EJECUTA LA GEOLOCALIZACION Y DIBUJA EL MAPA */
function cargarMapa() {

    navigator.geolocation.getCurrentPosition(
        (pos) => {
            /* SI ENCUENTRA GEOLOCALIZACION LA PINTA EN ESA LAT Y LNG */
            var { coords } = pos
            dibujarMapa(coords.latitude, coords.longitude, 'myMap');
            
        },
        (err) => {
            /* SI NO ENCUENTRA GEOLOCALIZACION PINTA EN UN PUNTO FIJO */
            console.log(err);
            dibujarMapa(-25.319955, -57.591397, 'myMap');
        }, {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    }
    );
}

/* REDIMENSIONAR EL MAPA AL MOSTRAR EL TAP QUE LO CONTIENE */
$('#modal_principal').on('shown.bs.modal', function (event) {
    setTimeout(function () {
        /* MAPA SE REDIMENSIONA AL CARGAR EL DIV QUE LO CONTIENE */
        myMap.invalidateSize()
    }, 100
    );
});
$('#modal_detalle').on('shown.bs.modal', function (event) {
    setTimeout(function () {
        /* MAPA SE REDIMENSIONA AL CARGAR EL DIV QUE LO CONTIENE */
        myMap.invalidateSize()
    }, 100
    );
})

/* PASAR NUEVA UBICACION AL MAPA SIN VOLVER A INICIALIZAR */
function BuscarEnMapa(lat, lng) {
    //PASAR LATITUD Y LONGITUD
    marcador.setLatLng([lat, lng], 13);
    //CENTRAR EN LA NUEVA UBUCACION
    //myMap.fitBounds([[lat, lng]]);
    //DESPLAZAMIENTO SUAVE A UN PUNTO (PERMITE ZOOM)
    myMap.flyTo([lat, lng], 13);
}