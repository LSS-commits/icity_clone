<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof Utilisateur) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();

    }

// 4 REQUETES SPECIALES POUR STATISTIQUES UTILISATEUR POUR L'ADMIN (TOTAL/7 JOURS/30 JOURS/ANNEE) (admin AdminController index)

public function allUsers(){

    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT COUNT(u.id)FROM App\Entity\Utilisateur u');
    return $query->getSingleScalarResult();

}
public function allUsersCountSeven($dateInscriptionseven){
    
    $entityManager = $this->getEntityManager();
    $query = $entityManager->createQuery(
        "SELECT COUNT(u.id)FROM App\Entity\Utilisateur u WHERE u.dateInscription >= '$dateInscriptionseven'");
    return $query->getSingleScalarResult();
        
}
public function allUsersCountThirty($dateInscriptionthirty){

    $entityManager = $this->getEntityManager();
    $query = $entityManager->createQuery(
        "SELECT COUNT(u.id)FROM App\Entity\Utilisateur u WHERE u.dateInscription >= '$dateInscriptionthirty'");

    return $query->getSingleScalarResult();

}
public function allUsersCountOneYear($dateInscriptionOneYear){

    $entityManager = $this->getEntityManager();
    $query = $entityManager->createQuery(
        "SELECT COUNT(u.id)FROM App\Entity\Utilisateur u WHERE u.dateInscription >= '$dateInscriptionOneYear'");
        
    return $query->getSingleScalarResult();

}

    // /**
    //  * @return Utilisateur[] Returns an array of Utilisateur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Utilisateur
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
