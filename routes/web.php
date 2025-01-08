<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Order;
use App\Models\Artist;
use App\Models\Message;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\OrderItems;
use App\Models\SingleNews;
use App\Events\MessageSent;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Models\ArtistImage;
use App\Models\CommentNews;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Models\Scopes\NotDeletedScope;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\VNPayController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;








Route::get('/products-filter', [ProductController::class, 'filter'])->name('products.filter');
// ===========EndPayment========
Route::get('/checkout', function () {
    $items = CartItem::with('product')
        ->where('user_id', Auth::id())
        ->get();

    $data = $items->map(function ($item) {
        $product = $item->product;
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $item->quantity,
            'total_price' => $product->price * $item->quantity,
        ];
    });
    return view('frontend.checkout', compact('data'));
})->name('checkout');


Route::get('/nghesi', function () {
    return view('frontend.nghesi.index-nghesi');
})->name('nghesi.index');


Route::get('/contact', function () {
    return view('frontend.contact');
})->name('frontend/contact');

Route::get('/about', function () {
    return view('frontend.index-about');
})->name('about');

Route::get('/wishlist', function () {})->name('wishlist');
Route::get("/single-news", function () {
    $id = request('id');
    $post_news = Post::orderBy('id', 'desc')->take(3)->get();
    if ($id) {
        $news = Post::find($id);
        if ($news) {
            $singlenews = SingleNews::where('news_id', $news->id)->get();
            return view('frontend.blog.chitietblog', ['news' => $news, 'singlenews' => $singlenews ,"post_news"=>$post_news]);
        } else {
            return "Bài viết không tồn tại!";
        }
    } else {
        return "ID không hợp lệ!";
    }
});



Route::get('/get-comments', function () {
    $news_id = request()->input('id');
    $comments = CommentNews::with(['user', 'replies.user'])
        ->where('news_id', $news_id)
        ->whereNull('parent_id') 
        ->get();
    $formattedComments = $comments->map(function ($comment) {
        return [
            'id' => $comment->id,
            'userImage' => $comment->user ? $comment->user->avatar : 'https://symbols.vn/wp-content/uploads/2021/12/Xem-them-hinh-dai-dien-avt-chibi-cho-con-trai.png',
            'username' => $comment->user ? $comment->user->name : 'Khách',
            'createdAt' => $comment->created_at ? Carbon::parse($comment->created_at)->format('d/m/Y H:i') : null,
            'content' => $comment->content,
            'replies' => $comment->replies->map(function ($reply) {
                return [
                    'id' => $reply->id,
                    'userImage' => $reply->user ? $reply->user->avatar : 'https://symbols.vn/wp-content/uploads/2021/12/Xem-them-hinh-dai-dien-avt-chibi-cho-con-trai.png',
                    'username' => $reply->user ? $reply->user->name : 'Khách',
                    'createdAt' => $reply->created_at ? Carbon::parse($reply->created_at)->format('d/m/Y H:i') : null,
                    'content' => $reply->content,
                ];
            }),
        ];
    });
    
    return response()->json([
        'comments' => $formattedComments
    ]);
})->name('get.comment.news');


Route::get('/search-result', function (Request $request) {
    $query = $request->input('query');
    if (!$query) {
        return view('frontend.search.result-search', ['products' => collect(), 'query' => '']);
    }

    $products = Product::with('images')
        ->where('name', 'like', '%' . $query . '%')
        ->get();
    return view('frontend.search.result-search', compact('products', 'query'));
})->name("search.result");
Route::get('/api/search', function (Request $request) {
    $query = $request->query('query');
    if (!$query) {
        return response()->json([], 200); 
    }
    $products = Product::with('images')->where('name', 'like', '%' . $query . '%')->get();
    if ($products->isEmpty()) {
        return response()->json([], 200);
    }
    return response()->json($products);
})->name('search.product');



Route::post('/api/users/restore/{id}', function($id) {
    $user = User::withoutGlobalScope(NotDeletedScope::class)
                ->where('id', $id)
                ->where('is_delete', 1)
                ->first();
                
    if ($user) {
        $user->is_delete = 0;
        if ($user->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Người dùng đã được khôi phục thành công!'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể cập nhật người dùng!'
            ], 500);
        }
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'Người dùng không tồn tại hoặc không bị xóa!'
        ], 404);
    }
});
Route::delete('/api/users/delete/{id}', function($id) {
    $user = User::withoutGlobalScope(NotDeletedScope::class)
                ->where('id', $id)
                ->where('is_delete', 1)
                ->first();
    if ($user) {
        $user->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Người dùng và các dữ liệu liên quan đã được xóa thành công!'
        ], 200);
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'Người dùng không tồn tại hoặc không bị xóa!'
        ], 404);
    }
});
Route::delete('/api/products/delete/{id}', function($id) {
    $user = Product::withoutGlobalScope(NotDeletedScope::class)
                ->where('id', $id)
                ->where('is_delete', 1)
                ->first();
    if ($user) {
        $user->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Người dùng và các dữ liệu liên quan đã được xóa thành công!'
        ], 200);
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'Người dùng không tồn tại hoặc không bị xóa!'
        ], 404);
    }
});






