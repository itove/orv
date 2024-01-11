<?php

namespace App\Repository;

use App\Entity\Node;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Node>
 *
 * @method Node|null find($id, $lockMode = null, $lockVersion = null)
 * @method Node|null findOneBy(array $criteria, array $orderBy = null)
 * @method Node[]    findAll()
 * @method Node[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Node::class);
    }
    
    public function findByTag($tag, $limit = null, $offset = null): array
    {
        return $this->createQueryBuilder('n')
            ->join('n.tag', 't')
            ->andWhere('t.label = :tag')
            ->setParameter('tag', $tag)
            ->orderBy('n.id', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findByCategory($category, $limit = null, $offset = null): array
    {
        return $this->createQueryBuilder('n')
            ->join('n.category', 'c')
            ->andWhere('c.label = :category')
            ->setParameter('category', $category)
            ->orderBy('n.id', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findByRegion($region, $limit = null, $offset = null): array
    {
        return $this->createQueryBuilder('n')
            ->join('n.region', 'r')
            ->andWhere('r.label = :region')
            ->setParameter('region', $region)
            ->orderBy('n.id', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findByCategoryAndRegion($category, $region, $limit = null, $offset = null): array
    {
        return $this->createQueryBuilder('n')
            ->join('n.region', 'r')
            ->join('n.category', 'c')
            ->andWhere('r.label = :region')
            ->andWhere('c.label = :category')
            ->setParameter('region', $region)
            ->setParameter('category', $category)
            ->orderBy('n.id', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findByCategoryAndTag($category, $tag, $limit = null, $offset = null): array
    {
        return $this->createQueryBuilder('n')
            ->join('n.tag', 't')
            ->join('n.category', 'c')
            ->andWhere('t.label = :tag')
            ->andWhere('c.label = :category')
            ->setParameter('tag', $tag)
            ->setParameter('category', $category)
            ->orderBy('n.id', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Node[] Returns an array of Node objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Node
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
