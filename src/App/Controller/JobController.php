<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Job;
use App\Repository\JobRepository;
use App\Repository\ServiceRepository;
use App\Request\JobRequest;
use App\Request\JobSearchRequest;
use App\Validator\JobRequestValidator;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\ORMException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class JobController extends FOSRestController
{
    /**
     * @var JobRepository
     */
    protected $jobRepository;

    /**
     * @var ServiceRepository
     */
    protected $serviceRepository;

    /**
     * @var JobRequestValidator
     */
    protected $jobRequestValidator;

    public function __construct(
        JobRepository $jobRepository,
        ServiceRepository $serviceRepository,
        JobRequestValidator $jobRequestValidator
    ) {
        $this->jobRepository = $jobRepository;
        $this->serviceRepository = $serviceRepository;
        $this->jobRequestValidator = $jobRequestValidator;
    }

    /**
     * @Rest\Get("/job/{jobId}")
     * @param int $jobId
     * @return View
     * @throws EntityNotFoundException
     */
    public function getJob(int $jobId): View
    {
        $job = $this->jobRepository->find($jobId);

        if ($job === null) {
            throw new EntityNotFoundException(sprintf('Job id=%s not found', $jobId));
        }

        return View::create($job, Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/job")
     * @ParamConverter("jobRequest", converter="fos_rest.request_body")
     * @param JobRequest $jobRequest
     * @return View
     * @throws ORMException
     */
    public function putJob(JobRequest $jobRequest): View
    {
        $errors = $this->jobRequestValidator->validate($jobRequest);
        if (count($errors) > 0) {
            throw new \InvalidArgumentException(implode('; ', $errors));
        }

        $job = $this->createOrUpdateJobFromJobRequest(0, $jobRequest);

        return View::create($job, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/job/{jobId}")
     * @ParamConverter("jobRequest", converter="fos_rest.request_body")
     * @param int $jobId
     * @param JobRequest $jobRequest
     * @return View
     * @throws ORMException
     */
    public function postJob(int $jobId, JobRequest $jobRequest): View
    {
        $job = $this->jobRepository->find($jobId);

        if ($job === null) {
            throw new EntityNotFoundException(sprintf('Job id=%s not found', $jobId));
        }

        $errors = $this->jobRequestValidator->validate($jobRequest);
        if (count($errors) > 0) {
            throw new \InvalidArgumentException(implode('; ', $errors));
        }

        $job = $this->createOrUpdateJobFromJobRequest($jobId, $jobRequest);

        return View::create($job, Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/job/{jobId}")
     * @param int $jobId
     * @return View
     * @throws EntityNotFoundException
     * @throws ORMException
     */
    public function deleteJob(int $jobId): View
    {
        $job = $this->jobRepository->find($jobId);

        if ($job === null) {
            throw new EntityNotFoundException(sprintf('Job id=%s not found', $jobId));
        }

        $job->setIsCanceled(true);

        $this->jobRepository->save($job);

        return View::create($job, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/search/job")
     * @ParamConverter("jobSearchRequest", converter="fos_rest.request_body")
     * @param JobSearchRequest $jobSearchRequest
     * @return View
     */
    public function searchJob(JobSearchRequest $jobSearchRequest): View
    {
        // TODO don't pass JobSearchRequest directly to repository method.
        $jobs = $this->jobRepository->findByFields($jobSearchRequest);

        return View::create($jobs, Response::HTTP_OK);
    }

    /**
     * @param int $jobId
     * @param JobRequest $jobRequest
     * @return Job
     * @throws ORMException
     */
    protected function createOrUpdateJobFromJobRequest(int $jobId, JobRequest $jobRequest): Job
    {
        if ($jobId > 0) {
            $job = $this->jobRepository->find($jobId);
        } else {
            $job = new Job();
            $job->setCreatedAt(new \DateTime());
        }

        $service = $this->serviceRepository->find($jobRequest->getServiceId());
        if ($service === null) {
            throw new \InvalidArgumentException(sprintf('Service id=%s not found', $jobRequest->getServiceId()));
        }

        $job->setTitle($jobRequest->getTitle());
        $job->setPostcode($jobRequest->getPostcode());
        $job->setCity($jobRequest->getCity());
        $job->setDescription($jobRequest->getDescription());
        $job->setFulfillmentDate($jobRequest->getFulfillmentDate()->setTime(0, 0, 0));
        $job->setUpdatedAt(new \DateTime());
        $job->setService($service);

        $this->jobRepository->save($job);

        return $job;
    }
}