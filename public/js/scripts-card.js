/******************leaflet *****************************************/
window.onload = function () {
    // Fonction d'initialisation qui s'exécute lorsque le DOM est chargé
    initMap();
}

var latitude= 43.295229;
var longitude= 5.377512;
var zoom= 20;

let marqueur;
var mymap = L.map('mapid').setView([latitude, longitude], zoom); 

/*x = longitude y= latitudeitude 
 /*le dernier nombre est le zoom*/ 

function initMap() {
      L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
            maxZoom: 20,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
                  'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1
      }).addTo(mymap);

      // Création des click on map *Création pour la fonction
  //  mymap.on("click",mapClickListen)
    
};
