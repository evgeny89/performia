<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "price",
        "bedrooms",
        "bathrooms",
        "storeys",
        "garages",
    ];


    /*
     * ---------------------------------------
     * Scoped
     * ---------------------------------------
     */

    public function scopeNameFilter($query, $name = "")
    {
        return $name ? $query->where("name", "LIKE", "%{$name}%") : $query;
    }

    public function scopeMinFilter($query, $price = 0)
    {
        return $price ? $query->where("price", ">", $price) : $query;
    }
    public function scopeMaxFilter($query, $price = 0)
    {
        return $price ? $query->where("price", "<", $price) : $query;
    }

    public function scopeBedroomFilter($query, $bedrooms = 0)
    {
        return $bedrooms ? $query->whereBedrooms($bedrooms) : $query;
    }

    public function scopeBathroomFilter($query, $bathrooms = 0)
    {
        return $bathrooms ? $query->whereBathrooms($bathrooms) : $query;
    }

    public function scopeStoreyFilter($query, $storeys = 0)
    {
        return $storeys ? $query->whereStoreys($storeys) : $query;
    }

    public function scopeGarageFilter($query, $garages = 0)
    {
        return $garages ? $query->whereGarages($garages) : $query;
    }
}
