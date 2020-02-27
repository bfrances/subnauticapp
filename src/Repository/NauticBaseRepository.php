<?php

namespace App\Repository;

use App\Entity\NauticBase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;

/**
 * @method null|NauticBase find($id, $lockMode = null, $lockVersion = null)
 * @method null|NauticBase findOneBy(array $criteria, array $orderBy = null)
 * @method NauticBase[]    findAll()
 * @method NauticBase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NauticBaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NauticBase::class);
    }

    public function findByPagination($page, $nbByPage)
    {
        $qb = $this->createQueryBuilder('a');

        $query = $qb->getQuery();

        $premierResultat = ($page - 1) * $nbByPage;
        $query->setFirstResult($premierResultat)->setMaxResults($nbByPage);
        $paginator = new Paginator($query);

        if (($paginator->count() <= $premierResultat) && 1 != $page) {
            throw new Exception('Page not found');
        }

        return $paginator;
    }
}
