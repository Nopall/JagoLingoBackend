<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProductDataTable;
use App\DataTables\ProductImageDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\CreateProductImageRequest;
use App\Models\Product;
use App\Models\Commodity;
use App\Models\ProductImage;
use App\Services\ProductService;
use App\Services\UploadService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;
    protected $uploadService;

    public function __construct(ProductService $productService, UploadService $uploadService)
    {
        $this->productService = $productService;
        $this->uploadService = $uploadService;
    }

    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('product.list');
    }

    public function formCreateProduct()
    {
        $commodities = Commodity::all();
        
        return view('product.form', compact('commodities'));
    }

    public function formEditProduct(String $id)
    {
        $product = Product::find($id);
        $commodities = Commodity::all();

        if (!$product) {
            return redirect()->route('product.index')->with('error', 'Product not found.');
        }

        return view('product.form', compact('product', 'commodities'));
    }
    
    public function formCreateProductImage(String $id)
    {

        return view('product.productimageform', compact('id'));
    }
    
    
    public function imageEditProduct(String $id, ProductImageDataTable $dataTable)
    {
        $product = Product::with('images')->where('id',  $id)->first();

        if (!$product) {
            return redirect()->route('master.product.list')->with('error', 'Product not found.');
        }
        
        $dataTable->setId($id);

        return $dataTable->render('product.image', compact('id'));
    }

    public function createProduct(CreateProductRequest $request)
    {
        $data = $request->validated();

        $product = $this->productService->createProduct($data['name'], $data['description'], $data['price'], $data['weight'], $data['uom'], $data['contact'], $data['commodity_id']);

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully.',
            'data' => $product,
        ]);
    }

    public function deleteProductById(String $id)
    {
        $this->productService->deleteProductById($id);

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully.',
        ]);
    }
    

    public function updateProduct(Request $request, $id)
    {
        $data = $request->all();

        $product = $this->productService->updateProduct($id, $data['name'], $data['description'], $data['price'], $data['weight'], $data['uom'], $data['contact'], $data['commodity_id']);

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully.',
            'data' => $product,
        ]);
    }
    
    public function createProductImage(CreateProductImageRequest $request, $id)
    {
        $data = $request->validated();

        $product = $this->productService->createProductImage($id, $data['image']);

        return response()->json([
            'status' => true,
            'message' => 'Product Image created successfully.',
            'data' => $product,
        ]);
    }
    
    public function formEditProductImage(String $id, String $id_image)
    {
        $productimage = ProductImage::find($id_image);

        if (!$productimage) {
            return redirect()->route('master.product.list')->with('error', 'Product not found.');
        }
        
        $productimage->image = $this->uploadService->getPublicUrl($productimage->image);


        return view('product.productimageform', compact('productimage', 'id'));
    }
    
    public function updateProductImage(Request $request, $id)
    {
        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $icon = $request->file('image');
        } else {
            $icon = null;
        }

        $product = $this->productService->updateProductImage($id, $icon);

        return response()->json([
            'status' => true,
            'message' => 'Product Image updated successfully.',
            'data' => $product,
        ]);
    }
    
    public function deleteProductImageById(String $id)
    {
        $this->productService->deleteProductImageById($id);

        return response()->json([
            'status' => true,
            'message' => 'Product Image deleted successfully.',
        ]);
    }
}
