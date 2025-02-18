<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Filters\V1\ServiceFilter;
use App\Http\Requests\Api\V1\Service\StoreServiceRequest;
use App\Http\Requests\Api\V1\Service\UpdateServiceRequest;
use App\Http\Resources\V1\ServiceResource;
use App\Models\Service;

class ServiceController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(ServiceFilter $filters)
    {
        return ServiceResource::collection(Service::filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return new ServiceResource($service);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        //
    }
}
