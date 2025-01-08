<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

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
                'stock'=> $product->stock,
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
    public function filterview()
    {
        return view('x');
    }
    public function filter(Request $request)
    {


        if (($request->category === '404' || $request->category == 0) &&
            (!$request->price_min || $request->price_min == 0) &&
            (!$request->price_max || $request->price_max == 0) &&
            (!$request->quantity_min || $request->quantity_min == 0)
        ) {
            return redirect('/products-view'); // Chuyển hướng sang trang khác
        }
        // Tạo query để lọc sản phẩm


        // Validate các yêu cầu lọc
        $validator = Validator::make($request->all(), [
            'category' => 'nullable|integer|exists:categories,id',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'quantity_min' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            // Trả về lỗi nếu dữ liệu không hợp lệ
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }


        $query = Product::query();

        // Kiểm tra nếu có category thì lọc theo category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Lọc theo giá trị min/max
        if ($request->has('price_min') && $request->price_min) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->has('price_max') && $request->price_max) {
            $query->where('price', '<=', $request->price_max);
        }

        // Lọc theo số lượng tối thiểu
        if ($request->has('quantity_min') && $request->quantity_min) {
            $query->where('stock', '>=', $request->quantity_min);
        }

        // Lấy các sản phẩm với các điều kiện đã lọc
        $products = $query->with(['category', 'artist', 'images'])->get();

        // Trả về view với kết quả lọc
        return view('frontend.sanpham.filter-sanpham', ['products' => $products, 'success' => "Lọc thành công"]);
    }
}
