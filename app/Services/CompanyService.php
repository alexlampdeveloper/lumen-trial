<?php

namespace App\Services;

use App\Models\Company;
use App\Repositories\CompanyRepository;
use Illuminate\Support\Facades\Auth;

class CompanyService
{
    protected $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function getAllCompaniesForUser(int $userId)
    {
        return $this->companyRepository->getAllForUser($userId);
    }

    public function createCompany(array $data, int $userId)
    {
        return $this->companyRepository->create($data, $userId);
    }
}
