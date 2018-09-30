<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\ServiceRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class ServiceController extends FOSRestController
{
    /**
     * @var ServiceRepository
     */
    protected $serviceRepository;

    /**
     * @param ServiceRepository $serviceRepository
     */
    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * @Rest\Get("/services")
     * @return View
     */
    public function getServices(): View
    {
        $services = $this->serviceRepository->findAll();

        return View::create($services, Response::HTTP_OK);
    }
}