<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Job;
use App\Request\JobSearchRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\ORMException;

/**
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    /**
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Job::class);
    }

    /**
     * TODO don't pass JobSearchRequest directly to repository method.
     * @param JobSearchRequest $jobSearchRequest
     * @return Job[]
     */
    public function findByFields(JobSearchRequest $jobSearchRequest): array
    {
        $criteria = new Criteria();
        $expr = Criteria::expr();

        if (count($jobSearchRequest->getCity()) > 0) {
            $criteria->andWhere(
                $expr->in('city', $jobSearchRequest->getCity())
            );
        }

        if (count($jobSearchRequest->getPostcode()) > 0) {
            $criteria->andWhere(
                $expr->in('postcode', $jobSearchRequest->getPostcode())
            );
        }

        if (count($jobSearchRequest->getServiceId()) > 0) {
            $criteria->andWhere(
                $expr->in('service', $jobSearchRequest->getServiceId())
            );
        }

        if ($jobSearchRequest->getCreatedAtMin() !== null) {
            $criteria->andWhere(
                $expr->gte(
                    'createdAt',
                    $jobSearchRequest
                        ->getCreatedAtMin()
                        ->setTime(0, 0, 0)
                )
            );
        }

        return $this->matching($criteria)->getValues();
    }

    /**
     * @param Job $job
     * @throws ORMException
     */
    public function save(Job $job): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($job);
        $entityManager->flush();
    }
}
