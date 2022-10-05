<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Apartment;
use Illuminate\Database\Eloquent\Collection;

class ApartmentController extends Controller
{
    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return Apartment::all();
    }

    /**
     * @param SearchRequest $request
     * @return Collection
     */
    public function search(SearchRequest $request): Collection
    {
        return Apartment::query()
            ->nameFilter($request->name)
            ->minFilter($request->min)
            ->maxFilter($request->max)
            ->bedroomFilter($request->bedrooms)
            ->bathroomFilter($request->bathrooms)
            ->storeyFilter($request->storeys)
            ->garageFilter($request->garages)
            ->get();
    }
}
