<?php

namespace App\Repositories;

use App\Models\Company;
use Illuminate\Support\Collection;

class CompanyRepository
{
    public function getAllForUser(int $userId): Collection
    {
        return Company::where('user_id', $userId)->get();
    }

    public function create(array $data, int $userId): Company
    {
        $company = new Company($data);
        $company->user_id = $userId;
        $company->save();

        return $company;
    }
}
