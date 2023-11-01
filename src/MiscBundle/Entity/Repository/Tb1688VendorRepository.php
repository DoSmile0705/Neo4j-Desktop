<?php

namespace MiscBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Tb1688VendorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class Tb1688VendorRepository extends BaseRepository
{
  /**
   * @param array $conditions
   * @param array $orders
   * @param int $page
   * @param int $limit
   * @return \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
   */
  public function findVendorList($conditions = [], $orders = [], $page = 1, $limit = 100)
  {
    $qb = $this->createQueryBuilder('v');
    $qb->addOrderBy('v.id', 'ASC');

    /** @var \Knp\Component\Pager\Paginator $paginator */
    $paginator  = $this->getContainer()->get('knp_paginator');
    /** @var \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination $pagination */
    $pagination = $paginator->paginate(
        $qb->getQuery() /* query NOT result */
      , $page
      , $limit
    );

    return $pagination;
  }

}