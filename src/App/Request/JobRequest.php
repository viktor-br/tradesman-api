<?php
declare(strict_types=1);

namespace App\Request;

use JMS\Serializer\Annotation\Type;

class JobRequest
{
    /**
     * @var string
     * @Type("string")
     */
    private $title;

    /**
     * @var string
     * @Type("string")
     */
    private $postcode;

    /**
     * @var string
     * @Type("string")
     */
    private $city;

    /**
     * @var string
     * @Type("string")
     */
    private $description;

    /**
     * @var \DateTime
     * @Type("DateTime<'Y-m-d'>")
     */
    private $fulfillmentDate;

    /**
     * @var integer
     * @Type("integer")
     */
    private $serviceId;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostcode(): string
    {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     * @return $this
     */
    public function setPostcode(string $postcode)
    {
        $this->postcode = $postcode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFulfillmentDate(): \DateTime
    {
        return $this->fulfillmentDate;
    }

    /**
     * @param \DateTime $fulfillmentDate
     * @return $this
     */
    public function setFulfillmentDate(\DateTime $fulfillmentDate)
    {
        $this->fulfillmentDate = $fulfillmentDate;
        return $this;
    }

    /**
     * @return int
     */
    public function getServiceId(): int
    {
        return $this->serviceId;
    }

    /**
     * @param int $serviceId
     * @return $this
     */
    public function setServiceId(int $serviceId)
    {
        $this->serviceId = $serviceId;
        return $this;
    }
}