// ===============Middleware============
Route::group(['middleware' => ['role:admin'], 'prefix' => 'admin'], function () {

    // ==========Cache Index=============
    Route::get('/get-user-cache', function() {
        $user = User::withoutGlobalScope(NotDeletedScope::class)
                    ->where('is_delete', true)
                    ->get();
        return response()->json($user);
    });

    Route::get("/messages",function(){
        return view("backend.messages.index-messages");
    });
    Route::get('/categories-index-cache',function(){
        $category = Category::withoutGlobalScope(NotDeletedScope::class)
                    ->where('is_delete', true)
                    ->get();
                    return response()->json($category);
    });
    Route::get('/product-index-cache',function(){
        $category = Product::withoutGlobalScope(NotDeletedScope::class)
                    ->where('is_delete', true)
                    ->get();
                    return response()->json($category);
    });

    Route::patch('/categories-restore', function (Request $request) {
        $categoryId = $request->input('id'); 
        if (!$categoryId) {
            return response()->json(['error' => 'Category ID is required'], 400);
        }
        $category = Category::withoutGlobalScope(NotDeletedScope::class)
                            ->where('id', $categoryId)
                            ->where('is_delete', true) 
                            ->first();
    
        if (!$category) {
            return response()->json(['error' => 'Category not found or not deleted'], 404);
        }
        $category->is_delete = false; 
        $category->save();
    
        return response()->json(['message' => 'Category restored successfully'], 200);
    });

    Route::patch('/restore-product', function (Request $request) {
        $productId = $request->input('id'); 
        if (!$productId) {
            return response()->json(['error' => 'Product ID is required'], 400);
        }
        $product = Product::withoutGlobalScope(NotDeletedScope::class)
                          ->where('id', $productId)
                          ->where('is_delete', true)
                          ->first();
    
        if (!$product) {
            return response()->json(['error' => 'Product not found or not deleted'], 404);
        }
        $product->is_delete = false; 
        $product->save();
    
        return response()->json(['message' => 'Product restored successfully'], 200);
    });


    Route::get('/user/cache',function(){
       return view('backend.cache.index-cache-user');
    })->name('cache.user');;

    Route::get('/categories/cache',function(){
        return view('backend.cache.index-cache-categories');
     })->name('cache.categories');

     Route::get('/product/cache',function(){
        return view('backend.cache.index-cache-product');
     })->name('cache.product');

   
 
  


    // =====Api Liên Quan Đến Restore========
    Route::get('/dashboard', function () {
        $countuser = User::count();
        $countnews = Post::count(); 
        $countorder = Order::count(); 
        $countproduct  = Product::count(); 
        $newsuser = User::orderBy('id', 'desc')->take(5)->get(); 
        $newsorder = Order::orderBy('id', 'asc')->take(5)->get();
        $hethang = Product::where('stock', '<', 2)->count();
       
        return view('backend.dashboard', compact('countuser', 'countnews', 'countorder', 'newsuser',"countproduct","newsorder",'hethang'));
    })->name('dashboard');




    Route::get('/user', function () {
        return view('backend.user.index-user');
    })->name('user.admin');
    Route::get("/news", function () {
        return view('backend.news.news');
    });

  

    Route::get('/news-content/{id}', function($id) {
        $news = Post::find($id);

        return view("backend.news.index-news-content",compact("news"));
    })->name('news-content');
    

    Route::get('/content-index',function(Request $request){
        $id = $request->input('id');
        $content = SingleNews::where('news_id',$id)->get();
        return response()->json($content);
    })->name('content-index');

    Route::get("/news-content-create",function(){
         return response()->json(200);
    });
    Route::get('/news-content-single', function (Request $request) {
        $content = SingleNews::where('id', $request->input('id'))->first();
        if ($content) {
            return response()->json($content, 200);
        } else {
            return response()->json(['error' => 'Content not found'], 404);
        }
    });
    Route::post("/news-content-update", function (Request $request) {
        $id = $request->input('id');
        $contentText = $request->input('content');
        $image = $request->file('image');
        $content = SingleNews::find($id);
        if (!$content) {
            return response()->json(['error' => 'Content not found'], 404);
        }
        $content->content = $contentText;
        if ($image) {
            $destinationPath = public_path('images/news');
            $fileName = time() . '_' . $image->getClientOriginalName();
            $image->move($destinationPath, $fileName);
            $content->image = 'images/news/' . $fileName;
        }
        $content->save();
        return response()->json(['message' => 'Content updated successfully'], 200);
    });

    Route::post("/news-content-create", function (Request $request) {
        $contentText = $request->input('content');
        $image = $request->file('image');
        $news_id = $request->input('news_id');
        $content = new SingleNews();
        $content->content = $contentText;
        $content->news_id = $news_id;

        if ($image) {
            $destinationPath = public_path('images/news');
            $fileName = time() . '_' . $image->getClientOriginalName();
            $image->move($destinationPath, $fileName);
            $content->image = 'images/news/' . $fileName;
        }
        $content->save();
        return response()->json(['message' => 'Content created successfully'], 201);
    });
    


    Route::get("/products", function () {
        return view("backend.products.products");
    });

    Route::get('/revenue', function () {
        return view('backend.revenue.index-revenue');
    });

    //  =========Index Categories========
    Route::get("/categories", function () {
        return view("backend.categories.index-categories");
    })->name('categories.admin');
    // ===========Api Categories===========
    Route::get("/categories-index",function(){
        $categories = Category::all();
        return response()->json($categories);
    });
    Route::get("/categories-single",function(){
        $id = request()->input('id');
        $categories = Category::where('id',$id)->get();
        return response()->json($categories);
    });
    Route::post("/categories-update", function(Request $request) {
        $id = $request->input('id');
        $name = $request->input('name');
        $description = $request->input('description');
        $image = $request->file('image_path');
        if ($id) {
            $category = Category::find($id);
            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }
            $category->name = $name;
            $category->description = $description;
            if ($image) {
                $destinationPath = public_path('images/categories');
                $fileName = time() . '_' . $image->getClientOriginalName();
                $image->move($destinationPath, $fileName);
                $category->image_path = 'images/categories/' . $fileName;
            }
            $category->save();
            return response()->json(['message' => 'Category updated successfully']);
        } else {
            $category = new Category();
            $category->name = $name;
            $category->description = $description;
            if ($image) {
                $destinationPath = public_path('images/categories');
                $fileName = time() . '_' . $image->getClientOriginalName();
                $image->move($destinationPath, $fileName);
                $category->image_path = 'images/categories/' . $fileName; 
            }
            $category->save();
            return response()->json(['message' => 'Category created successfully']);
        }
    });
    
    Route::delete('/categories-remove', function () {
        $id = request()->input('id');
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'message' => 'Thể loại không tồn tại'
            ], 404);
        }
        $category->is_delete = 1;
        $category->save();  
    
        return response()->json([
            'message' => 'Xóa thành công'
        ]);
    });

    // =======Index News=========
    Route::get('/news',function(){
      return view('backend.news.index-news');
    });

    // ======Api News=========
  
    Route::get('news-index', function() {
        $news = Post::with('user')->get();    
        return response()->json($news);
    });
    
    Route::delete('/news-content-remove',function(){
        $id = request()->input('id');
        $category = SingleNews::find($id);
        if (!$category) {
            return response()->json([
                'message' => 'Thể loại không tồn tại'
            ], 404);
        }
        $category->update([
            'is_delete' => 1 
        ]);
        $category->save();
        return response()->json([
            'message' => 'Xóa thành công'
        ]);
    });

    Route::delete('/news-remove', function () {
        $id = request()->input('id');
        $category = Post::find($id);
        if (!$category) {
            return response()->json([
                'message' => 'Thể loại không tồn tại'
            ], 404);
        }
        $category->update([
            'is_delete' => 1 
        ]);
        $category->save();
        return response()->json([
            'message' => 'Xóa thành công'
        ]);
    });

    Route::get("/news-single",function(){
        $id = request()->input('id');
        $categories = Post::with('user')->where('id',$id)->get();
        return response()->json($categories);
    })->name('edit-news-single');
    
    Route::post("/news-update", function(Request $request) {
        $id = $request->input('id');
        $title = $request->input('title');
        $content = $request->input('content');
        $status = $request->input('status', 0); 
        $image = $request->file('image');
        $image_path = null;
        if ($image) {
            $destinationPath = public_path('images/news'); 
            $fileName = time() . '_' . $image->getClientOriginalName();
            $image->move($destinationPath, $fileName); 
            $image_path = 'images/news/' . $fileName;
        }
    
        if ($id) {
            $post = Post::find($id);
            if (!$post) {
                return response()->json(['message' => 'Post not found'], 404);
            }
            $post->update([
                'title' => $title,
                'content' => $content,
                'status' => $status,
                'image_path' => $image_path ?? $post->image_path, 
            ]);
    
            return response()->json(['message' => 'Post updated successfully']);
        } else {
            $post = Post::create([
                'user_id' => Auth::id(),
                'title' => $title,
                'content' => $content,
                'status' => $status,
                'image_path' => $image_path,
            ]);
            return response()->json(['message' => 'Post created successfully']);
        }
    });

    // =========Index Artist==========
    Route::get('/artist',function(){
        return view('backend.artist.index-artist');
    });

    Route::get('artist-index', function() {
        $artist = Artist::get();
        return response()->json($artist);
    });
    Route::delete('/artist-remove', function () {
        $id = request()->input('id');
        $category = Artist::find($id);
        if (!$category) {
            return response()->json([
                'message' => 'Thể loại không tồn tại'
            ], 404);
        }
    
        $category->is_delete = 1;
        $category->save();  
    
        return response()->json([
            'message' => 'Đánh dấu thành công là đã xóa'
        ]);
    });

    Route::get("/artist-single",function(){
        $id = request()->input('id');
        $categories = Artist::where('id',$id)->get();
        return response()->json($categories);
    });

    Route::post("/artist-update", function(Request $request) {
        $id = $request->input('id');
        $name = $request->input('name');
        $bio = $request->input('bio');
        $status = $request->input('status');
        $image = $request->file('image');
    
        if ($id) {
            $artist = Artist::find($id);
            if (!$artist) {
                return response()->json(['message' => 'Artist not found'], 404);
            }
            $artist->update([
                'name' => $name,
                'bio' => $bio,
                'status' => $status,
            ]);
            if ($image) {
                $destinationPath = public_path('images/artist'); 
                $fileName = time() . '_' . $image->getClientOriginalName();
                $image->move($destinationPath, $fileName); 
                $artist->image_path = 'images/artist/' . $fileName; 
            }
            $artist->save();
            return response()->json(['message' => 'Artist updated successfully']);
        } else {
            $imagePath = null;
    
            if ($image) {
                $destinationPath = public_path('images/artist'); 
                $fileName = time() . '_' . $image->getClientOriginalName();
                $image->move($destinationPath, $fileName); 
                $imagePath = 'images/artist/' . $fileName; 
            }
            // Tạo mới nghệ sĩ
            $artist = Artist::create([
                'name' => $name,
                'bio' => $bio,
                'status' => $status,
                'image_path' => $imagePath, 
            ]);
    
            return response()->json(['message' => 'Artist created successfully']);
        }
    });
    
    // =============Index Product=============
    Route::get('/products', function () {
        return view('backend.products.index-product');
    });
    // =============Api Product==============
    Route::get('product-index', function () {
        $products = Product::with(['images', 'category', 'artist'])->get();
    
        $data = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'description' => $product->description,
                'image_path' => $product->images->first()->image_path ?? null, 
                'categories' => $product->category->name ?? null, 
                'artist' => $product->artist->name ?? null,
                "is_active"=>$product->active
            ];
        });
    
        return response()->json($data);
    });
    
    Route::delete('/delete-product', function () {
        $id = request()->input('id');
        $product = Product::find($id); 
        if (!$product) {
            return response()->json([
                'message' => 'Sản phẩm không tồn tại' 
            ], 404);
        }
        $product->update(['is_delete' => 1]); 
        return response()->json([
            'message' => 'Xóa thành công'
        ]);
    });
    Route::get("/product-single", function() {
        $id = request()->input('id');
        $GetProduct = Product::with(['images', 'categories', 'artist'])->find($id);
        if (!$GetProduct) {
            return response()->json(['message' => 'Sản phẩm không tìm thấy'], 404);
        }
        $product = [
            'id' => $GetProduct->id,
            'name' => $GetProduct->name,
            'count' => $GetProduct->stock,
            'price' => $GetProduct->price,
            'description' => $GetProduct->description,
            'image_path' => $GetProduct->images->first()->image_path ?? null,
            'categories' => $GetProduct->categories->name ?? null,
            'categories_id' => $GetProduct->categories->id ?? null,
            'artist' => $GetProduct->artist->name ?? null,
            'artist_id' => $GetProduct->artist->id ?? null,
            'is_active' => $GetProduct->active
        ];
    
        return response()->json($product);
    });
 
 
    Route::get("/single-orders/{id}",function($id){
          $orders = Order::find($id);
           return response()->json($orders);
    });
    
    Route::post("/update-product", function(Request $request) {
        $id = $request->input('id');
        $name = $request->input('name');
        $count = $request->input('count');
        $price = $request->input('price');
        $description = $request->input('description');
        $status = $request->input('status', 0); 
        $categories_id = $request->input('categories');
        $artist_id = $request->input('artist');
        $imagePath = $request->file('image');
        $mp3Path = $request->file('mp3');
    
        if ($id) {
            $product = Product::find($id);
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }
            if ($mp3Path) {
                $destinationPath = public_path('audio'); 
                $fileName = time() . '_' . $mp3Path->getClientOriginalName();
                $mp3Path->move($destinationPath, $fileName); 
                $mp3_path = 'audio/' . $fileName;
            }
            $product->update([
                'name' => $name,
                'description' => $description,
                'active' => $status,
                'price' => $price,
                'stock' => $count,
                'category_id' => $categories_id ?? $product->category_id,
                'artist_id' => $artist_id ?? $product->artist_id,
                "mp3_path" => $mp3_path ?? $product->mp3_path,
            ]);
           
            if ($imagePath) {
                $destinationPath = public_path('images/Products'); 
                $fileName = time() . '_' . $imagePath->getClientOriginalName();
                $imagePath->move($destinationPath, $fileName); 
                $imagePath = 'images/Products/' . $fileName; 
    
                $product_image = ProductImage::where('product_id', $product->id)->first();
                if ($product_image) {
                    $product_image->update(['image_path' => $imagePath]);
                } else {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                    ]);
                }
            }
    
            return response()->json(['message' => 'Product updated successfully']);
        } else {
            // Tạo mới sản phẩm nếu ID không tồn tại
            $product = Product::create([
                'name' => $name,
                'description' => $description,
                'active' => $status,
                'price' => $price,
                'stock' => $count,
                'category_id' => $categories_id,
                'artist_id' => $artist_id,
            ]);
    
            if ($imagePath) {
                $destinationPath = public_path('images/Products'); 
                $fileName = time() . '_' . $imagePath->getClientOriginalName();
                $imagePath->move($destinationPath, $fileName); 
                $imagePath = 'images/Products/' . $fileName; 
    
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                ]);
            }
    
            return response()->json(['message' => 'Product created successfully']);
        }
    });
    
        //   =====Index Orders=====
    Route::get("/orders",function(){
      return view("backend.orders.index-orders");
    });
    // =======Api Orders=======
    Route::get('/orders-index', function () {
        $orders = Order::with('orderItems.product')->get();
        return response()->json($orders);
    });
    Route::delete("/orders/{id}", function($id){
        $order = Order::find($id);
        if($order) {
            $order->is_delete = 1;
            $order->save(); 
            return response()->json(['message' => 'Delete order success'], 200);
        }
        return response()->json(['message' => 'Order not found'], 404);
    });
    
    Route::post("/orders-update/{id}", function ($id) {
        $order = Order::find($id);
        if ($order) {
            $order->final_price = request()->input('total_amount') ?? $order->final_price;
            if (request()->has('status')) {
                $status = request()->input('status');
                if (!empty($status)) {
                    $order->status = $status;
                }
            }
            $order->save();
            return response()->json(['message' => 'Order updated successfully'], 200);
        }
    
        return response()->json(['message' => 'Order not found'], 404);
    });
    

    // ============Index User =============
    Route::post('/update-user', function (Request $request) {
        $user = User::find($request->id);
        
        if ($user) {
            $name = $request->input('name');
            $is_active = $request->input('is_active');
            $role = $request->input('role');
            
            $avatar = $request->file('image');
            if ($avatar) {
                $destinationPath = public_path('images/avatar');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $fileName = time() . '_' . $avatar->getClientOriginalName();
                $avatar->move($destinationPath, $fileName);
                $user->avatar = asset('images/avatar/' . $fileName);
            }
            
            $user->name = $name;
            $user->is_active = $is_active ?? $user->is_active;
            $user->role = $role  ?? $user->is_role;;
            $user->save();
            
            return response()->json(['message' => 'User updated successfully'], 200);
        }
        
        return response()->json(['message' => 'User not found'], 404);
    });
    

});

