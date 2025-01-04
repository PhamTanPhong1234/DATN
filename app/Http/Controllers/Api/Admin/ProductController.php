<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        
        $query = Product::with(['category', 'images']);

        if ($request->has('filter')) {
            $filter = $request->query('filter');
            switch ($filter) {
                case 'az':
                    $query->orderBy('name', 'asc');
                    break;
                case 'za':
                    $query->orderBy('name', 'desc');
                    break;
                case 'instock':
                    $query->where('quantity', '>', 0);
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        }

        $Products = $query->get();

        $ProductsData = $Products->map(function ($product) {
            $image = $product->images->isNotEmpty() ? $product->images->first()->image_path : null;
            return [
                'category_name' => $product->category ? $product->category->name : null,
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'description' => $product->description,
                'image' => $image,
                'image1' => $image,
                'isNew' => true,
                'rating' => random_int(1, 5),
                'oldPrice' => $product->price * 1.2,
            ];
        });

        return response()->json(['productData' => $ProductsData]);
    }

    public function show($id)
    {
        $sanPham = Product::find($id);
        if (!$sanPham) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm'], Response::HTTP_NOT_FOUND);
        }
        return response()->json(['san_pham' => $sanPham]);
    }

    public function store(Request $request)
    {
        $sanPham = Product::create($request->all());
        return response()->json($sanPham, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $sanPham = Product::find($id);
        if (!$sanPham) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm'], Response::HTTP_NOT_FOUND);
        }
        $sanPham->update($request->all());
        return response()->json($sanPham, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $sanPham = Product::find($id);
        if (!$sanPham) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm'], Response::HTTP_NOT_FOUND);
        }
        $sanPham->delete();
        return response()->json(['message' => 'Xóa sản phẩm thành công'], Response::HTTP_NO_CONTENT);
    }
    public function filter() {

    }
}
