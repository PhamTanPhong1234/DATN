<?php

use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\Order;
use App\Models\Artist;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\ProductSale;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Models\FeaturedProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\Admin\CartController;
use App\Http\Controllers\Api\Admin\PostController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Http\Controllers\Api\Admin\ArtistController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\OrderItemController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Api\Admin\NewsCategoryController;
use App\Http\Controllers\Api\Admin\ProductReviewController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
    Route::apiResource('order-items', OrderItemController::class);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::get('/cart', [CartController::class, 'viewCart']);
    Route::put('/cart/update/{itemId}', [CartController::class, 'updateCart']);
    Route::delete('/cart/remove/{itemId}', [CartController::class, 'removeFromCart']);
});
Route::prefix('/users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/edit/{id}', [UserController::class, 'edit']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

Route::resource('products', ProductController::class);
Route::resource('news_categories', NewsCategoryController::class);
Route::apiResource('posts', PostController::class);
Route::resource('artist', ArtistController::class);
Route::apiResource('reviews', ProductReviewController::class);
Route::get('products', [ProductController::class, 'index']);


//xử lý về phần products
Route::get("/product/best/sell", function () {
    $bestSellingProducts = ProductSale::select('product_id', DB::raw('SUM(total_sales)'))
        ->groupBy('product_id')
        ->orderByDesc('total_sales')
        ->limit(4)
        ->get();
    $productIds = $bestSellingProducts->pluck('product_id');
    $Products = Product::with('categories')
        ->whereIn('id', $productIds)
        ->orderBy('id', 'desc')
        ->get();
    $ProductsData = $Products->map(function ($product) {
        $image = $product->images->isNotEmpty() ? $product->images->first()->image_path : asset('assets/images/default.jpg');
        return [
            'category' => $product->categories ? $product->categories->name : null,
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'description' => $product->description,
            'image1' => $image,
            'image2' => $image,
            'isNew' => true,
            'rating' => random_int(1, 5),
            'oldPrice' => $product->price * 1.2,
        ];
    });
    return response()->json(['ProductsData' => $ProductsData]);
});

Route::get("/product-new", function () {
    $Products = Product::with(['category', 'images'])->latest('id')->take(10)->get();
    $ProductsData = $Products->map(function ($product) {
        $image = $product->images->first()->image_path ?? asset('assets/images/default.jpg');
        return [
            'category' => $product->category->name ?? null,
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $product->stock,
            'description' => $product->description,
            'image1' => $image,
            'image2' => $image,
            'isNew' => true,
            'rating' => random_int(1, 5),
            'oldPrice' => $product->price * 1.2,
        ];
    });
    return response()->json(['ProductsData' => $ProductsData]);
})->name("product-new");

Route::get('/post', function () {
    $posts = Post::with('author')->get();
    $postData = $posts->map(function ($post) {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'author' => $post->author->name,
            'image' => $post->image ? $post->image : 'https://product.hstatic.net/1000304920/product/duc-tuan-tron-mot-kiep-yeu-lam-phuong-album-2020_7c580d7ce96a4b2c91cb833a56616659_grande.jpg',
        ];
    });
    return response()->json(['postData' => $postData]);
});





// =====================Xử lý tất cả event =============
// get nghê sĩ
Route::get('/artists', function () {
    $artists = Artist::get();
    $artistData = $artists->map(function ($artist) {
        return [
            'id' => $artist->id,
            'name' => $artist->name,
            'image' => $artist->image ? $artist->image : 'https://product.hstatic.net/1000304920/product/duc-tuan-tron-mot-kiep-yeu-lam-phuong-album-2020_7c580d7ce96a4b2c91cb833a56616659_grande.jpg',
        ];
    });
    return response()->json(['artistData' => $artistData]);
});

// get product có hot deal
Route::get('/products/hot-deals', function () {
    $products = Product::limit(5)->with('images')->get();
    $productData = $products->map(function ($product) {
        $image = $product->images->first()
            ? asset('assets/images/Products/' . $product->images->first()->image_path)
            : asset('assets/images/default.jpg');
        return [
            'id' => $product->id,
            'name' => $product->name,
            'productCount' => $product->products_count ?? 0,
            'image1' => $image,
            'image2' => $image,
        ];
    });
    return response()->json(["productData" => $productData], 200);
});