// Nhóm routes cho user
Route::group(['middleware' => ['auth']], function () {
    Route::post('/user/update', function (Request $request) {
        return "view";
    })->name('user.update');

   

    Route::get('/account', function(){
        return view('frontend.user.index-user');
    })->name('account');
});




// ===============EndMiddleware==============

//Nhóm Route Global

Route::post('/contact', [ContactController::class, 'sendContactForm'])->name('contact.submit');

// ===========Verify-email==============
Route::get('/verify-email/{id}/{token}', function ($id, $token) {
    $user = User::find($id);
    if (!$user || $user->activation_token !== $token) {
        return redirect('/login')->with('error', 'Mã kích hoạt không hợp lệ.');
    }
    $user->is_active = true;
    $user->activation_token = null;
    $user->save();

    return redirect('/login')->with('success', 'Tài khoản của bạn đã được kích hoạt. Bạn có thể đăng nhập ngay.');
})->name('account.activate');


// =================Index==============
Route::get('/', function () {
    return view('frontend.index');
})->name('index');

// ==============End=============

Route::get('/single-product', function (Request $request) {
    $id = $request->query('id');
    $product = Product::find($id);
    $images = ProductImage::where('product_id', $product->id)->get();
    if (!$product) {
        abort(404, 'Product not found');
    }

    return view('frontend.product-single', compact('product', 'images'));
})->name('single-product');
Route::get('/products-view', function () {
    return view("frontend.sanpham.index-sanpham");
})->name('sanpham.index');


