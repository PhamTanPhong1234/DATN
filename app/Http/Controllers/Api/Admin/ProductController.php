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
                'stock' => $product->stock,
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
        $rules = [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id', // Phải tồn tại trong bảng categories
            'artist_id' => 'nullable|exists:artists,id', // Có thể để trống, nếu không trống phải tồn tại
            'active' => 'required|boolean', // Chỉ chấp nhận true hoặc false
        ];

        // Thực hiện validate
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

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
    public function indexfilter()
    {
        
        return view('frontend.sanpham.index-sanpham');
    }
    public function filter(Request $request)
    {
        // If no filter is selected, redirect to the products-view page
        if (($request->category === 'all' || $request->category == 0) &&
            (!$request->price_min || $request->price_min == 0) &&
            (!$request->price_max || $request->price_max == 0) &&
            (!$request->quantity_min || $request->quantity_min == 0) &&
            (!$request->artist || $request->artist === 'all')
        ) {
            return redirect('/products-view'); // Redirect to another page
        }

        // Validate filter inputs
        $validator = Validator::make(
            $request->all(),
            [
                'price_min' => 'nullable|numeric|min:0',
                'price_max' => 'nullable|numeric|min:0',
                'quantity_min' => 'nullable|integer|min:0',
                'quantity_max' => 'nullable|integer|min:0',
                'artist' => 'nullable|exists:artists,id', // Validate artist exists in the artists table
            ],
            [
                'price_min.numeric' => 'Giá tối thiểu phải là một số.',
                'price_min.min' => 'Giá tối thiểu không thể nhỏ hơn 0.',
                'price_max.numeric' => 'Giá tối đa phải là một số.',
                'price_max.min' => 'Giá tối đa không thể nhỏ hơn 0.',
                'quantity_min.integer' => 'Số lượng tối thiểu phải là một số nguyên.',
                'quantity_min.min' => 'Số lượng tối thiểu không thể nhỏ hơn 0.',
                'quantity_max.integer' => 'Số lượng tối đa phải là một số nguyên.',
                'quantity_max.min' => 'Số lượng tối đa không thể nhỏ hơn 0.',
                'artist.exists' => 'Nghệ sĩ không tồn tại.',
            ]
        );

        $validator->after(function ($validator) use ($request) {
            if (!is_null($request->price_min) && !is_null($request->price_max)) {
                if ($request->price_max < $request->price_min) {
                    $validator->errors()->add('price_max', 'Giá tối đa không thể nhỏ hơn giá tối thiểu.');
                }
            }
            if (!is_null($request->quantity_min) && !is_null($request->quantity_max)) {
                if ($request->quantity_max < $request->quantity_min) {
                    $validator->errors()->add('quantity_max', 'Số lượng tối đa không thể nhỏ hơn số lượng tối thiểu.');
                }
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Initialize the query to filter products
        $query = Product::query();

        // Filter by category
        if ($request->has('category') && $request->category !== 'all' && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by price range
        if ($request->has('price_min') && $request->price_min) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->has('price_max') && $request->price_max) {
            $query->where('price', '<=', $request->price_max);
        }

        // Filter by quantity
        if ($request->has('quantity_min') && $request->quantity_min) {
            $query->where('stock', '>=', $request->quantity_min);
        }

        // Filter by artist
        if ($request->has('artist') && $request->artist !== 'all' && $request->artist) {
            // Kiểm tra xem nghệ sĩ có sản phẩm thuộc danh mục đã chọn không
            $artistExistsInCategory = Product::where('category_id', $request->category)
                ->where('artist_id', $request->artist)
                ->exists();

            if (!$artistExistsInCategory) {
                return back()->withErrors(['artist' => 'Nghệ sĩ này không có sản phẩm trong danh mục này.'])->withInput();
            }

            $query->where('artist_id', $request->artist);
        }

        // Get the filtered products
        $products = $query->with(['category', 'artist', 'images'])->get();

        // Check if products are found
        if ($products->isEmpty()) {
            return view('frontend.sanpham.filter-sanpham', ['products' => $products, 'error' => 'Không có sản phẩm nào phù hợp với bộ lọc.']);
        }

        // Return the view with filtered products
        return view('frontend.sanpham.filter-sanpham', ['products' => $products, 'success' => "Lọc thành công"]);
    }
}
