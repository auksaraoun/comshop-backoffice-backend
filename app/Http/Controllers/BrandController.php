<?php

namespace App\Http\Controllers;

use App\Http\Requests\Brand\IndexBrandRequest;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Services\BrandService;
use App\Utils\ApiResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class BrandController extends Controller
{
    public function __construct(
        private ApiResponse $response,
        private BrandService $brandService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(IndexBrandRequest $request): JsonResponse
    {
        $validate = $request->validated();
        $search = $validate['search'] ?? '';
        $per_page = $validate['per_page'] ?? 30;
        $sort_by = $validate['sort_by'] ?? null;
        $sort_order = $validate['sort_order'] ?? null;
        $brands = $this->brandService->getBrands($search, $per_page, $sort_by, $sort_order);

        return $this->response->success(
            BrandResource::collection($brands),
            'Fetch Brands success',
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request): JsonResponse
    {
        $data = $request->validated();
        $brand = $this->brandService->storeBrand($data);

        return $this->response->success(
            new BrandResource($brand),
            'Store Brand success',
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand): JsonResponse
    {
        return $this->response->success(
            new BrandResource($brand),
            'Fetch Brand success',
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand): JsonResponse
    {
        $data = $request->validated();
        $brand = $this->brandService->updateBrand($data, $brand);

        return $this->response->success(
            new BrandResource($brand),
            'Update Brand success'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand): JsonResponse
    {
        $brand->delete();

        return $this->response->success(
            null,
            'Delete Brand success',
        );
    }
}