// =================Checkout===========
Route::get('/vnpay/return', function (Request $request) {
    $data = $request->all();
    $vnp_TxnRef = $request->input('vnp_TxnRef');
    
    if ($vnp_TxnRef) {
        $order = Order::where('order_code', $vnp_TxnRef)->first();
        if ($order) {
            $order->update([
                'payment_status' => "Đã thanh toán",
            ]);
            return redirect()->route('history')->with('success', 'Thanh toán thành công');
        } else {
            return redirect()->route('history')->with('error', 'Thanh toán thất bại');
        }
    } else {
        return redirect()->route('history')->with('error', 'Không tìm thấy mã giao dịch');
    }
})->name('vnpay.return');

Route::post('/checkout/submit', function (Request $request) {
    try {
        $order = Order::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'city' => $request->input('city'),
            'district' => $request->input('district'),
            'commune' => $request->input('commune'),
            'note' => $request->input('note'),
            'total_price' => $request->input('total_price'),
            'shipping_fee' => $request->input('shipping_fee'),
            'final_price' => $request->input('final_price'),
            'order_code' => 'QR' . uniqid(),
            'status' => 0,
            'user_id' => Auth::id(),
            "payment_method" => $request->input('payment_method'),
        ]);
        
        $products = $request->input('products');
        if (is_array($products)) {
            foreach ($products as $product) {
                OrderItems::create([
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'product_id' => $product['id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                ]);
            }
        }

        if ($request->input('payment_method') == "atm") {
            $vnp_TmnCode = 'HAJJKVKM';
            $vnp_HashSecret = 'NQJNOXBODGJE9A3IPIJLI5J69ZIT1H68';
            $vnp_Url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
            $order_id = $order->order_code;
            $amount = $order->final_price;
            $vnp_Params = [
                'vnp_Version' => '2.1.0',
                'vnp_TmnCode' => $vnp_TmnCode,
                'vnp_Amount' => $amount * 100,
                'vnp_Command' => 'pay',
                'vnp_CurrCode' => 'VND',
                'vnp_OrderInfo' => 'Thanh toán đơn hàng ' . $order_id,
                'vnp_OrderType' => 'other',
                'vnp_ReturnUrl' => route('vnpay.return'),
                'vnp_TxnRef' => $order_id,
                'vnp_Locale' => 'vn',
                'vnp_CreateDate' => now()->format('YmdHis'),
                'vnp_IpAddr' => $request->ip(),
            ];
            ksort($vnp_Params);
            $query = http_build_query($vnp_Params);
            $secureHash = hash_hmac('sha512', $query, $vnp_HashSecret);
            $vnp_Params['vnp_SecureHash'] = $secureHash;
            $paymentUrl = $vnp_Url . '?' . http_build_query($vnp_Params);
            return response()->json([
                'success' => true, 
                'message' => 'Thành công! vui lòng đợi 2s để đến trang thanh toán',
                'paymentUrl' => $paymentUrl
            ], 200);
        }
        return response()->json([
            'success' => true, 
            'message' => 'Ghi nhận thành công vui lòng đợi 2s để đến trang lịch sử',
            'paymentUrl' => "/history"
        ], 200);
    } catch (\Exception $e) {
        Log::error($e->getMessage());
        return response()->json(['error' => 'Có lỗi xảy ra, vui lòng thử lại sau.'], 500);
    }
})->name('checkout.submit');

// ==================End Checkout=================

// =================Artist=============

Route::get('/artist', function () {
    $id = request()->query('id', 1);
    $category = Artist::find($id);
    if (!$category) {
        return abort(404, 'Danh mục không tồn tại');
    }
    $products = Product::where('artist_id', $id)->with('images')->get();
    $data = $products->map(function ($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->images->isNotEmpty() ? $product->images->first()->image_path : 'default/image',
            'rating' => 5,
            'isNew' => true,
            'old-price' => $product->price + 100
        ];
    });
    return view('frontend.showproduct', ['products' => $data, 'category' => $category]);
});

