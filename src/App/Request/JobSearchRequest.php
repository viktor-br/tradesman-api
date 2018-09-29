<?php
declare(strict_types=1);

namespace App\Request;

use JMS\Serializer\Annotation\Type;

class JobSearchRequest
{
    /**
     * @var int[]
     * @Type("array<integer>")
     */
    private $serviceId = [];

    /**
     * @var string[]
     * @Type("array<string>")
     */
    private $postcode = [];

    /**
     * @var string[]
     * @Type("array<string>")
     */
    private $city = [];

    /**
     * @var \DateTime
     * @Type("DateTime<'Y-m-d'>")
     */
    private $createdAtMin;

    /**
     * @var int
     * @Type("integer")
     */
    private $offset = 0;

    /**
     * @var int
     * @Type("integer")
     */
    private $limit = 10;

    /**
     * @return int[]
     */
    public function getServiceId(): array
    {
        return $this->serviceId;
    }

    /**
     * @param int[] $serviceId
     * @return $this
     */
    public function setServiceId(array $serviceId)
    {
        $this->serviceId = $serviceId;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getPostcode(): array
    {
        return $this->postcode;
    }

    /**
     * @param string[] $postcode
     * @return $this
     */
    public function setPostcode(array $postcode)
    {
        $this->postcode = $postcode;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getCity(): array
    {
        return $this->city;
    }

    /**
     * @param string[] $city
     * @return $this
     */
    public function setCity(array $city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAtMin(): ?\DateTime
    {
        return $this->createdAtMin;
    }

    /**
     * @param \DateTime $createdAtMin
     * @return $this
     */
    public function setCreatedAtMin(\DateTime $createdAtMin)
    {
        $this->createdAtMin = $createdAtMin;
        return $this;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function setOffset(int $offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }
}