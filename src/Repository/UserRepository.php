<?php

namespace App\Repository;

use App\Entity;
use App\Entity\User;
use App\Classe\Search;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    /**
     * Requête qui me permet de récupérer les partenaires en fonction de la recherche effectuée dans le form
     * @return User[]
     */
    public function findWithSearch(Search $search)
    {
        $query = $this
            ->createQueryBuilder('u');

        if (!empty($search->string)) {
            $query = $query
                ->andWhere('u.name LIKE :string')
                ->setParameter('string', "%{$search->string}%");
        }

        if (!empty($search->active)) {
            $query = $query
                ->andWhere('u.isActive = 1');
        }

        if (!empty($search->inactive)) {
            $query = $query
                ->andWhere('u.isActive = 0');
        }

        return $query->getQuery()->getResult();
    }
}