// =================End Artist============










// =======================Middleware Auth============
Route::middleware('auth')->group(function () {
    Route::get('/account', function () {
        $id = Auth::id(); 
        $user = User::with('logLogin')->find($id)->toArray();
        if (!$user) {
            abort(404, 'User not found'); 
        }
        return view('frontend.user.index-user', compact('user'));
    })->name('account');
    Route::get('/history', function () {
        $orders = Order::with('orderItems.product')->where('user_id', Auth::id())->get()->toArray();
        return view('frontend.history.index-history', compact('orders'));
    })->name('history');

});




// ================Blog=============
Route::get("/blog", function () {
    $posts = Post::with('user')->limit(8)->get();
    return view("frontend.blog.index-blog", compact("posts"));
})->name("blog.index");
Route::post('/comment/add', function (Request $request) {
    $product_id = $request->input('product_id');
    if (Auth::check()) {
        $user_id = Auth::id();
        $existingReview = ProductReview::where('user_id', $user_id)
                                      ->where('product_id', $product_id)
                                      ->first();

        if ($existingReview) {
            return response()->json(["message" => "Bạn đã gửi đánh giá cho sản phẩm này rồi."], 400);
        }

        $order_items = OrderItems::where('user_id', $user_id)
                                  ->where('product_id', $product_id)
                                  ->exists();

        if (!$order_items) {
            return response()->json(["message" => "Chưa mua sản phẩm, không thể đánh giá."], 401);
        }
        ProductReview::create([
            'comment' => $request->input('comment'),
            'rating' => $request->input('rating'),
            'user_id' => $user_id,
            'product_id' => $product_id,
        ]);
        return response()->json([
            "message" => "Đã gửi đánh giá thành công!"
        ], 200);
    }
    return response()->json([
        "message" => "Vui lòng đăng nhập để gửi đánh giá."
    ], 401);
})->name("product.comment.add");