Route::get('/products/new-arrivals', function () {
    $products = Product::limit(5)->with('images')->get();
    $productData = $products->map(function ($product) {
        $image = $product->images->first()
            ? asset('assets/images/Products/' . $product->images->first()->image_path)
            : asset('assets/images/default.jpg');
        return [
            'id' => $product->id,
            'name' => $product->name,
            'productCount' => $product->products_count ?? 0,
            'image1' => $image,
            'image2' => $image
        ];
    });
    return response()->json(["productData" => $productData], 200);
});

// get product hot 
Route::get('/categories/hot', function () {
    $categories = Category::withCount('products')->get();
    $categoriesData = $categories->map(function ($category) {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'productCount' => $category->products_count,
            'image' => $category->image_path ? $category->image_path : 'https://product.hstatic.net/1000304920/product/duc-tuan-tron-mot-kiep-yeu-lam-phuong-album-2020_7c580d7ce96a4b2c91cb833a56616659_grande.jpg',
        ];
    });
    return response()->json(['categoriesData' => $categoriesData]);
});



// ========Get danh mục==========
Route::get("/categories", function () {
    $categories = Category::withCount('products')->get();
    $categoriesData = $categories->map(function ($category) {
        return [
            "id" => $category->id,
            'name' => $category->name,
            'productCount' => $category->products_count,
        ];
    });
    return response()->json(["categoriesData" => $categoriesData], 200);
});
Route::get("/product/views", function () {
    $products = FeaturedProduct::with('product')
        ->orderBy('views', 'desc')
        ->get();

    return response()->json($products);
})->name('products.views');

Route::get("/product/purchases", function () {

    $products = FeaturedProduct::with('product')
        ->orderBy('purchases', 'desc')
        ->get();

    return response()->json($products);
})->name('products.purchases');
// thêm sản phẩm
Route::post('/products/store', function (Request $request) {

    $product = Product::create([
        'name' => $request->input('name'),
        'description' => "test",
        'stock' => $request->input('quantity'),
        'price' => $request->input('price'),
        'category_id' => 1
    ]);

    if ($request->hasFile('image')) {
        $timestamp = now()->timestamp;
        $originalName = $request->file('image')->getClientOriginalName();
        $imageName = pathinfo($originalName, PATHINFO_FILENAME) . '_' . $timestamp . '.' . $request->file('image')->getClientOriginalExtension();
        $imagePath = $request->file('image')->move(public_path('assets/products'), $imageName);

        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => 'assets/products/' . $imageName,
        ]);
    }
    return response()->json([
        'success' => true,
        'message' => 'Thêm sản phẩm thành công!',
        'product' => $product,
    ], 200);
});


Route::delete('/users/{id}', function ($id) {
    try {
        $user =User::find($id);
        $user->is_delete = true; 
        $user->save();
        return response()->json(['message' => 'User deleted successfully'], 200);
    } catch (ModelNotFoundException $e) {
        return response()->json(['message' => 'User not found'], 404);
    }
});

Route::post("/users/lock", function (Request $request) {
    $users_list = $request->input('userIds');
    $updated = User::whereIn('id', $users_list)->update(['is_ban' => 1]);
    if ($updated) {
        return response()->json([
            'status' => 'success',
            'message' => 'Các tài khoản đã được khóa thành công.',
        ]);
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'Có lỗi xảy ra khi khóa tài khoản.',
        ], 500);
    }
});

Route::post("/users/unlock", function (Request $request) {
    $users_list = $request->input('userIds');
    $updated = User::whereIn('id', $users_list)->update(['is_ban' => 0]);
    if ($updated) {
        return response()->json([
            'status' => 'success',
            'message' => 'Các tài khoản đã được mở khóa thành công.',
        ]);
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'Có lỗi xảy ra khi mở khóa tài khoản.',
        ], 500);
    }
});

Route::get('/orders', function () {
    $orders = Order::with(['user'], ['items'])->get();
    return response()->json(['orders' => $orders], 200);
});
Route::get('/product-reviews/{id}', function ($id) {
    $reviews = ProductReview::with('user')
        ->where('product_id', $id)
        ->get();

    $reviews = $reviews->map(function ($review) {
        return [
            'comment' => $review->comment,
            'name' => $review->user['name'],
            'avatar' => $review->user['avatar'],
            'created_at' => date('Y-m-d H:i:s', strtotime($review->created_at)),
            'rating' => $review->rating,
        ];
    });
    return response()->json([
        'reviews' => $reviews
    ]);
})->name('product.reviews');




Route::get('/artists/famous', function () {
    $artists = Artist::withCount('products')->get();
    return response()->json($artists);
})->name("artists.famous");
