<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\GrowBigQuickAudit;
use App\Models\GrowBigContact;
use App\Models\ServiceHeader;
use App\Models\GrowBigService;
use App\Models\GrowBigWhoWeAre;
use App\Models\ContactHeader;
use App\Models\PortfolioHeader;
use App\Models\TestimonialHeader;
use App\Models\GrowBigTestimonial;
use App\Models\TeamHeader;
use App\Models\GrowBigTeam;
use App\Models\GrowBigHero;
class FrontController extends Controller
{

/**
 * পোর্টফোলিও ডাটা লোড করার ফাংশন (AJAX)
 */
public function getPortfolioByService(Request $request)
{
    // ১. রিকোয়েস্ট থেকে আইডি এবং এটি প্যারেন্ট কিনা তা নেওয়া
    $serviceId = $request->service_id;
    $isParent = $request->is_parent; // ১ মানে প্যারেন্ট, ০ মানে চাইল্ড

    try {
        if ($isParent == 1) {
            /**
             * লজিক: যখন প্যারেন্ট সার্ভিস ট্যাবে ক্লিক করা হবে
             * তখন ঐ প্যারেন্ট সার্ভিসের আইডি এবং তার আন্ডারে থাকা সকল চাইল্ড/সাব-সার্ভিসের আইডি বের করতে হবে।
             */
            $childIds = \App\Models\GrowBigService::where('parent_id', $serviceId)
                                                ->pluck('id')
                                                ->toArray();
            
            // প্যারেন্ট আইডি এবং চাইল্ড আইডিগুলো একটি অ্যারেতে নেওয়া
            $allIds = array_merge([$serviceId], $childIds);
            
            /**
             * ডাটাবেজ থেকে ঐ সকল আইডির পোর্টফোলিও খুঁজে বের করা। 
             * এখানে service_id (প্যারেন্ট) অথবা subcategory_id (চাইল্ড) চেক করা হচ্ছে।
             */
            $portfolios = \App\Models\Portfolio::with('subcategory')
            ->whereIn('service_id', $allIds)
            ->orWhereIn('subcategory_id', $allIds)
            ->latest()->get();
        } else {
            /**
             * লজিক: যখন নির্দিষ্ট কোনো চাইল্ড (যেমন: Cinematic বা SEO) বাটনে ক্লিক করা হবে
             * তখন শুধুমাত্র ঐ নির্দিষ্ট চাইল্ড আইডির পোর্টফোলিও আসবে।
             */
            $portfolios = \App\Models\Portfolio::with('subcategory')
            ->where('service_id', $serviceId)
            ->orWhere('subcategory_id', $serviceId)
            ->latest()->get();
        }

        /**
         * ২. ডাটাগুলো একটি ছোট ব্লেড ভিউতে পাঠানো যা AJAX রেসপন্স হিসেবে যাবে।
         * নোট: আপনার যদি এই ফাইলটি না থাকে, তবে একটি তৈরি করে নিন (যেমন: ajax_list.blade.php)
         */
        return view('front.home_page.ajax_list', compact('portfolios'))->render();

    } catch (\Exception $e) {
        return response()->json(['error' => 'Something went wrong'], 500);
    }
}
public function quickAuditStore(Request $request)
{
    $validator = Validator::make($request->all(), [
        'full_name' => 'required|string|max:255',
        'service' => 'required',
        'profile_or_social_url' => 'required|url',
        'email' => 'required|email',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
    }

    try {
        GrowBigQuickAudit::create([
            'full_name' => $request->full_name,
            'service' => $request->service,
            'profile_or_social_url' => $request->profile_or_social_url,
            'email' => $request->email,
            'is_read' => 0
        ]);

        return response()->json(['status' => 'success', 'message' => 'Audit request sent successfully!']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);
    }
}

   public function contactStore(Request $request)
{
    $validator = Validator::make($request->all(), [
        'full_name'   => 'required|string|max:255',
        'email'       => 'required|email',
        'interested_in' => 'required',
        'description' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
    }

    try {
        GrowBigContact::create([
            'full_name'     => $request->full_name,
            'email'         => $request->email,
            'interested_in' => $request->interested_in,
            'description'   => $request->description,
            'is_read'       => 0
        ]);

        return response()->json(['status' => 'success', 'message' => 'Message sent successfully!']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);
    }
}


    public function aboutUs()
    {
        $systemInfo = SystemInformation::first();
       // dd($systemInfo);
        return view('front.about.about', compact('systemInfo'));
    }

    public function contactUs()
    {
        return view('front.contact.contact');
    }


    
   public function index()
{
$portfolioHeader = PortfolioHeader::first();
$testimonialHeader = TestimonialHeader::first();
    $testimonials = GrowBigTestimonial::latest()->get();
$serviceHeader = ServiceHeader::first();
    $teamHeader = TeamHeader::first();
    // socialLinks রিলেশনসহ টিম মেম্বারদের ডাটা ফেচ করা
    $teamMembers = GrowBigTeam::with('socialLinks')->get();
   $whoWeAre = GrowBigWhoWeAre::with('listItems')->first();
   $hero = GrowBigHero::first();
    return view('front.home_page.index', compact('whoWeAre','portfolioHeader', 'testimonialHeader', 'testimonials', 'serviceHeader', 'teamHeader', 'teamMembers', 'hero'));
}


public function categoryWiseCompanies(Request $request, $slug)
{
    // ১. ক্যাটাগরি চেক
    $category = Category::where('slug', $slug)
        ->where('status', 1)
        ->firstOrFail();

        //dd($category->brands());

    // ২. আলাদাভাবে ব্র্যান্ড কুয়েরি করে প্যাজিনেশন করা (১০ টা করে)
    $brands = $category->brands()
        ->where('status', 1)
        ->withCount('companyCategories')
        ->latest()
        ->paginate(10); // প্রতি পেজে ১০ টা

    // ৩. যদি AJAX রিকোয়েস্ট হয় (স্ক্রল করলে কল হবে)
    if ($request->ajax()) {
        return view('front.category.company_data', compact('brands'))->render();
    }

    // ৪. সাধারণ লোড
    return view('front.category.category_wise_companies', compact('category', 'brands'));
}

public function categoryWiseProducts(Request $request, $slug)
{
    // ১. ক্যাটাগরি চেক
    $category = Category::where('slug', $slug)
        ->where('status', 1)
        ->firstOrFail();

    // ২. আলাদাভাবে প্রোডাক্ট কুয়েরি করে প্যাজিনেশন করা (১২ টা করে)
    $products = $category->products()
        ->where('status', 1)
        ->latest()
        ->paginate(12); // প্রতি পেজে ১২ টা

    // ৩. যদি AJAX রিকোয়েস্ট হয় (স্ক্রল করলে কল হবে)
    if ($request->ajax()) {
        return view('front.category.product_data', compact('products'))->render();
    }

    // ৪. সাধারণ লোড
    return view('front.category.category_wise_products', compact('category', 'products'));
}

// প্রোডাক্ট ডিটেইলস মেথড
public function productDetails($slug)
{
    // ১. প্রোডাক্ট ফেচ করা (রিলেশন সহ)
    $product = Product::where('slug', $slug)
                    ->where('status', 1)
                    ->with(['brand', 'category', 'assigns.category']) // রিলেশন যদি লাগে
                    ->firstOrFail();

    // ২. রিলেটেড প্রোডাক্ট (একই ক্যাটাগরির অন্য প্রোডাক্ট)
    // যেহেতু আপনার মাল্টিপল ক্যাটাগরি অ্যাসাইনমেন্ট আছে, আমরা প্রাইমারি ক্যাটাগরি বা অ্যাসাইনড ক্যাটাগরি দিয়ে ফিল্টার করতে পারি
    // সিম্পল লজিক: একই ব্র্যান্ড বা একই ক্যাটাগরির প্রোডাক্ট
    $relatedProducts = Product::where('status', 1)
                            ->where('id', '!=', $product->id)
                            ->where('category_id', $product->category_id) // অথবা ব্র্যান্ড আইডি দিয়ে
                            ->take(4)
                            ->get();

    return view('front.product.product_details', compact('product', 'relatedProducts'));
}

   // ১. কোম্পানি ওয়াইজ ক্যাটাগরি পেজ (আপডেট করা হয়েছে)
    public function companyWiseCategories(Request $request, $slug)
{
    $brand = Brand::where('slug', $slug)->where('status', 1)->firstOrFail();

    // প্যাজিনেশন যোগ করা হলো (প্রতি পেজে ১২ টি)
    $categories = CompanyCategory::where('company_id', $brand->id)
                    ->whereNull('parent_id')
                    ->where('status', 1)
                    ->withCount('children')
                    ->latest()
                    ->paginate(12);

    // AJAX রিকোয়েস্ট হলে শুধু পার্শিয়াল ভিউ রিটার্ন করবে
    if ($request->ajax()) {
        return view('front.company.company_category_data', compact('categories'))->render();
    }

    $all_brands = Brand::where('status', 1)->orderBy('name', 'asc')->get();

    return view('front.company.company_wise_categories', compact('brand', 'categories', 'all_brands'));
}

    // ২. নতুন মেথড: সাব-ক্যাটাগরি পেজ
    public function companyCategorySubCategories(Request $request, $slug)
{
    // ১. প্যারেন্ট ক্যাটাগরি খুঁজে বের করা
    $parentCategory = CompanyCategory::where('slug', $slug)
        ->where('status', 1)
        ->with('company')
        ->firstOrFail();

    // ২. চাইল্ড ক্যাটাগরি (Sub Categories) ফেচ করা
    $subCategories = CompanyCategory::where('parent_id', $parentCategory->id)
        ->where('status', 1)
        ->withCount('children')
        ->latest()
        ->paginate(12);

    // ৩. AJAX রিকোয়েস্ট চেক
    if ($request->ajax()) {
        return view('front.company.company_category_sub_category_data', compact('subCategories'))->render();
    }

    // ৪. সাইডবারের জন্য সব ব্র্যান্ড (নতুন লাইন)
    $all_brands = Brand::where('status', 1)->orderBy('name', 'asc')->get();

    // ৫. ভিউ রিটার্ন (all_brands পাঠানো হলো)
    return view('front.company.company_category_sub_categories', compact('parentCategory', 'subCategories', 'all_brands'));
}

    // ৩. নতুন মেথড: প্রোডাক্ট পেজ
   public function companyCategoryProducts(Request $request, $slug)
    {
        // ১. মেইন ক্যাটাগরি (প্যারেন্ট) খুঁজে বের করা
        $category = CompanyCategory::where('slug', $slug)
            ->where('status', 1)
            ->with('children') // চাইল্ড ক্যাটাগরিগুলো ইগার লোড করা হলো
            ->firstOrFail();

        // ২. ইনপুট ভেরিয়েবল
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $filterChildCats = $request->input('child_categories', []); // চাইল্ড ক্যাটাগরি ফিল্টার অ্যারে

        // ৩. সাইডবার ডাটা (এই ক্যাটাগরির চাইল্ডগুলো)
        $sidebarChildCategories = $category->children()->where('status', 1)->get();

        // ৪. প্রোডাক্ট কুয়েরি তৈরি
        // আমরা সরাসরি রিলেশন ব্যবহার না করে ম্যানুয়ালি কুয়েরি করছি যাতে প্যারেন্ট এবং চাইল্ড উভয়ের প্রোডাক্ট আনা যায়
        $query = Product::where('status', 1)
        ->with('brand')
            ->whereHas('assigns', function($q) use ($category, $filterChildCats) {
                
                if (!empty($filterChildCats)) {
                    // যদি ফিল্টার সিলেক্ট করা থাকে, শুধু সেই চাইল্ড ক্যাটাগরির প্রোডাক্ট দেখাবে
                    $q->whereIn('category_id', $filterChildCats);
                } else {
                    // ডিফল্ট: প্যারেন্ট ক্যাটাগরি + তার সব চাইল্ড ক্যাটাগরির প্রোডাক্ট দেখাবে
                    $allCategoryIds = $category->children()->pluck('id')->push($category->id);
                    $q->whereIn('category_id', $allCategoryIds);
                }
            });

        // ৫. সার্চ ফিল্টার অ্যাপ্লাই (নাম বা কোড দিয়ে)
        $query->when($search, function ($q) use ($search) {
            $q->where(function ($subQ) use ($search) {
                $subQ->where('name', 'like', '%' . $search . '%')
                     ->orWhere('product_code', 'like', '%' . $search . '%');
            });
        });

        // ৬. ডাটা ফেচ (Pagination)
        $products = $query->latest()->paginate($perPage);

        // ৭. AJAX রিকোয়েস্ট হ্যান্ডলিং
        if ($request->ajax()) {
            return view('front.company.company_category_product_data', compact('products', 'category'))->render();
        }

        // ৮. নরমাল ভিউ রিটার্ন
        return view('front.company.company_category_products', compact('category', 'products', 'sidebarChildCategories'));
    }


public function companyWiseProducts(Request $request, $slug)
{
    // ১. ব্র্যান্ড খুঁজে বের করা
    $brand = Brand::where('slug', $slug)->where('status', 1)->firstOrFail();

    // ২. ইনপুট ভেরিয়েবল
    $search = $request->input('search');
    $perPage = $request->input('per_page', 10);
    $filterCategories = $request->input('categories', []);

    // ৩. বেস কুয়েরি (এই ব্র্যান্ডের প্রোডাক্ট)
    $query = Product::where('brand_id', $brand->id)
                    ->where('status', 1)
                    ->with('brand'); // ইমেজ লজিকের জন্য ব্র্যান্ড লোড রাখা

    // ৪. সাইডবার ফিল্টার ডাটা তৈরি
    // (এই ব্র্যান্ডের প্রোডাক্টগুলো যে যে ক্যাটাগরিতে আছে, শুধু সেগুলো সাইডবারে দেখাব)
    $existingCategoryIds = Product::where('brand_id', $brand->id)
                                ->where('status', 1)
                                ->whereNotNull('category_id')
                                ->pluck('category_id')
                                ->unique();

    $sidebarCategories = Category::whereIn('id', $existingCategoryIds)
                                 ->where('status', 1)
                                 ->get();

    // ৫. ক্যাটাগরি ফিল্টার অ্যাপ্লাই
    $query->when(!empty($filterCategories), function ($q) use ($filterCategories) {
        $q->whereIn('category_id', $filterCategories);
    });

    // ৬. সার্চ ফিল্টার অ্যাপ্লাই
    $query->when($search, function ($q) use ($search) {
        $q->where(function ($subQ) use ($search) {
            $subQ->where('name', 'like', '%' . $search . '%')
                 ->orWhere('product_code', 'like', '%' . $search . '%');
        });
    });

    // ৭. প্যাজিনেশন
    $products = $query->latest()->paginate($perPage);

    // ৮. AJAX রেসপন্স
    if ($request->ajax()) {
        return view('front.company.company_wise_product_data', compact('products'))->render();
    }

    // ৯. মেইন ভিউ
    return view('front.company.company_wise_products', compact('brand', 'products', 'sidebarCategories'));
}

// ১. প্রোডাক্ট কার্টে যোগ করা
    public function addToCart(Request $request)
    {
        $id = $request->product_id;
        $qty = $request->quantity ?? 1;
        $product = Product::with('brand')->find($id);

        if(!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found']);
        }

        $cart = session()->get('cart', []);

        // যদি প্রোডাক্ট অলরেডি থাকে, তাহলে কোয়ান্টিটি বাড়বে
        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $qty;
        } else {
            // ইমেজ লজিক
            $images = $product->thumbnail_image; 
            if (is_array($images) && count($images) > 0) {
                $image = $images[0];
            } elseif ($product->brand && $product->brand->logo) {
                $image = $product->brand->logo;
            } else {
                $image = 'no-image.png'; 
            }

            $cart[$id] = [
                "id" => $product->id,
                "name" => $product->name,
                "quantity" => $qty,
                "image" => $image,
                "code" => $product->product_code
            ];
        }

        session()->put('cart', $cart);
        
        return response()->json([
            'status' => 'success', 
            'message' => 'Added to quote list!', 
            'total_items' => count($cart)
        ]);
    }

    // ২. কার্টের বর্তমান কন্টেন্ট লোড করা (AJAX এর জন্য)
    public function getCartContent()
    {
        $cart = session()->get('cart', []);
        // এই ভিউ ফাইলটি আমরা পরের ধাপে তৈরি করব
        return view('front.include.cart_content', compact('cart'))->render();
    }

    // ৩. কার্টের কোয়ান্টিটি আপডেট করা
    public function updateCartQty(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            return response()->json(['status' => 'success']);
        }
    }