Route::post("/save-comment", function (Request $request) {
    $keyword = ["dcm", "okem", "admin", "occho", "sex", "bitch", "spam", "hack", "fake"];
    $content = $request->input("content");
    foreach ($keyword as $badWord) {
        if (Str::contains(strtolower($content), strtolower($badWord))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nội dung bình luận chứa từ cấm!'
            ], 400);
        }
    }
    $comment = CommentNews::create([
        'user_id' => Auth::id(),
        "news_id" => $request->input('news_id'),
        'content' => $content
    ]);
return response()->json(['success' => true, 'comment' => $comment]);
});
Route::post("/save-reply", function (Request $request) {
    $comment = CommentNews::find($request->input('comment_id'));
    $keyword = ["dcm", "okem", "admin", "occho", "sex", "bitch", "spam", "hack", "fake"];
    $content = $request->input("content");
    foreach ($keyword as $badWord) {
        if (Str::contains(strtolower($content), strtolower($badWord))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nội dung bình luận chứa từ cấm!'
            ], 400);
        }
    }
    $reply = CommentNews::create([
        'news_id' => $request->input('news_id'),
        'user_id' => auth()->id(),
        'parent_id' => $request->input('comment_id'),
        'content' => $content,
    ]);
    return response()->json(['success' => true, 'reply' => $reply]);
});

