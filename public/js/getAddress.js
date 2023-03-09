var marker = null;

btn_go.addEventListener("click", async function (event) {
    event.preventDefault();
    console.log("coucou");
    let nom = infrastructure_nom.value;
    let rue = infrastructure_rue.value;
    let codePostal = infrastructure_codePostal.value;
    let arrondissement = infrastructure_arrondissement.value;
    let ville = infrastructure_ville.value;
    // console.log(nom + " " + rue + " " + codePostal + " " + arrondissement + " " + ville);
    //  let _=" "
    //let adresse = nom + _ + rue + _arrondissement + _ + codePostal + _ + ville;

    let urlAPI = "https://api-adresse.data.gouv.fr/search/?q=" + nom + " " + rue + " " + codePostal + " " + ville;
    let response = await fetch(urlAPI);
    let json = await response.json();
    // console.log(json);
    let latitude = json.features[0].geometry.coordinates[1];
    let longitude = json.features[0].geometry.coordinates[0];
    infrastructure_latitude.value = latitude;
    infrastructure_longitude.value = longitude;

    if (marker == null) {
        //ajout marqueur
        marker = L.marker([latitude, longitude], {
            draggable: true,
            // centrer sur le point recherché

        }).addTo(mymap);

        //On écoute le glisser/déposer sur le marqueur
        marker.on("dragend", function (e) {
            position = e.target.getLatLng()
            document.querySelector("#infrastructure_latitude").value = position.lat
            document.querySelector("#infrastructure_longitude").value = position.lng
        })
        marker.bindPopup(nom + " " + rue + " " + codePostal + " " + ville + " " + arrondissement).openPopup();
        centerLeafletMapOnMarker(mymap, marker);
    } else if (
        // On vérifie si un marqueur existe
        marker != undefined) {
        mymap.removeLayer(marker) // ON RETIRE LE MARQUEUR SI IL EST DEJA DEFINIE //ajout marqueur
        marker = L.marker([latitude, longitude], {
            draggable: true,
            // centrer sur le point recherché

        }).addTo(mymap);

        //On écoute le glisser/déposer sur le marqueur
        marker.on("dragend", function (e) {
            position = e.target.getLatLng()
            document.querySelector("#infrastructure_latitude").value = position.lat
            document.querySelector("#infrastructure_longitude").value = position.lng
        })
        marker.bindPopup(nom + " " + rue + " " + codePostal + " " + ville + " " + arrondissement).openPopup();
        centerLeafletMapOnMarker(mymap, marker);
    }

})

function centerLeafletMapOnMarker(map, marker) {
    var latLngs = [marker.getLatLng()];
    var markerBounds = L.latLngBounds(latLngs);
    map.fitBounds(markerBounds);
}
/////////////////////////////////////////////////////////////

// Création des click on map *Création pour la fonction
mymap.on("click", mapClickListen)

function mapClickListen(e) {
    // On récupère les coordonées du clic
    let position = e.latlng
    // On ajoute le marqueur *Création pour la fonction
    addMarker(position)


    // on affiche les coordonées dans le formulaire
    document.querySelector("#infrastructure_latitude").value = position.lat
    document.querySelector("#infrastructure_longitude").value = position.lng
}
// marker.bindPopup(" ok ok").openPopup();


function addMarker(position) {
    // On vérifie si un marqueur existe
    if (marker != undefined) {
        mymap.removeLayer(marker) // ON RETIRE LE MARQUEUR SI IL EST DEJA DEFINIE
    }

    marker = L.marker(position, {
        // Marqueur délaçable
        draggable: true
    })

    // On écoute le glisser/déposer sur le marqueur
    marker.on("dragend", function (e) {
        position = e.target.getLatLng()
        document.querySelector("#infrastructure_latitude").value = position.lat // Remise a jour des Lat et Long
        document.querySelector("#infrastructure_longitude").value = position.lng
    })


    marker.addTo(mymap)

}