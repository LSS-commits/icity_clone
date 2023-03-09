<?php

namespace App\Controller;

use App\Entity\Infrastructure;
use App\Form\InfrastructureType;
use App\Repository\InfrastructureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\TailleImage\TailleImage;


#[Route('/admin/infrastructure')]
class InfrastructureController extends AbstractController
{
    #[Route('/', name: 'infrastructure_index', methods: ['GET'])]
    public function index(InfrastructureRepository $infrastructureRepository): Response
    {
        return $this->render('infrastructure/index.html.twig', [
            'infrastructures' => $infrastructureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'infrastructure_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $infrastructure = new Infrastructure();
        $form = $this->createForm(InfrastructureType::class, $infrastructure);
        $form->handleRequest($request);

        $utilisateurConnecte = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            // Completer les infos manquantes
            $infrastructure->setDateCreation(new \DateTime());
            $infrastructure->setUtilisateur($utilisateurConnecte);

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

                $infrastructure->setImage($newFilename);
            } else {
                $infrastructure->setImage("");     // aucun fichier uploade
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($infrastructure);
            $entityManager->flush();

            return $this->redirectToRoute('admin_mes_infrastructures');
        }

        return $this->render('infrastructure/new.html.twig', [
            'infrastructure' => $infrastructure,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'infrastructure_show', methods: ['GET'])]
    public function show(Infrastructure $infrastructure): Response
    {
        return $this->render('infrastructure/show.html.twig', [
            'infrastructure' => $infrastructure,
        ]);
    }

    #[Route('/{id}/edit', name: 'infrastructure_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Infrastructure $infrastructure, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(InfrastructureType::class, $infrastructure);
        $form->handleRequest($request);

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
            return $this->redirectToRoute('infrastructure_index');
        }

        return $this->render('infrastructure/edit.html.twig', [
            'infrastructure' => $infrastructure,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'infrastructure_delete', methods: ['DELETE'])]
    public function delete(Request $request, Infrastructure $infrastructure): Response
    {
        if ($this->isCsrfTokenValid('delete' . $infrastructure->getId(), $request->request->get('_token'))) {

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

        return $this->redirectToRoute('admin_mes_infrastructures');
    }
}