Route::post('/cancel-order',function($id){
     
})->name("cancel-order");
// ==============End Blog=================



// =====================Cart================

Route::get('/api/cart', function () {
    $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();
    $data = $cartItems->map(function ($item) {
        $image = $item->product->images->first()->image_path;
        return [
            "id" => $item->id,
            'name' => $item->product->name,
            'image' => $image,
            'quantity' => $item->quantity,
            'amount' => $item->product->price * $item->quantity,
        ];
    });
    return response()->json($data);
});
Route::get("/cart",function(){
    return view("frontend.cart.index-cart");
 })->name('index.cart');

 
Route::delete("/cart/remove/{id}", function (Request $request, $id) {
    $cartItem = CartItem::find($id);
    if (!$cartItem) {
        return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng.']);
    }

    try {
        $cartItem->delete();
        return response()->json(['success' => true, 'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Đã xảy ra lỗi khi xóa sản phẩm.']);
    }
});
Route::post('/cart/update-quantity/{id}',function(Request $request,$id){
    
    $cartItem = CartItem::find($id);

    if (!$cartItem) {
        return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng.']);
    }

    if ($request->action === 'increase') {
        $cartItem->quantity += 1;
    } elseif ($request->action === 'decrease' && $cartItem->quantity > 1) {
        $cartItem->quantity -= 1;
    }

    $cartItem->save();

    return response()->json(['success' => true]);
});
Route::get('/get-cart',function(){
    $cartItems = CartItem::where('user_id', Auth::id())->with('product.images')->get();
    return response()->json(['success' => true, 'cart' => $cartItems]);
})->name('cart.data');

