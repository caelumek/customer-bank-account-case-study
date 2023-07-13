<?php

namespace App\Controller;

use App\Dto\CustomerCreateDto;
use App\Dto\CustomerUpdateDto;
use App\Repository\CustomerRepository;
use App\Resources\CustomerBaseResource;
use App\Resources\CustomerCreatedResource;
use App\Resources\CustomerDetailsResource;
use App\Resources\CustomerUpdatedResource;
use App\Service\Customer\CreateCustomerService;
use App\Service\Customer\UpdateCustomerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

final class CustomerController extends AbstractController
{
    #[Route('/customers', name: 'customers_index', methods: ['GET'])]
    public function index(CustomerRepository $customerRepository): JsonResponse
    {
        $customers = $customerRepository->findAllAsResource(CustomerBaseResource::class);

        return $this->json($customers->toArray());
    }

    #[Route('/customers', name: 'customers_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CustomerCreateDto $customerCreateDto,
        CreateCustomerService $createCustomerService,
        CustomerRepository $customerRepository
    ): JsonResponse {
        $customerUuid = $createCustomerService->create($customerCreateDto);
        $customer = $customerRepository->findByUuidAsResource($customerUuid, CustomerCreatedResource::class);

        return $this->json($customer, JsonResponse::HTTP_CREATED);
    }

    #[Route('/customers/{customerUuid}', name: 'customers_update', methods: ['PATCH'])]
    public function update(
        #[MapRequestPayload] CustomerUpdateDto $customerUpdateDto,
        Uuid $customerUuid,
        UpdateCustomerService $updateCustomerService,
        CustomerRepository $customerRepository
    ): JsonResponse {
        $updateCustomerService->update($customerUuid, $customerUpdateDto);
        $customer = $customerRepository->findByUuidAsResource($customerUuid, CustomerUpdatedResource::class);

        return $this->json($customer, JsonResponse::HTTP_OK);
    }

    #[Route('/customers/{customerUuid}', name: 'customers_show', methods: ['GET'])]
    public function show(
        Uuid $customerUuid,
        CustomerRepository $customerRepository
    ): JsonResponse {
        $customer = $customerRepository->findByUuidAsResource($customerUuid, CustomerDetailsResource::class);

        return $this->json($customer, JsonResponse::HTTP_OK);
    }
}
