<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Infrastructure;
use App\Form\InfrastructureType;
use App\Repository\InfrastructureRepository;
use App\Repository\CategorieRepository;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\TailleImage\TailleImage;


class MembreController extends AbstractController
{
    #[Route('/membre', name: 'membre', methods: ['GET'])]
    public function index(Request $request, InfrastructureRepository $infrastructureRepository, CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();

        $mot = $request->get('mot');
        // dump($mot);

        $categorie = $request->get('categorie');
        // dump($categorie);

        $arrondissement = $request->get('arrondissement');
        // dump($arrondissement);


        if (!empty($mot) && !empty($categorie) && !empty($arrondissement)) {
            // rechercher7: combinaison de 3 criteres (arrondissement/categorie/mot)
            $infraACM = $infrastructureRepository->chercherMotCatArr($mot, $categorie, $arrondissement);

            // dump($infraACM);

            return $this->render('site/resultat_recherche.html.twig', [

                'infraACM' => $infraACM ?? [],
                'arrondissement' => $arrondissement,
                'categorie'      => $categorie,
                'categories'      => $categories,
                'mot'      => $mot,
            ]);
        } else {

            if (empty($mot) && !empty($categorie) && !empty($arrondissement)) {
                // rechercher6: combinaison de 2 criteres (arrondissement/categorie)
                $infraACM = $infrastructureRepository->chercherArrCat($arrondissement, $categorie);

                return $this->render('site/resultat_recherche.html.twig', [
                    'infraACM' => $infraACM ?? [],
                    'arrondissement' => $arrondissement,
                    'categorie'      => $categorie,
                    'categories'      => $categories,
                    'mot'      => $mot,
                ]);
            } else {
                if (empty($mot) && empty($categorie) && !empty($arrondissement)) {
                    // rechercher3: par arrondissement
                    $infraACM = $infrastructureRepository->chercherArrondissement($arrondissement);

                    return $this->render('site/resultat_recherche.html.twig', [
                        'infraACM' => $infraACM ?? [],
                        'arrondissement' => $arrondissement,
                        'categorie'      => $categorie,
                        'categories'      => $categories,
                        'mot'      => $mot,
                    ]);
                }

                if (empty($mot) && !empty($categorie) && empty($arrondissement)) {
                    // rechercher3: par categorie
                    $infraACM = $infrastructureRepository->chercherCategorie($categorie);

                    return $this->render('site/resultat_recherche.html.twig', [
                        'infraACM' => $infraACM ?? [],
                        'arrondissement' => $arrondissement,
                        'categorie'      => $categorie,
                        'categories'      => $categories,
                        'mot'      => $mot,
                    ]);
                }
            }

            if (!empty($mot) && empty($categorie) && !empty($arrondissement)) {
                // rechercher5: combinaison de 2 criteres (mot/arrondissement)
                $infraACM = $infrastructureRepository->chercherArrMot($arrondissement, $mot);

                return $this->render('site/resultat_recherche.html.twig', [
                    'infraACM' => $infraACM ?? [],
                    'arrondissement' => $arrondissement,
                    'categorie'      => $categorie,
                    'categories'      => $categories,
                    'mot'      => $mot,
                ]);
            } else {
                if (empty($mot) && empty($categorie) && !empty($arrondissement)) {
                    // rechercher3: par arrondissement
                    $infraACM = $infrastructureRepository->chercherArrondissement($arrondissement);

                    return $this->render('site/resultat_recherche.html.twig', [
                        'infraACM' => $infraACM ?? [],
                        'arrondissement' => $arrondissement,
                        'categorie'      => $categorie,
                        'categories'      => $categories,
                        'mot'      => $mot,
                    ]);
                }

                if (!empty($mot) && empty($categorie) && empty($arrondissement)) {
                    // rechercher3: par mot
                    $infraACM = $infrastructureRepository->chercherMot($mot);

                    return $this->render('site/resultat_recherche.html.twig', [
                        'infraACM' => $infraACM ?? [],
                        'arrondissement' => $arrondissement,
                        'categorie'      => $categorie,
                        'categories'      => $categories,
                        'mot'      => $mot,
                    ]);
                }
            }

            if (!empty($mot) && !empty($categorie) && empty($arrondissement)) {
                // rechercher4: combinaison de 2 criteres (mot/categorie)
                $infraACM = $infrastructureRepository->chercherMotCat($mot, $categorie);

                return $this->render('site/resultat_recherche.html.twig', [
                    'infraACM' => $infraACM ?? [],
                    'arrondissement' => $arrondissement,
                    'categorie'      => $categorie,
                    'categories'      => $categories,
                    'mot'      => $mot,
                ]);
            } else {
                if (empty($mot) && !empty($categorie) && empty($arrondissement)) {
                    // rechercher3: par categorie
                    $infraACM = $infrastructureRepository->chercherCategorie($categorie);

                    return $this->render('site/resultat_recherche.html.twig', [
                        'infraACM' => $infraACM ?? [],
                        'arrondissement' => $arrondissement,
                        'categorie'      => $categorie,
                        'categories'      => $categories,
                        'mot'      => $mot,
                    ]);
                }
                if (!empty($mot) && empty($categorie) && empty($arrondissement)) {
                    // rechercher3: par mot
                    $infraACM = $infrastructureRepository->chercherMot($mot);

                    return $this->render('site/resultat_recherche.html.twig', [
                        'infraACM' => $infraACM ?? [],
                        'arrondissement' => $arrondissement,
                        'categorie'      => $categorie,
                        'categories'      => $categories,
                        'mot'      => $mot,
                    ]);
                }
            }
        }

        $utilisateurConnecte = $this->getUser();

        $deuxDernier = $infrastructureRepository->findBy(["utilisateur" => $utilisateurConnecte], ['id' => "DESC"], 2);

        // Afficher les derniers ajouts d'infrastructures (requête dans CategorieRepository)
        $infrastructures = $infrastructureRepository->findBy([], ['id' => "DESC"], 4);

        // Afficher les catégories (nom picto, description)
        $categories = $categorieRepository->findAll();

        return $this->render('membre/index.html.twig', [
            'infrastructures' => $infrastructures ?? [],
            'controller_name' => 'membreController',
            'categories' => $categories,
            "deuxDernier" => $deuxDernier
        ]);

        // Afficher les derniers ajouts d'infrastructures (requête dans CategorieRepository)
        $infrastructures = $infrastructureRepository->findBy([], ['id' => "DESC"], 4);
        return $this->render('membre/index.html.twig', [
            'controller_name' => 'membreController',
            'infrastructures' => $infrastructureRepository->findAll(),
            'infrastructures' => $infrastructures ?? [],

        ]);
    }


