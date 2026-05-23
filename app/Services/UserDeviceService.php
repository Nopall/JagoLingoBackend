<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\UploadedFile;

class UserDeviceService
{
    public function __construct()
    {
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

    public function deleteUserDeviceById($id)
    {
        $product = UserDevice::where('id', $id);
        $product->delete();
    }
    

    public function updateUserDevice($id, $user_id, $description, $price, $weight, $uom, $contact, $commodity)
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
