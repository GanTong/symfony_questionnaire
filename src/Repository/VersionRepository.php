<?php

namespace App\Repository;

use App\Entity\Questionoptions;
use App\Entity\Questions;
use App\Entity\Version;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Version>
 *
 * @method Version|null find($id, $lockMode = null, $lockVersion = null)
 * @method Version|null findOneBy(array $criteria, array $orderBy = null)
 * @method Version[]    findAll()
 * @method Version[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VersionRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Version::class);
    }

    /**
     * @param Version $entity
     * @param bool $flush
     * @return void
     */
    public function add(Version $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param Version $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Version $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param $version_id
     * @return array
     */
    public function findQuestionnaireByVersionId($version_id): array
    {
        $qb = $this->createQueryBuilder('v')
            ->select('v.id as version_id, q.identifier as question_identifier, q.label, q.is_multichoice, qo.label')
            ->innerJoin(Questions::class, 'q', 'WITH', 'v.id = q.version_id')
            ->innerJoin(Questionoptions::class, 'qo', 'WITH', 'qo.question_identifier = q.identifier')
            ->where('v.id = :version_id')
            ->setParameter('version_id', $version_id);

        return $qb->getQuery()->getResult();
    }
}