Route::get('/cart/remove', function () {
    $id = request()->input("id");
    $cartItem = CartItem::find($id);
    if (!$cartItem) {
        return response()->json(['message' => 'Item not found'], 404);
    }
    $cartItem->delete();
    return response()->json(['message' => 'Item removed from cart successfully']);
});



Route::post('/cart/add', function (Request $request) {
    if (!Auth::check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Vui lòng đăng nhập để mua hàng!',
        ], 401);
    }
    $user_id = Auth::id();
    $product_id = $request->input('product_id');
    $quantity = $request->input('quantity', 1);
    try {
        $cartItem = CartItem::updateOrCreate(
            ['user_id' => $user_id, 'product_id' => $product_id],
            ['quantity' => DB::raw("quantity + $quantity")]
        );
        return response()->json([
            'status' => 'success',
            'message' => 'Thêm sản phẩm vào giỏ hàng thành công!',
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Đã xảy ra lỗi khi thêm vào giỏ hàng!',
            'error' => $e->getMessage(),
        ], 500);
    }
})->name('cart.add');




// ====================End Cart=============


// ==================Update User================
Route::post('/update/user', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'userId' => 'required|exists:cdsyncs_users,id',
        'name' => 'required|string|max:255',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'phone' => 'nullable|string|max:15',
        'address' => 'nullable|string|max:255',
    ]);
    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors(),
            'message' => 'Dữ liệu không hợp lệ.'
        ], 422); 
    }

    $user = User::find($request->input("userId"));
    if ($user) {
        $name = $request->input('name');
        $avatar = $request->file('avatar');
        $phone = $request->input('phone');
        $address = $request->input('address');
        
        if ($avatar) {
            $destinationPath = public_path('images/avatar');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $fileName = time() . '_' . $avatar->getClientOriginalName();
            $avatar->move($destinationPath, $fileName);
            $user->avatar = asset('images/avatar/' . $fileName);
        }
        
        // Update other user details
        $user->name = $name;
        $user->number = $phone;
        $user->address = $address;
        $user->save();

        return response()->json(['message' => 'Thông tin người dùng đã được cập nhật thành công.'], 200); // Success
    }
    
    return response()->json(['message' => 'Người dùng không tồn tại.'], 404); // User not found
});


// =================End User================



// ================Categories==============

Route::get('/categories', function () {
    $id = request()->query('id');
    $category = Category::find($id);
    if (!$category) {
        return abort(404, 'Danh mục không tồn tại');
    }
    $products = Product::where('category_id', $id)->with('images')->get();
    $data = $products->map(function ($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->images->isNotEmpty() ? $product->images->first()->image_path : 'default/image',
            'rating' => 5,
            'isNew' => true,
            'old-price' => $product->price + 100
        ];
    });
    return view('frontend.showproduct', ['products' => $data, 'category' => $category]);
});
// =================End Categories====================

Route::get('/chat',function(){
    return view("message.chat");
  });

  Route::get("/audio",function(){
    return view("frontend.audio.mp-3");
  });

  Route::post('/send-message', function (Request $request) {
      $validated = $request->validate([
          'username' => 'required|string',
          'message' => 'required|string',
        ]);
        Message::create([
            'user_id' => Auth::id(),
            'message' => $validated['message'],
        ]);
        event(new MessageSent($validated['username'], $validated['message']));
        return response()->json(['status' => 'Message sent']);

  });
  
  
  Route::get('/fetch-messages', function () {
      $messages = Message::with('user')->latest()->get();
      return response()->json(['messages' => ["messages"]]);
  });
  Route::get('/get/messages', function () {
    $messages = Message::with('user')->select("user_id")->distinct()->get();  
    return response()->json($messages);
});

  Route::get("/api/categories/{id}/messages",function($id){
    $messages = Message::where('user_id',$id)->with('user')->get();  
    return response()->json($messages);
  });
 
  Route::get('/get-product-price', function () {
    $priceMax = Product::max("price");
    $priceMin = Product::min('price');
    
    return response()->json([
        'priceMax' => $priceMax,
        'priceMin' => $priceMin
    ]);
});
Route::post('/filter/product-price', function (Request $request) {
    $Pricemax = $request->input('Pricemax');
    $Pricemin = 0;
    $products = Product::with("images")->whereBetween('price', [$Pricemin, $Pricemax])->get();

    return response()->json([
        'status' => 'success',
        'products' => $products
    ], 200);
});

  
require __DIR__ . '/auth.php';
Route::post('/products/filter',[ProductController::class, 'filter'] )->name("products.filter");
Route::get('/products/filter-view',[ProductController::class, 'filterview'] );