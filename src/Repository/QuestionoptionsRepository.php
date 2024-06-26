<?php

namespace App\Repository;

use App\Entity\Questionoptions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Questionoptions>
 *
 * @method Questionoptions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Questionoptions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Questionoptions[]    findAll()
 * @method Questionoptions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionoptionsRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Questionoptions::class);
    }

    /**
     * @param Questionoptions $entity
     * @param bool $flush
     * @return void
     */
    public function add(Questionoptions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param Questionoptions $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Questionoptions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Questionoptions[] Returns an array of Questionoptions objects
     */
    public function findQuestionOptionsByLabelsAndQuestionIdentifier($label, $questionIdentifier): array
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.label IN (:array)')
            ->andWhere('q.question_identifier = :questionIdentifier')
            ->setParameters([
                'array' => $label,
                'questionIdentifier' => $questionIdentifier
            ])
            ->orderBy('q.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param string $choiceNumber
     * @return Questionoptions|null
     */
    public function findByChoiceNumber(string $choiceNumber): ?Questionoptions
    {
        return $this->findOneBy(['option_choice_number' => $choiceNumber]);
    }

}
