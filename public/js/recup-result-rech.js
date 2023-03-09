// selectionner toutes les balises avec la classe marker
//boucle sur chaque éléments
//pour chaque élément on va récupérer la latitude et la longitude
//creer un marqueur sur la carte avec cette position

//creer une nouvelle variable (boite )
var listMarker = {};


document.querySelectorAll(".marker").forEach(function (element) {
      //console.log(element);
      let latitude = element.querySelector(".infrastructure_latitude").value;
      let longitude = element.querySelector(".infrastructure_longitude").value;
      let idSelect = element.querySelector(".infrastructure_id").value;
      let nom = element.querySelector(".infrastructure_nom").innerHTML;
      let rue = element.querySelector(".infrastructure_rue").innerHTML;
      let codePostal = element.querySelector(".infrastructure_code_postal").innerHTML;
      let ville = element.querySelector(".infrastructure_ville").innerHTML;
      let categorie = element.querySelector(".infrastructure_categorie").innerHTML;

      if (categorie == "Santé") {
            var myIcon = L.icon({
                  iconUrl: '../uploads/pictosante.png',
                  //shadowUrl: 'icon-shadow.png',      
                  iconSize: [34, 44], // taille de l'icone
                  //shadowSize:   [50, 64], // taille de l'ombre
                  iconAnchor: [17, 64], // point de l'icone qui correspondra à la position du marker
                  //shadowAnchor: [32, 64],  // idem pour l'ombre
                  popupAnchor: [-3, -66] // point depuis lequel la popup doit s'ouvrir relativement à l'iconAnchor
            });
      } else if (categorie == "Nature et Aventure") {
            var myIcon = L.icon({
                  iconUrl: '../uploads/pictonatureetaventure.png',
                  //shadowUrl: 'icon-shadow.png',      
                  iconSize: [34, 44], // taille de l'icone
                  //shadowSize:   [50, 64], // taille de l'ombre
                  iconAnchor: [17, 64], // point de l'icone qui correspondra à la position du marker
                  //shadowAnchor: [32, 64],  // idem pour l'ombre
                  popupAnchor: [-3, -76] // point depuis lequel la popup doit s'ouvrir relativement à l'iconAnchor
            });
      } else if (categorie == "Animaux") {
            var myIcon = L.icon({
                  iconUrl: '../uploads/pictoanimaux.png',
                  //shadowUrl: 'icon-shadow.png',      
                  iconSize: [34, 44], // taille de l'icone
                  //shadowSize:   [50, 64], // taille de l'ombre
                  iconAnchor: [17, 64], // point de l'icone qui correspondra à la position du marker
                  //shadowAnchor: [32, 64],  // idem pour l'ombre
                  popupAnchor: [-3, -76] // point depuis lequel la popup doit s'ouvrir relativement à l'iconAnchor
            });
      } else if (categorie == "Arts et Culture") {
            var myIcon = L.icon({
                  iconUrl: '../uploads/pictoartetculture.png',
                  //shadowUrl: 'icon-shadow.png',      
                  iconSize: [34, 44], // taille de l'icone
                  //shadowSize:   [50, 64], // taille de l'ombre
                  iconAnchor: [17, 64], // point de l'icone qui correspondra à la position du marker
                  //shadowAnchor: [32, 64],  // idem pour l'ombre
                  popupAnchor: [-3, -76] // point depuis lequel la popup doit s'ouvrir relativement à l'iconAnchor
            });
      } else if (categorie == "Alimentation et Restauration") {
            var myIcon = L.icon({
                  iconUrl: '../uploads/pictoalimentationetrestaurant.png',
                  //shadowUrl: 'icon-shadow.png',      
                  iconSize: [34, 44], // taille de l'icone
                  //shadowSize:   [50, 64], // taille de l'ombre
                  iconAnchor: [17, 64], // point de l'icone qui correspondra à la position du marker
                  //shadowAnchor: [32, 64],  // idem pour l'ombre
                  popupAnchor: [-3, -76] // point depuis lequel la popup doit s'ouvrir relativement à l'iconAnchor
            });
      } else if (categorie == "Bien-être") {
            var myIcon = L.icon({
                  iconUrl: '../uploads/pictobienetre.png',
                  //shadowUrl: 'icon-shadow.png',      
                  iconSize: [34, 44], // taille de l'icone
                  //shadowSize:   [50, 64], // taille de l'ombre
                  iconAnchor: [17, 64], // point de l'icone qui correspondra à la position du marker
                  //shadowAnchor: [32, 64],  // idem pour l'ombre
                  popupAnchor: [-3, -76] // point depuis lequel la popup doit s'ouvrir relativement à l'iconAnchor
            });
      } else if (categorie == "Sport") {
            var myIcon = L.icon({
                  iconUrl: '../uploads/pictosport.png',
                  //shadowUrl: 'icon-shadow.png',      
                  iconSize: [34, 44], // taille de l'icone
                  //shadowSize:   [50, 64], // taille de l'ombre
                  iconAnchor: [17, 64], // point de l'icone qui correspondra à la position du marker
                  //shadowAnchor: [32, 64],  // idem pour l'ombre
                  popupAnchor: [-3, -76] // point depuis lequel la popup doit s'ouvrir relativement à l'iconAnchor
            });
      }

    //  console.log(latitude + " " + longitude);
      let marker = L.marker([latitude, longitude],{icon:myIcon}).addTo(mymap);
      listMarker["id" + idSelect] = marker;
      marker.bindPopup("<strong>"+nom +"</strong>"+"</br>" + rue + "</br>" + codePostal + " " + ville + "</br>").openPopup();

})


function selectInfra(idSelect) {
      event.preventDefault();
      console.log(idSelect)
      let marker = listMarker["id" + idSelect];
      marker.openPopup();
      mymap.flyTo(marker.getLatLng());
     

}
 
function TriSelector(categorie) {
      document.getElementById(".infrastructure_categorie").style.display = none;

}