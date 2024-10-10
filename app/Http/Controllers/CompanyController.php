<?php

namespace App\Http\Controllers;

use App\Services\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index()
    {
        $companies = $this->companyService->getAllCompaniesForUser(
            Auth::id()
        );

        return response()->json($companies);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'description' => 'required|string',
        ]);

        $company = $this->companyService->createCompany(
            $request->only(['title', 'phone', 'description']),
            Auth::id()
        );

        return response()->json($company, 201);
    }
}
