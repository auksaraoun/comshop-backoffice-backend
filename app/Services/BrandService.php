<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Pagination\LengthAwarePaginator;

class BrandService
{
    public function getBrands(string $search, int $per_page, ?string $sort_by = null, ?string $sort_order = null): LengthAwarePaginator
    {
        $query = Brand::select('*');

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

    public function storeBrand(array $data): Brand
    {
        return Brand::create([
            'name' => $data['name'],
        ]);
    }

    public function updateBrand(array $data, Brand $brand): Brand
    {
        $brand->update([
            'name' => $data['name'],
        ]);

        return $brand->fresh();
    }
}