    // page afficher toutes les infrastructures d'un utilisateur
    #[Route('/membre/mes_infrastructures', name: 'membre_mes_infrastructures', methods: ['GET'])]
    public function mesInfrastructures(Request $request, InfrastructureRepository $infrastructureRepository, CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();

        $mot = $request->get('mot');
        // dump($mot);

        $categorie = $request->get('categorie');
        // dump($categorie);

        $arrondissement = $request->get('arrondissement');
        // dump($arrondissement);


        if (!empty($mot) && !empty($categorie) && !empty($arrondissement)) {
            // rechercher7: combinaison de 3 criteres (arrondissement/categorie/mot)
            $infraACM = $infrastructureRepository->chercherMotCatArr($mot, $categorie, $arrondissement);

            // dump($infraACM);

            return $this->render('site/resultat_recherche.html.twig', [

                'infraACM' => $infraACM ?? [],
                'arrondissement' => $arrondissement,
                'categorie'      => $categorie,
                'categories'      => $categories,
                'mot'      => $mot,
            ]);
        } else {

            if (empty($mot) && !empty($categorie) && !empty($arrondissement)) {
                // rechercher6: combinaison de 2 criteres (arrondissement/categorie)
                $infraACM = $infrastructureRepository->chercherArrCat($arrondissement, $categorie);

                return $this->render('site/resultat_recherche.html.twig', [
                    'infraACM' => $infraACM ?? [],
                    'arrondissement' => $arrondissement,
                    'categorie'      => $categorie,
                    'categories'      => $categories,
                    'mot'      => $mot,
                ]);
            } else {
                if (empty($mot) && empty($categorie) && !empty($arrondissement)) {
                    // rechercher3: par arrondissement
                    $infraACM = $infrastructureRepository->chercherArrondissement($arrondissement);

                    return $this->render('site/resultat_recherche.html.twig', [
                        'infraACM' => $infraACM ?? [],
                        'arrondissement' => $arrondissement,
                        'categorie'      => $categorie,
                        'categories'      => $categories,
                        'mot'      => $mot,
                    ]);
                }

                if (empty($mot) && !empty($categorie) && empty($arrondissement)) {
                    // rechercher3: par categorie
                    $infraACM = $infrastructureRepository->chercherCategorie($categorie);

                    return $this->render('site/resultat_recherche.html.twig', [
                        'infraACM' => $infraACM ?? [],
                        'arrondissement' => $arrondissement,
                        'categorie'      => $categorie,
                        'categories'      => $categories,
                        'mot'      => $mot,
                    ]);
                }
            }

            if (!empty($mot) && empty($categorie) && !empty($arrondissement)) {
                // rechercher5: combinaison de 2 criteres (mot/arrondissement)
                $infraACM = $infrastructureRepository->chercherArrMot($arrondissement, $mot);

                return $this->render('site/resultat_recherche.html.twig', [
                    'infraACM' => $infraACM ?? [],
                    'arrondissement' => $arrondissement,
                    'categorie'      => $categorie,
                    'categories'      => $categories,
                    'mot'      => $mot,
                ]);
            } else {
                if (empty($mot) && empty($categorie) && !empty($arrondissement)) {
                    // rechercher3: par arrondissement
                    $infraACM = $infrastructureRepository->chercherArrondissement($arrondissement);

                    return $this->render('site/resultat_recherche.html.twig', [
                        'infraACM' => $infraACM ?? [],
                        'arrondissement' => $arrondissement,
                        'categorie'      => $categorie,
                        'categories'      => $categories,
                        'mot'      => $mot,
                    ]);
                }

                if (!empty($mot) && empty($categorie) && empty($arrondissement)) {
                    // rechercher3: par mot
                    $infraACM = $infrastructureRepository->chercherMot($mot);

                    return $this->render('site/resultat_recherche.html.twig', [
                        'infraACM' => $infraACM ?? [],
                        'arrondissement' => $arrondissement,
                        'categorie'      => $categorie,
                        'categories'      => $categories,
                        'mot'      => $mot,
                    ]);
                }
            }

            if (!empty($mot) && !empty($categorie) && empty($arrondissement)) {
                // rechercher4: combinaison de 2 criteres (mot/categorie)
                $infraACM = $infrastructureRepository->chercherMotCat($mot, $categorie);

                return $this->render('site/resultat_recherche.html.twig', [
                    'infraACM' => $infraACM ?? [],
                    'arrondissement' => $arrondissement,
                    'categorie'      => $categorie,
                    'categories'      => $categories,
                    'mot'      => $mot,
                ]);
            } else {
                if (empty($mot) && !empty($categorie) && empty($arrondissement)) {
                    // rechercher3: par categorie
                    $infraACM = $infrastructureRepository->chercherCategorie($categorie);

                    return $this->render('site/resultat_recherche.html.twig', [
                        'infraACM' => $infraACM ?? [],
                        'arrondissement' => $arrondissement,
                        'categorie'      => $categorie,
                        'categories'      => $categories,
                        'mot'      => $mot,
                    ]);
                }
                if (!empty($mot) && empty($categorie) && empty($arrondissement)) {
                    // rechercher3: par mot
                    $infraACM = $infrastructureRepository->chercherMot($mot);

                    return $this->render('site/resultat_recherche.html.twig', [
                        'infraACM' => $infraACM ?? [],
                        'arrondissement' => $arrondissement,
                        'categorie'      => $categorie,
                        'categories'      => $categories,
                        'mot'      => $mot,
                    ]);
                }
            }
        }


        $utilisateurConnecte = $this->getUser();

        // Affichage des boutons catégories pour le filtre de ses infras
        $categories = $categorieRepository->findAll();

        // Affichage de ses infrastructures 
        $infrastructures = $infrastructureRepository->findBy(["utilisateur" => $utilisateurConnecte], ["dateCreation" => "DESC"]);

        // Filtre par catégorie
        $filtrerCategorie = $request->get('filtrerCategorie');
        // dump($filtrerCategorie);

        if (!empty($filtrerCategorie)) {
            $mesinfrascats = $infrastructureRepository->filtrerInfraCat($filtrerCategorie, $utilisateurConnecte);
        }

        return $this->render('membre/mes_infrastructures.html.twig', [
            'controller_name' => 'MembreController',
            'infrastructures' => $infrastructures,
            'categories' => $categories,
            'filtrerCategorie' => $filtrerCategorie,
            'mesinfrascats' => $mesinfrascats ?? [],
        ]);
    }