    // ৪. কার্ট থেকে রিমুভ করা
    public function removeFromCart(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return response()->json(['status' => 'success']);
        }
    }

    // 1. Submit Quote Method
public function submitQuote(Request $request)
{
    $cart = session()->get('cart');

    if (!$cart || count($cart) == 0) {
        return response()->json(['status' => 'error', 'message' => 'Cart is empty!']);
    }

    if (!Auth::check()) {
        return response()->json(['status' => 'auth_error', 'message' => 'Please login first']);
    }

    DB::beginTransaction();
    try {
        $user = Auth::user();
        
        // Calculate initial subtotal (Admin can change this later)
        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += ($item['quantity'] * 0); // Assuming 0 price for Quote, or use $item['price']
        }

        // 1. Create Order
        $order = new Order();
        $order->customer_id = $user->customer ? $user->customer->id : null; // Ensure User has customer relation
        // If no customer relation exists, create one or handle null
        
        $order->invoice_no = 'INV-' . strtoupper(uniqid()); // Generate Invoice No
        $order->delivery_type = 'regular'; // Default
        $order->subtotal = $subtotal;
        $order->total_amount = $subtotal; // Admin will update this
        $order->due = $subtotal;
        $order->status = 'pending'; // YOUR STATUS: pending
        $order->shipping_address = $user->customer->address ?? $user->address ?? 'N/A';
        $order->billing_address = $user->customer->address ?? $user->address ?? 'N/A';
        $order->payment_status = 'unpaid';
        $order->order_from = 'web'; // To identify it came from website
        $order->save();

        // 2. Create Order Details
        foreach ($cart as $id => $details) {
            $orderDetail = new OrderDetail();
            $orderDetail->order_id = $order->id;
            $orderDetail->product_id = $details['id'];
            $orderDetail->quantity = $details['quantity'];
            $orderDetail->unit_price = 0; // Quote request, so 0 or base price
            $orderDetail->subtotal = 0;
            $orderDetail->delivery_status = 'pending';
            $orderDetail->save();
        }

        // 3. Clear Cart
        session()->forget('cart');

        DB::commit();

        return response()->json([
            'status' => 'success', 
            'message' => 'Quote request sent successfully! Please wait for Admin approval.'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
}

// 2. Get Order Details for Modal (AJAX)
// এই ফাংশনটি আপডেট করুন
public function getOrderDetailsHtml($id)
{
    // ১. অর্ডার ডাটা আনা (Product, Brand এবং Category রিলেশন সহ)
    $order = Order::with(['orderDetails.product.brand', 'orderDetails.product.category'])
                  ->where('id', $id)
                  ->where('customer_id', Auth::user()->customer->id ?? 0)
                  ->firstOrFail();
    
    // ২. মোডালের টেবিল ডিজাইন তৈরি
    $html = '<div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Company Name</th>
                            <th>Product Category</th>
                            <th>Product Name</th>
                        </tr>
                    </thead>
                    <tbody>';
    
    foreach($order->orderDetails as $detail) {
        $product = $detail->product;
        
        // কোম্পানি নাম (ব্র্যান্ড মডেল থেকে)
        $companyName = $product && $product->brand ? $product->brand->name : 'N/A';
        
        // ক্যাটাগরি নাম
        $categoryName = $product && $product->category ? $product->category->name : 'N/A';
        
        // প্রোডাক্ট নাম
        $productName = $product ? $product->name : 'Unknown Product';

        $html .= '<tr>
                    <td class="fw-bold">'. $companyName .'</td>
                    <td><span class="badge bg-secondary">'. $categoryName .'</span></td>
                    <td>'. $productName .'</td>
                  </tr>';
    }

    $html .= '</tbody></table></div>';

    // ৩. ইনফরমেশন অ্যালার্ট বক্স (আপনার ইমেজ অনুযায়ী)
    $html .= '<div class="alert alert-info mt-4" role="alert" style="background-color: #e0f7fa; border-color: #b2ebf2; color: #006064;">
                <h6 class="alert-heading fw-bold mb-2"><i class="bi bi-info-circle-fill me-2"></i>To get Quote:</h6>
                <p class="mb-0 small">
                    Wait for the Product Quotation or contact this <strong>+1 01111111</strong> number.<br>
                    Thank you for connecting with us. We look forward to serving you.
                </p>
              </div>';

    return $html;
}

// এই নতুন মেথডটি যোগ করুন
public function getQuoteDetailsHtml($id)
{
    // ১. অর্ডার ডাটা আনা
    $order = Order::with(['orderDetails.product.brand', 'orderDetails.product.category'])
                  ->where('id', $id)
                  ->where('customer_id', Auth::user()->customer->id ?? 0)
                  ->firstOrFail();

    // ২. মোডাল টেবিল (Price কলাম সহ)
    $html = '<div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Company Name</th>
                            <th>Product Category</th>
                            <th>Product Name</th>
                            <th>Price</th> 
                        </tr>
                    </thead>
                    <tbody>';
    
    foreach($order->orderDetails as $detail) {
        $product = $detail->product;
        $companyName = $product && $product->brand ? $product->brand->name : 'N/A';
        $categoryName = $product && $product->category ? $product->category->name : 'N/A';
        $productName = $product ? $product->name : 'Unknown Product';
        
        // প্রাইস ফরম্যাটিং (Accepted অর্ডারের জন্য)
        $price = number_format($detail->unit_price, 2); 

        $html .= '<tr>
                    <td class="fw-bold">'. $companyName .'</td>
                    <td><span class="badge bg-secondary">'. $categoryName .'</span></td>
                    <td>'. $productName .'</td>
                    <td class="text-success fw-bold">$'. $price .'</td>
                  </tr>';
    }

    $html .= '</tbody></table></div>';

    // ৩. ইনফরমেশন অ্যালার্ট বক্স (আপনার Capturaae.PNG ইমেজ অনুযায়ী)
    $html .= '<div class="alert alert-info mt-4" role="alert" style="background-color: #e0f7fa; border-color: #b2ebf2; color: #006064;">
                <h6 class="alert-heading fw-bold mb-2"><i class="bi bi-info-circle-fill me-2"></i>To place an order:</h6>
                <p class="mb-0 small">
                    If you agree with the quoted price or have any questions, please contact us at <strong>+1 01111111</strong> to confirm your order.<br>
                    Thank you for connecting with us. We look forward to serving you.
                </p>
              </div>';

    return $html;
}

// PDF Print Route
    public function orderPrint($id)
    {
        // 1. Get Logged-in User
        $user = Auth::user();

        // 2. Find the associated Customer ID
        $customerId = $user->customer_id ?? ($user->customer ? $user->customer->id : null);

        if (!$customerId) {
            abort(403, 'No customer account associated with this user.');
        }

        // 3. Query Order
        $order = Order::where('id', $id)
                      ->where('customer_id', $customerId)
                      ->with(['orderDetails.product', 'customer'])
                      ->firstOrFail();

        // 4. Fetch Company Info (This fixes the Undefined variable error)
        $companyInfo = SystemInformation::first(); 

        // 5. Return view with both variables
        return view('admin.order.print_a4', compact('order', 'companyInfo'));
    }
}
