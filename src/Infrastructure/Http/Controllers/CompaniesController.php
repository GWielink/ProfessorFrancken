<?php

declare(strict_types=1);

namespace Francken\Infrastructure\Http\Controllers;

use Francken\Application\Career\CompanyRepository;
use Francken\Application\Career\JobOpeningRepository;
use Francken\Application\Career\JobType;
use Francken\Application\Career\Sector;

final class CompaniesController
{
    private $companies;
    private $jobs;

    public function __construct(CompanyRepository $companies, JobOpeningRepository $jobs)
    {
        $this->companies = $companies;
        $this->jobs = $jobs;
    }

    public function index()
    {
        return view('career.companies.index')
            ->with('companies', $this->companies->profiles());
    }

    public function show($slug)
    {
        $company = $this->companies->findByLink($slug);
        $jobs = $this->jobs->search(
            null, $company['name']
        );

        return view('career.companies.show')
            ->with('companies', $this->companies->profiles())
            ->with('company', $company)
            ->with('jobs', $jobs)
            ->with('sectors', Sector::SECTORS)
            ->with('types', JobType::TYPES);
    }
}
