<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductType\IndexProductTypeRequest;
use App\Http\Requests\ProductType\StoreProductTypeRequest;
use App\Http\Requests\ProductType\UpdateProductTypeRequest;
use App\Http\Resources\ProductTypeResource;
use App\Models\ProductType;
use App\Services\ProductTypeService;
use App\Utils\ApiResponse;
use Illuminate\Http\JsonResponse;

class ProductTypeController extends Controller
{
    public function __construct(
        private ApiResponse $response,
        private ProductTypeService $productTypeService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(IndexProductTypeRequest $request): JsonResponse
    {
        $validate = $request->validated();
        $search = $validate['search'] ?? '';
        $per_page = $validate['per_page'] ?? 30;
        $sort_by = $validate['sort_by'] ?? null;
        $sort_order = $validate['sort_order'] ?? null;
        $productTypes = $this->productTypeService->getProductTypes($search, $per_page, $sort_by, $sort_order);

        return $this->response->success(
            ProductTypeResource::collection($productTypes),
            'Fetch Product Types success',
        );
    }

    public function getDataAll()
    {
        $productTypes = $this->productTypeService->getProductTypesAll();

        return $this->response->success(
            ProductTypeResource::collection($productTypes),
            'Fetch Product Types success',
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductTypeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $productType = $this->productTypeService->storeProductType($data, $request->user()->id);

        return $this->response->success(
            new ProductTypeResource($productType),
            'Store Product Type success',
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductType $productType): JsonResponse
    {
        return $this->response->success(
            new ProductTypeResource($productType),
            'Fetch Product Type success',
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductTypeRequest $request, ProductType $productType): JsonResponse
    {
        $data = $request->validated();
        $productType = $this->productTypeService->updateProductType($data, $productType);

        return $this->response->success(
            new ProductTypeResource($productType),
            'Update Product type success'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductType $productType): JsonResponse
    {
        $productType->delete();

        return $this->response->success(
            null,
            'Delete Product type success',
        );
    }
}
