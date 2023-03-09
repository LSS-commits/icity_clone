<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategorieRepository;
use App\Repository\InfrastructureRepository;



class SiteController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, InfrastructureRepository $infrastructureRepository, CategorieRepository $categorieRepository): Response
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

        return $this->render('site/index.html.twig', [
            'infrastructures' => $infrastructures ?? [],
            'controller_name' => 'SiteController',
            'categories' => $categories,
        ]);
    }

    #[Route('/contact', name: 'contact', methods: ['POST', 'GET'])]
    public function contact(): Response
    {
        return $this->render('site/contact.html.twig', [
        ]);
    }
}