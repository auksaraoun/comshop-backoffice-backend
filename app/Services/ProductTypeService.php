<?php

namespace App\Services;

use App\Models\ProductType;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductTypeService
{
    public function getProductTypes(string $search, int $per_page, ?string $sort_by = null, ?string $sort_order = null): LengthAwarePaginator
    {
        $query = ProductType::select('*');

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'ILIKE', '%'.$search.'%');
            });
        }

        if ($sort_order && $sort_by) {
            $query->orderBy($sort_by, $sort_order);
        } else {
            $query->orderBy('id', 'asc');
        }

        $paginator = $query->paginate($per_page);

        return $paginator;
    }

    public function getProductTypesAll()
    {
        return ProductType::orderBy('name', 'asc')->get();
    }

    public function storeProductType(array $data, int $admin_id): ProductType
    {
        return ProductType::create([
            'name' => $data['name'],
            'admin_id' => $admin_id,
        ]);
    }

    public function updateProductType(array $data, ProductType $productType): ProductType
    {
        $productType->update([
            'name' => $data['name'],
        ]);

        return $productType->fresh();
    }
}
