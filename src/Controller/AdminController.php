<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\InfrastructureRepository;
use App\Repository\CategorieRepository;
use App\Repository\UtilisateurRepository;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(Request $request, InfrastructureRepository $infrastructureRepository, CategorieRepository $categorieRepository, UtilisateurRepository $utilisateurRepository): Response
    {
        // Afficher les catégories (nom picto, description)
        $categories = $categorieRepository->findAll();

        // récupérer le mot recherché
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

        // Afficher les derniers ajouts d'infrastructures (requête dans CategorieRepository)
        $infrastructures = $infrastructureRepository->findBy([], ['id' => "DESC"], 4);

        // Affichage derniere Infras admin
        $utilisateurConnecte = $this->getUser();

        $deuxDernier = $infrastructureRepository->findBy(["utilisateur" => $utilisateurConnecte], ['id' => "DESC"], 2);

        // Pour Afficher le nombre d'utilisateur: le nombre total , 7j
        $nombreDeUserTotal = $utilisateurRepository->allUsers();

        $dateInscriptionseven = date('Y-m-d H:i:s', strtotime("-1 week"));
        $nombreDeUserTotalSeven = $utilisateurRepository->allUsersCountSeven($dateInscriptionseven);
        // Les 30j
        $dateInscriptionthirty = date('Y-m-d H:i:s', strtotime("-1 month"));
        $nombreDeUserTotalthirty = $utilisateurRepository->allUsersCountThirty($dateInscriptionthirty);

        // 1 année 
        $dateInscriptionOneYear = date('Y-m-d H:i:s', strtotime("-1 year"));
        $nombreDeUserTotalYear = $utilisateurRepository->allUsersCountOneYear($dateInscriptionOneYear);

        // Total infrastructure
        $nombreDeInfrastructures = $infrastructureRepository->allInfra();

        //7 Jour
        $dateInfrastructureSeven = date('Y-m-d H:i:s', strtotime("-1 week"));
        $nombreDeInfrastructuresTotalSeven = $infrastructureRepository->allinfraCountSeven($dateInfrastructureSeven);

        //30 jour 
        $dateInfrastructureThirty = date('Y-m-d H:i:s', strtotime("-1 month"));
        $nombreDeInfrastructuresTotalThirty = $infrastructureRepository->allInfraCountThirty($dateInfrastructureThirty);
       
        // Année
        $dateInfrastructureOneYear = date('Y-m-d H:i:s', strtotime("-1 year"));
        $nombreDeInfrastructuresTotalOneYear = $infrastructureRepository->allInfraCountThirty($dateInfrastructureOneYear);

       
        // On appelle dans le twig
        return $this->render('admin/index.html.twig', [
            'totalusers' => $nombreDeUserTotal ?? 0,
            'totalusersseven' => $nombreDeUserTotalSeven,
            'totalusersthirty' => $nombreDeUserTotalthirty,
            'totalusersoneyear' => $nombreDeUserTotalYear,
            'totalinfra' => $nombreDeInfrastructures,
            'totalinfraseven' => $nombreDeInfrastructuresTotalSeven,
            'totalinfrathirty' => $nombreDeInfrastructuresTotalThirty,
            'totalinfraoneyear' => $nombreDeInfrastructuresTotalOneYear,
            'deuxDernier' => $deuxDernier,
            'infrastructures' => $infrastructures ??  [],
            'controller_name' => 'AdminController',
            'categories' => $categories

        ]);
    }

    #[Route('/admin/mes_infrastructures', name: 'admin_mes_infrastructures', methods: ['GET'])]
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

        return $this->render('admin/mes_infrastructures.html.twig', [
            'controller_name' => 'AdminController',
            'infrastructures' => $infrastructures,
            'categories' => $categories,
            'filtrerCategorie' => $filtrerCategorie,
            'mesinfrascats' => $mesinfrascats ?? [],
        ]);
    }
}
