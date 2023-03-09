<?php

namespace App\Repository;

use App\Entity\Infrastructure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Infrastructure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Infrastructure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Infrastructure[]    findAll()
 * @method Infrastructure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfrastructureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Infrastructure::class);
    }

    // Recherche par mot clé
    public function chercherMot($mot)
    {
        $entityManager = $this->getEntityManager();

        // ATTENTION: REQUETE EN DQL (Doctrine Query Language)
        $query = $entityManager->createQuery(
            'SELECT i
        FROM App\Entity\Infrastructure i
        WHERE i.nom LIKE :nom'
        )
            ->setParameter('nom', "%$mot%");


        return $query->getResult();
    }

    // recherche par catégorie
    public function chercherCategorie($categorie)
    {

        $entityManager = $this->getEntityManager();

        // ATTENTION: REQUETE EN DQL (Doctrine Query Language)
        $query = $entityManager->createQuery(
            'SELECT i, c
        FROM App\Entity\Infrastructure i
        INNER JOIN i.categories c
        WHERE c.nom = :categorie'
        )
            ->setParameter('categorie', $categorie);

        return $query->getResult();
    }

    // recherche par arrondissement
    public function chercherArrondissement($arrondissement)
    {
        $entityManager = $this->getEntityManager();

        // ATTENTION: REQUETE EN DQL (Doctrine Query Language)
        $query = $entityManager->createQuery(
            'SELECT i
        FROM App\Entity\Infrastructure i
        WHERE i.arrondissement = :arrondissement'
        )
            ->setParameter('arrondissement', $arrondissement);


        return $query->getResult();
    }


    // recherche par arrondissement et categorie
    public function chercherArrCat($arrondissement, $categorie)
    {
        $entityManager = $this->getEntityManager();

        // ATTENTION: REQUETE EN DQL (Doctrine Query Language)
        $query = $entityManager->createQuery(
            'SELECT i, c
        FROM App\Entity\Infrastructure i
        INNER JOIN i.categories c
        WHERE i.arrondissement = :arrondissement
        AND c.nom = :categorie'
        )
            ->setParameter('arrondissement', $arrondissement)
            ->setParameter('categorie', $categorie);

        return $query->getResult();
    }

    // recherche par arrondissement et mot
    public function chercherArrMot($arrondissement, $mot)
    {
        $entityManager = $this->getEntityManager();

        // ATTENTION: REQUETE EN DQL (Doctrine Query Language)
        $query = $entityManager->createQuery(
            'SELECT i
        FROM App\Entity\Infrastructure i
        WHERE i.arrondissement = :arrondissement
        AND i.nom LIKE :mot'
        )
            ->setParameter('arrondissement', $arrondissement)
            ->setParameter('mot', "%$mot%");

        return $query->getResult();
    }

    // recherche par mot et categorie
    public function chercherMotCat($mot, $categorie)
    {
        $entityManager = $this->getEntityManager();

        // ATTENTION: REQUETE EN DQL (Doctrine Query Language)
        $query = $entityManager->createQuery(
            'SELECT i, c
        FROM App\Entity\Infrastructure i
        INNER JOIN i.categories c
        WHERE i.nom LIKE :mot
        AND c.nom = :categorie'
        )
            ->setParameter('mot', "%$mot%")
            ->setParameter('categorie', $categorie);


        return $query->getResult();
    }

    // recherche par mot, categorie et arrondissement
    public function chercherMotCatArr($mot, $categorie, $arrondissement)
    {
        $entityManager = $this->getEntityManager();

        // ATTENTION: REQUETE EN DQL (Doctrine Query Language)
        $query = $entityManager->createQuery(
            'SELECT i, c
        FROM App\Entity\Infrastructure i
        INNER JOIN i.categories c
        WHERE i.nom LIKE :mot
        AND c.nom = :categorie
        AND i.arrondissement = :arrondissement'
        )
            ->setParameter('mot', "%$mot%")
            ->setParameter('categorie', $categorie)
            ->setParameter('arrondissement', $arrondissement);


        return $query->getResult();
    }


    // filtre mes infras par catégorie
    public function filtrerInfraCat($categorie, $utilisateurConnecte)
    {
        $entityManager = $this->getEntityManager();

        // ATTENTION: REQUETE EN DQL (Doctrine Query Language)
        $query = $entityManager->createQuery(
            'SELECT i, c
        FROM App\Entity\Infrastructure i
        INNER JOIN i.categories c
        WHERE i.utilisateur = :utilisateurConnecte
        AND c.slug = :categorie
        ORDER BY i.dateCreation DESC'
        )
            ->setParameter('categorie', $categorie)
            ->setParameter('utilisateurConnecte', $utilisateurConnecte);


        return $query->getResult();
    }
    // 4 REQUETES SPECIALES POUR STATISTIQUES INFRAS POUR L'ADMIN (TOTAL/7 JOURS/30 JOURS/ANNEE) (admin AdminController index)
    public function allInfra()
    {

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT(i.id)FROM App\Entity\Infrastructure i'
        );
        return $query->getSingleScalarResult();
    }
    public function allInfraCountSeven()
    {

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT(i.id)FROM App\Entity\Infrastructure i'
        );
        return $query->getSingleScalarResult();
    }
    public function allInfraCountThirty()
    {

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT(i.id)FROM App\Entity\Infrastructure i'
        );
        return $query->getSingleScalarResult();
    }
    public function allInfraCountOneYear()
    {

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT(i.id)FROM App\Entity\Infrastructure i'
        );
        return $query->getSingleScalarResult();
    }

    // /**
    //  * @return Infrastructure[] Returns an array of Infrastructure objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Infrastructure
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
