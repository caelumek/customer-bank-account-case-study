<?php

namespace App\Controller;

use App\Dto\BankAccountCreateDto;
use App\Dto\BankAccountUpdateBalanceDto;
use App\Dto\BankAccountUpdateDto;
use App\Repository\BankAccountRepository;
use App\Resources\BankAccountBaseResource;
use App\Resources\BankAccountCreatedResource;
use App\Resources\BankAccountDetailsResource;
use App\Resources\BankAccountUpdatedBalanceResource;
use App\Resources\BankAccountUpdatedResource;
use App\Service\BankAccount\CreateBankAccountService;
use App\Service\BankAccount\UpdateBankAccountBalanceService;
use App\Service\BankAccount\UpdateBankAccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

final class BankAccountController extends AbstractController
{
    #[Route('/bank-accounts', name: 'bank_accounts_index', methods: ['GET'])]
    public function index(BankAccountRepository $bankAccountRepository): JsonResponse {
        $bankAccounts = $bankAccountRepository->findAllAsResource(BankAccountBaseResource::class);

        return $this->json($bankAccounts->toArray());
    }

    #[Route('/bank-accounts', name: 'bank_accounts_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] BankAccountCreateDto $bankAccountCreateDto,
        CreateBankAccountService $createBankAccountService,
        BankAccountRepository $bankAccountRepository
    ): JsonResponse {
        $bankAccountUuid = $createBankAccountService->create($bankAccountCreateDto);
        $bankAccount = $bankAccountRepository->findByUuidAsResource(
            $bankAccountUuid,
            BankAccountCreatedResource::class
        );

        return $this->json($bankAccount, JsonResponse::HTTP_CREATED);
    }

    #[Route('/bank-accounts/{bankAccountUuid}', name: 'bank_accounts_update', methods: ['PATCH'])]
    public function update(
        #[MapRequestPayload] BankAccountUpdateDto $bankAccountUpdateDto,
        Uuid $bankAccountUuid,
        UpdateBankAccountService $updateBankAccountService,
        BankAccountRepository $bankAccountRepository
    ): JsonResponse {
        $updateBankAccountService->update($bankAccountUuid, $bankAccountUpdateDto);
        $bankAccount = $bankAccountRepository->findByUuidAsResource(
            $bankAccountUuid,
            BankAccountUpdatedResource::class
        );

        return $this->json($bankAccount, JsonResponse::HTTP_OK);
    }

    #[Route('/bank-accounts/{bankAccountUuid}/balance', name: 'bank_accounts_update_balance', methods: ['PATCH'])]
    public function updateBalance(
        #[MapRequestPayload] BankAccountUpdateBalanceDto $bankAccountUpdateBalanceDto,
        Uuid $bankAccountUuid,
        UpdateBankAccountBalanceService $updateBankAccountBalanceService,
        BankAccountRepository $bankAccountRepository
    ): JsonResponse {
        $updateBankAccountBalanceService->updateBalance($bankAccountUuid, $bankAccountUpdateBalanceDto);
        $bankAccount = $bankAccountRepository->findByUuidAsResource(
            $bankAccountUuid,
            BankAccountUpdatedBalanceResource::class
        );

        return $this->json($bankAccount, JsonResponse::HTTP_OK);
    }

    #[Route('/bank-accounts/{bankAccountUuid}', name: 'bank_accounts_show', methods: ['GET'])]
    public function show(
        Uuid $bankAccountUuid,
        BankAccountRepository $bankAccountRepository
    ): JsonResponse {
        $bankAccount = $bankAccountRepository->findByUuidAsResource(
            $bankAccountUuid,
            BankAccountDetailsResource::class
        );

        return $this->json($bankAccount, JsonResponse::HTTP_OK);
    }
}