    // page ajouter une infra
    #[Route('/membre/infrastructure/new', name: 'infrastructure_new_membre', methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $infrastructure = new Infrastructure();
        $form = $this->createForm(InfrastructureType::class, $infrastructure);
        $form->handleRequest($request);

        $utilisateurConnecte = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $infrastructure->setDateCreation(new \DateTime());
            $infrastructure->setUtilisateur($utilisateurConnecte);

            // code de gestion de upload image
            $imageFile = $form->get('image')->getData();

            // this condition is needed because the 'image' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $pathImage = $this->getParameter('images_directory');    // dossier cible;

                    $imageFile->move(
                        $pathImage,
                        $newFilename
                    );

                    $fichierImage = new TailleImage();
                    $fichierImage->redimenImage("$newFilename", $pathImage, 200);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imageFilename' property to store the PDF file name
                // instead of its contents
                $infrastructure->setImage($newFilename);
            } else {
                $infrastructure->setImage("");     // aucun fichier uploade
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($infrastructure);
            $entityManager->flush();


            return $this->redirectToRoute('membre_mes_infrastructures');
        }

        return $this->render('infrastructure/new.html.twig', [
            'infrastructure' => $infrastructure,
            'form' => $form->createView(),
            'controller_name' => 'MembreController',

        ]);
    }

    // page modifier ses infras (via l'id) A FAIRE CODE POUR N'AVOIR QUE SES INFRAS
    #[Route('/membre/infrastructure/{id}/edit', name: 'infrastructure_edit_membre', methods: ['GET', 'POST'])]
    public function edit(Request $request, Infrastructure $infrastructure, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(InfrastructureType::class, $infrastructure);
        $form->handleRequest($request);

        $utilisateurConnecte = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            // code de gestion de upload image
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $pathImage = $this->getParameter('images_directory');    // dossier cible;

                    $imageFile->move(
                        $pathImage,
                        $newFilename
                    );

                    $fichierImage = new TailleImage();
                    $fichierImage->redimenImage("$newFilename", $pathImage, 200);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // supprimer l'image d'avant
                $pathImage = $this->getParameter('images_directory');
                $fichierImage = "$pathImage/" . $infrastructure->getImage();
                if (is_file($fichierImage)) {
                    unlink($fichierImage);
                }

                $infrastructure->setImage($newFilename);
            } else {
                // on laisse l'image d'avant => on ne fait rien
            }

            $this->getDoctrine()->getManager()->flush();

            $infrastructure->setUtilisateur($utilisateurConnecte);
            return $this->redirectToRoute('membre_mes_infrastructures');
        }

        return $this->render('membre/edit.html.twig', [
            'infrastructure' => $infrastructure,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/membre/infrastructure/{id}', name: 'infrastructure_delete_membre', methods: ['DELETE'])]
    public function delete(Request $request, Infrastructure $infrastructure): Response
    {
        $utilisateurConnecte = $this->getUser();

        $infrastructure->setUtilisateur($utilisateurConnecte);

        if ($this->isCsrfTokenValid('delete' . $infrastructure->getId(), $request->request->get('_token'))) {

            $utilisateurInfra = $infrastructure->getUtilisateur();
            if ($utilisateurConnecte != null && $utilisateurInfra != null) {
                if ($utilisateurConnecte->getId() == $utilisateurInfra->getId()) {
                    // declenche le delete de la ligne
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($infrastructure);
                    $entityManager->flush();

                    $pathImage = $this->getParameter('images_directory');    // dossier cible

                    $fichierImage = "$pathImage/" . $infrastructure->getImage();
                    if (is_file($fichierImage)) {
                        // dump($fichierImage);
                        unlink($fichierImage);
                    }
                    $fichierMin = "$pathImage/200x200-" . $infrastructure->getImage();
                    if (is_file($fichierMin)) {
                        // dump($fichierImage);
                        unlink($fichierMin);
                    }
                }
            }
        }
        return $this->redirectToRoute('membre_mes_infrastructures');
    }
}
