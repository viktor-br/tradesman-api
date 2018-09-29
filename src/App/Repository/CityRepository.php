<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, City::class);
    }

    /**
     * @param string $postcode
     * @return City[]
     */
    public function findByPostcode(string $postcode): array
    {
        return $this->findBy(['postcode' => $postcode]);
    }

    /**
     * @param string $postcode
     * @param string $city
     * @return City
     */
    public function findOneByPostcodeAndCity(string $postcode, string $city): ?City
    {
        return $this->findOneBy(['postcode' => $postcode, 'name' => $city]);
    }
}
