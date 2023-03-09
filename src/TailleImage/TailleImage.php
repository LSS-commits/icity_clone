<?php

namespace App\TailleImage;

class TailleImage
{
    public function redimenImage($fichierImage, $pathImage, $dimension = 200)
    {

        // dump($fichierImage);
        // dump($pathImage);

        $image = $pathImage . '/' . $fichierImage;

        // Connaitre la taille du fichier "source"
        $info = getimagesize($image);

        // Connaitre le côté le plus petit et sa taille
        $largeurSource = $info[0];
        $hauteurSource = $info[1];

        // Savoir où se place le coin supérieur gauche du carré source
        // On calcule la moitié de la zone restante après retrait du carré
        // Image horizontale : (largeurImage - largeurCarré) / 2
        // Image verticale : (hauteurImage - hauteurCarré) / 2
        switch ($largeurSource <=> $hauteurSource) {
            case -1:
                // Image portrait
                $tailleCarre = $largeurSource;
                $src_x = 0;
                $src_y = ($hauteurSource - $tailleCarre) / 2;
                break;
            case 0:
                // Image carrée
                $tailleCarre = $largeurSource;
                $src_x = 0;
                $src_y = 0;
                break;
            case 1:
                // Image paysage
                $tailleCarre = $hauteurSource;
                $src_x = ($largeurSource - $tailleCarre) / 2;
                $src_y = 0;
                break;
        }

        // On peut ensuite copier le carré source dans l'image de destination
        // Créer une image en mémoire
        // Equivalent à un nouveau fichier dans un programme de dessin
        $nouvelleImage = imagecreatetruecolor($dimension, $dimension);
        switch ($info["mime"]) {
                // On liste TOUS les types mime autorisés (png et jpeg)
            case "image/png":
                $ancienneImage = imagecreatefrompng($image);
                break;
            case "image/jpeg":
                $ancienneImage = imagecreatefromjpeg($image);
                break;
        }

        // On copie ancienneImage dans nouvelleImage sans la déformer
        imagecopyresampled(
            $nouvelleImage, // Image de destination
            $ancienneImage, // Image source
            0, // Décalage horiz coin supérieur gauche de l'image de destination
            0, // Décalage verti coin supérieur gauche de l'image de destination
            $src_x, // Décalage horiz coin supérieur gauche de l'image source
            $src_y, // Décalage verti coin supérieur gauche de l'image source
            $dimension, // Largeur de la zone de destination
            $dimension, // Hauteur de la zone de destination
            $tailleCarre, // Largeur de la zone source $info contient les dimensions de l'image source
            $tailleCarre // Hauteur de la zone source
        );

        $pathMini = $pathImage . "/${dimension}x${dimension}-" . $fichierImage;

        // On enregistre l'image sur le serveur
        switch ($info["mime"]) {
                // On liste TOUS les types mime autorisés (png et jpeg)
            case "image/png":
                imagepng($nouvelleImage, $pathMini);
                break;
            case "image/jpeg":
                imagejpeg($nouvelleImage, $pathMini);
                break;
        }
        // On détruit les images dans la mémoire
        imagedestroy($nouvelleImage);
        imagedestroy($ancienneImage);
    }
}
