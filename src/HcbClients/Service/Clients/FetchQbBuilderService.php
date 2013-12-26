<?php
namespace HcbClients\Service\Clients;

use HcBackend\Entity\User as UserEntity;
use HcBackend\Service\FetchQbBuilderServiceInterface;
use HcBackend\Service\Sorting\SortingServiceInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Zend\Stdlib\Parameters;

class FetchQbBuilderService implements FetchQbBuilderServiceInterface
{
    /**
     * @var SortingServiceInterface
     */
    protected $sortingService;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager,
                                SortingServiceInterface $sortingService)
    {
        $this->entityManager = $entityManager;
        $this->sortingService = $sortingService;
    }

    /**
     * @param Parameters $params
     * @return QueryBuilder
     */
    public function fetch(Parameters $params = null)
    {
        $qb = $this->entityManager
                   ->getRepository('App\Entity\User')
                   ->createQueryBuilder('u');

        $qb->where('u.role = :roleId')
           ->setParameter('roleId', UserEntity::ROLE_CLIENT);

        if (is_null($params)) return $qb;

        return $this->sortingService->apply($params, $qb, 'u');
    }
}
