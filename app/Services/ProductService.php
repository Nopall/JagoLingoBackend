<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;

class ProductService
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function createProduct(string $name, string $description, string $price, string $weight, string $uom, string $contact, string $commodity)
    {

        $product = Product::create([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'weight' => $weight,
            'uom' => $uom,
            'contact' => $contact,
            'commodity_id' => $commodity
        ]);

        return $product;
    }

    public function deleteProductById($id)
    {
        $product = Product::where('id', $id);
        $product->delete();
    }
    

    public function updateProduct($id, $name, $description, $price, $weight, $uom, $contact, $commodity)
    {
        $product = Product::findOrFail($id);
        $product->name = $name;
        $product->description = $description;
        $product->price = $price;
        $product->weight = $weight;
        $product->uom = $uom;
        $product->contact = $contact;
        $product->commodity_id = $commodity;


        $product->save();

        return $product;
    }
}
