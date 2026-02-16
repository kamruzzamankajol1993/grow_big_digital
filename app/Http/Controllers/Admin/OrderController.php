<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use Mpdf\Mpdf;
use App\Models\OrderTracking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Traits\StockManagementTrait;
use Illuminate\Support\Facades\Validator;
class OrderController extends Controller
{

     use StockManagementTrait;


     /**
     * Quickly store a new customer via AJAX from the order page.
     */
    public function quickStoreCustomer(Request $request)
    {
        // --- START: MODIFICATION ---
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|digits:11|unique:customers,phone',
            'secondary_phone' => 'nullable|string|digits:11|unique:customers,secondary_phone', // Added validation
            'address' => 'required|string|max:255',
        ]);
        // --- END: MODIFICATION ---

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $customer = null;
            $address = null;

            DB::transaction(function () use ($request, &$customer, &$address) {
                // --- START: MODIFICATION ---
                // Create the customer
                $customer = Customer::create([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'secondary_phone' => $request->secondary_phone, // Added field
                    'type' => 'normal', // Default type
                    'status' => 1,
                ]);
                // --- END: MODIFICATION ---

                // Create the address
                $address = $customer->addresses()->create([
                    'address' => $request->address,
                    'address_type' => 'Home', // As requested
                    'is_default' => true,      // As requested
                ]);
            });

            // Re-fetch customer to get the `address` accessor populated
            $customer->load('addresses');

            return response()->json([
                'message' => 'Customer created successfully!',
                'customer' => $customer,
                'address' => $address, // Send the new address
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error in quickStoreCustomer: ' . $e->getMessage());
            return response()->json(['errors' => ['server' => 'An internal error occurred. Please try again.']], 500);
        }
    }

    public function printA5(Order $order)
{
    try {
    $order->load('customer', 'orderDetails.product', 'payments');
    $companyInfo = DB::table('system_information')->first(); // Fetch company info
    $pdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A5']);
    $html = view('admin.order.print_a4', compact('order', 'companyInfo'))->render();
    $pdf->WriteHTML($html);
    return $pdf->Output('invoice-'.$order->invoice_no.'.pdf', 'I');
     } catch (\Exception $e) {
            Log::error('Error generating A5 PDF: ' . $e->getMessage());
            return response('Could not generate PDF.', 500);
        }
}

 // --- START: MODIFICATION ---
    public function searchCustomers(Request $request)
{
    try {
        $term = $request->get('term');
        
        $query = Customer::query();

        if (empty($term)) {
            $query->latest()->limit(5);
        } else {
            $query->where('name', 'LIKE', '%' . $term . '%')
                  ->orWhere('phone', 'LIKE', '%' . $term . '%')
                  ->limit(10);
        }
        
        // এখানে নির্দিষ্ট কলামগুলো সিলেক্ট করুন, বিশেষ করে discount_in_percent এবং type
        $customers = $query->get(['id', 'name', 'phone', 'type', 'discount_in_percent']);
        
        return response()->json($customers);

    } catch (\Exception $e) {
        Log::error('Error searching customers: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred during search.'], 500);
    }
}
    // --- END: MODIFICATION ---
    public function index()
    {
        try {
        // Get counts for each status tab
        $statusCounts = Order::select('status', DB::raw('count(*) as total'))
                             ->groupBy('status')
                             ->pluck('total', 'status');
        
        // Calculate the 'all' count
        $statusCounts['all'] = $statusCounts->sum();

        return view('admin.order.index', compact('statusCounts'));
        } catch (\Exception $e) {
            Log::error('Error loading order index page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not load the order page.');
        }
    }

    public function data(Request $request)
    {
        try {
        $query = Order::with('customer');

        // Filter by status tab
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Handle specific filters
        if ($request->filled('order_id')) {
            $query->where('invoice_no', 'like', '%' . $request->order_id . '%');
        }

        if ($request->filled('customer_name')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer_name . '%')
                  ->orWhere('phone', 'like', '%' . $request->customer_name . '%');
            });
        }
        
        // New: Filter by Product Name or Code
        if ($request->filled('product_info')) {
            $query->whereHas('orderDetails.product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->product_info . '%')
                  ->orWhere('product_code', 'like', '%' . $request->product_info . '%');
            });
        }


        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween(DB::raw('DATE(created_at)'), [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->latest()->paginate(10);

        return response()->json([
            'data' => $orders->items(),
            'total' => $orders->total(),
            'current_page' => $orders->currentPage(),
            'last_page' => $orders->lastPage(),
        ]);
        } catch (\Exception $e) {
            Log::error('Error fetching order data: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch order data.'], 500);
        }
    }

     public function create()
    {
        try {
        // Generate a unique invoice number
        $newInvoiceId = 'INV-' .mt_rand(1000, 9999);
        
        // Fetch customers for the dropdown
        $customers = Customer::where('status', 1)->get(['id', 'name', 'phone']);

        return view('admin.order.create', compact('newInvoiceId', 'customers'));
        } catch (\Exception $e) {
            Log::error('Error loading create order page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not load the new order page.');
        }
    }

     // AJAX method to get customer details
    public function getCustomerDetails($id)
    {
                try {

        $customer = Customer::with('addresses')->findOrFail($id);
        return response()->json([
            'main_address' => $customer->address,
            'addresses' => $customer->addresses,
        ]);
        } catch (\Exception $e) {
            Log::error('Error fetching customer details: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch customer details.'], 500);
        }
    }

     // AJAX method for product search
    // AJAX method for product search
   public function searchProducts(Request $request)
{
    try {
    $term = $request->get('term');

    $products = Product::where('name', 'LIKE','%' . $term . '%')
        ->orWhere('product_code', 'LIKE', '%' . $term . '%')
        ->limit(10)
        ->get();

    // We need to format the results for the jQuery UI Autocomplete plugin.
    // The frontend expects objects with 'label' and 'value' keys.
    // We also include the 'id' so we can use it when a product is selected.
    $formattedProducts = $products->map(function($product) {
        
        // --- START: MODIFICATION ---
        // Find a valid image URL. Prioritize thumbnail_image.
        $imageUrl = asset('backend/images/placeholder.jpg'); // Set a default placeholder
        
        if (is_array($product->thumbnail_image) && !empty($product->thumbnail_image[0])) {
            $imageUrl = asset('public/uploads/'.$product->thumbnail_image[0]);
        } elseif (is_array($product->main_image) && !empty($product->main_image[0])) {
            $imageUrl = asset('public/uploads/'.$product->main_image[0]); // Fallback to main_image
        }
        // --- END: MODIFICATION ---


        return [
            'id' => $product->id, // We'll need this to fetch details later
            'label' => $product->name . ' (' . $product->product_code . ')', // Text to display in the list
            'value' => $product->name, // Text to place in the input field on select
            'image_url' => $imageUrl // --- ADDED ---
        ];
    });

    return response()->json($formattedProducts);
    } catch (\Exception $e) {
            Log::error('Error searching products: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while searching for products.'], 500);
        }
}

    public function getProductDetails($id)
    {
        try {
        $product = Product::with('variants.color')->findOrFail($id);
        
        $variantsData = $product->variants->map(function ($variant) {
            $sizes = collect($variant->sizes)->map(function ($sizeInfo) {
                $sizeModel = Size::find($sizeInfo['size_id']);
                return [
                    'id' => $sizeInfo['size_id'],
                    'name' => $sizeModel ? $sizeModel->name : 'N/A',
                    'additional_price' => $sizeInfo['additional_price'] ?? 0, 
                      'quantity' => $sizeInfo['quantity'] ?? 0,
                ];
            });

            return [
                'variant_id' => $variant->id,
                'color_id' => $variant->color->id,
                'color_name' => $variant->color->name,
                'sizes' => $sizes,
            ];
        });

        return response()->json([
            'base_price' => $product->discount_price ?? $product->base_price,
            'variants' => $variantsData,
        ]);
        } catch (\Exception $e) {
            Log::error('Error fetching product details: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch product details.'], 500);
        }
    }

    public function store(Request $request)
{

    try {
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'invoice_no' => 'required|string|unique:orders,invoice_no',
        'order_date' => 'required|date_format:d-m-Y', // Validate the date field
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'discount_value' => 'nullable|numeric|min:0',
    ]);

    DB::transaction(function () use ($request) {
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'invoice_no' => $request->invoice_no,
            'subtotal' => $request->subtotal,
            'discount' => $request->discount,
            'discount_type' => $request->discount_type, // 'fixed' or 'percent'
            'discount_value' => $request->discount_value,
            'shipping_cost' => $request->shipping_cost,
            'total_amount' => $request->total_amount,
            'total_pay' => $request->total_pay,
            'cod' => $request->cod,
            'due' => $request->total_amount - $request->total_pay,
            'shipping_address' => $request->shipping_address,
            'payment_term' => $request->payment_term,
            'order_from' => $request->order_from,
            'notes' => $request->notes,
            'status' => 'pending',
            // Save the order_date, converting it for the database
            'order_date' => Carbon::createFromFormat('d-m-Y', $request->order_date)->format('Y-m-d'),
        ]);

        foreach ($request->items as $item) {
            $amount = $item['quantity'] * $item['unit_price'];
            $after_discount = $amount - ($item['discount'] ?? 0);

            $order->orderDetails()->create([
                'product_id' => $item['product_id'],
                'product_variant_id' => null, // Set to null as requested
                'size' => $item['size'],
                'color' => $item['color'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $amount,
                'discount' => $item['discount'] ?? 0,
                'after_discount_price' => $after_discount,
            ]);
        }
    });

    return redirect()->route('order.index')->with('success', 'Order created successfully.');
    } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating order: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the order.')->withInput();
        }
}

     /**
     * MODIFIED: updateStatus
     * This method now includes logic to adjust stock based on status transitions.
     */
    public function updateStatus(Request $request, Order $order)
    {
        try {
            $request->validate(['status' => 'required|string']);

            // --- UPDATED STATUS LOGIC ---
            $nonDeductingStatuses = ['pending', 'waiting'];
            $deductingStatuses = ['accepted']; // Deduct stock when Accepted
            $returnStockStatuses = ['cancelled']; // Return stock when Cancelled

            $oldStatus = strtolower($order->status);
            $newStatus = strtolower($request->status);

            if ($oldStatus === $newStatus) {
                return response()->json(['message' => 'Order status is already set to ' . $newStatus . '.']);
            }

            DB::transaction(function () use ($request, $order, $oldStatus, $newStatus, $nonDeductingStatuses, $deductingStatuses, $returnStockStatuses) {
                $order->load('orderDetails');

          

                $order->update(['status' => $newStatus]);

              
            });

            return response()->json(['message' => 'Order status updated successfully.']);
        } catch (\Exception $e) {
            Log::error('Error updating order status: ' . $e->getMessage());
            return response()->json(['error' => 'Could not update order status.'], 500);
        }
    }

    public function bulkUpdateStatus(Request $request)
    {
        try {
            $request->validate([
                'ids'    => 'required|array',
                'ids.*'  => 'exists:orders,id',
                'status' => 'required|string',
            ]);

            $orderIds = $request->ids;
            $newStatus = strtolower($request->status);

            // --- UPDATED STATUS LOGIC ---
            $nonDeductingStatuses = ['pending', 'waiting'];
            $deductingStatuses = ['accepted'];
            $returnStockStatuses = ['cancelled'];

            DB::transaction(function () use ($orderIds, $newStatus, $nonDeductingStatuses, $deductingStatuses, $returnStockStatuses) {
                $ordersToUpdate = Order::whereIn('id', $orderIds)->with('orderDetails')->get();

                foreach ($ordersToUpdate as $order) {
                    $oldStatus = strtolower($order->status);

                  
                   

                    $order->update(['status' => $newStatus]);
                }
            });

            return response()->json(['message' => 'Selected orders have been updated.']);
        } catch (\Exception $e) {
            Log::error('Error during bulk status update: ' . $e->getMessage());
            return response()->json(['error' => 'Could not update selected orders.'], 500);
        }
    }
    /**
     * Fetch details for the order detail modal.
     */
    public function getDetails($id)
    {
         try {
        $order = Order::with('customer', 'orderDetails.product')->findOrFail($id);
        return response()->json($order);
        } catch (\Exception $e) {
            Log::error('Error fetching order details for modal: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch order details.'], 500);
        }
    }

     public function destroy(Order $order)
    {
        try {
            $order->delete();
            return redirect()->route('order.index')->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting order: ' . $e->getMessage());
            return redirect()->route('order.index')->with('error', 'Could not delete the order.');
        }
    }
    
    /**
     * Destroy multiple orders at once.
     */
    /**
     * Destroy multiple orders at once.
     */
    public function destroyMultiple(Request $request)
    {
         try {
            $request->validate(['ids' => 'required|array']);
            Order::whereIn('id', $request->ids)->delete();

            // Recalculate all status counts after deletion
            $statusCounts = Order::select('status', DB::raw('count(*) as total'))
                                 ->groupBy('status')
                                 ->pluck('total', 'status');
            $statusCounts['all'] = $statusCounts->sum();

            return response()->json([
                'message' => 'Selected orders have been deleted.',
                'statusCounts' => $statusCounts // Send new counts back
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting multiple orders: ' . $e->getMessage());
            return response()->json(['error' => 'Could not delete the selected orders.'], 500);
        }
    }

    /**
 * Show the form for editing the specified order.
 */
public function edit(Order $order)
    {
        try {
            // Eager load the relationships to prevent too many database queries in the view
            $order->load('customer', 'orderDetails.product');

            // --- START: MODIFICATION ---
            // Prepare product variation details for JavaScript initialization
            $productDetailsJs = [];
            foreach ($order->orderDetails as $detail) {
                $product = $detail->product;
                if ($product) {
                    // Eager load variants for this specific product to be efficient
                    $product->load('variants.color');

                    $variantsData = $product->variants->map(function ($variant) {
                        $sizes = collect($variant->sizes)->map(function ($sizeInfo) {
                            $sizeModel = Size::find($sizeInfo['size_id']);
                            return [
                                'id' => $sizeInfo['size_id'],
                                'name' => $sizeModel ? $sizeModel->name : 'N/A',
                                'additional_price' => $sizeInfo['additional_price'] ?? 0,
                                'quantity' => $sizeInfo['quantity'] ?? 0,
                            ];
                        });

                        return [
                            'variant_id' => $variant->id,
                            'color_id' => $variant->color->id,
                            'color_name' => $variant->color->name,
                            'sizes' => $sizes,
                        ];
                    });

                    $productDetailsJs[$product->id] = [
                        'base_price' => $product->discount_price ?? $product->base_price,
                        'variants' => $variantsData,
                    ];
                }
            }
            // --- END: MODIFICATION ---

            return view('admin.order.edit', compact('order', 'productDetailsJs'));

        } catch (\Exception $e) {
            Log::error('Error loading edit order page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not load the order for editing.');
        }
    }

/**
 * Update the specified order in storage.
 */
public function update(Request $request, Order $order)
{
    try {
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        // Make sure the invoice number is unique, but ignore the current order's ID
        'invoice_no' => 'required|string|unique:orders,invoice_no,' . $order->id,
        'order_date' => 'required|date_format:d-m-Y',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'discount_value' => 'nullable|numeric|min:0',
    ]);

    DB::transaction(function () use ($request, $order) {
        // 1. Update the main order fields
        $order->update([
            'customer_id' => $request->customer_id,
            'invoice_no' => $request->invoice_no,
            'subtotal' => $request->subtotal,
            'discount' => $request->discount,
            'discount_type' => $request->discount_type, // 'fixed' or 'percent'
                'discount_value' => $request->discount_value,
            'shipping_cost' => $request->shipping_cost,
            'total_amount' => $request->total_amount,
            'total_pay' => $request->total_pay,
            'cod' => $request->cod,
            'due' => $request->total_amount - $request->total_pay,
            'shipping_address' => $request->shipping_address,
            'payment_term' => $request->payment_term,
            'order_from' => $request->order_from,
            'notes' => $request->notes,
            'status' => $request->status ?? 'pending', // You can add a status dropdown if needed
            'order_date' => Carbon::createFromFormat('d-m-Y', $request->order_date)->format('Y-m-d'),
        ]);

        // 2. Sync the order details. This is the cleanest way to handle changes.
        // It deletes the old items and creates new ones from the submitted form data.
        $order->orderDetails()->delete();

        foreach ($request->items as $item) {
            $amount = ($item['quantity'] ?? 1) * ($item['unit_price'] ?? 0);
            $after_discount = $amount - ($item['discount'] ?? 0);

            $order->orderDetails()->create([
                'product_id' => $item['product_id'],
                'product_variant_id' => null,
                'size' => $item['size'],
                'color' => $item['color'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $amount,
                'discount' => $item['discount'] ?? 0,
                'after_discount_price' => $after_discount,
            ]);
        }
    });

    return redirect()->route('order.index')->with('success', 'Order updated successfully.');

     } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating order: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the order.')->withInput();
        }
}


public function show(Order $order)
{
     try {
    $order->load('customer', 'orderDetails.product', 'payments');
    $companyInfo = DB::table('system_information')->first(); // Fetch company info
    return view('admin.order.show', compact('order', 'companyInfo'));
    } catch (\Exception $e) {
            Log::error('Error showing order: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not display the order details.');
        }
}

// ...

/**
 * Generate and stream an A4 PDF invoice.
 */
public function printA4(Order $order)
{
    try {
        $order->load('customer', 'orderDetails.product', 'payments');
        $companyInfo = DB::table('system_information')->first();

        // 1. Get default mPDF font configurations
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        // 2. Initialize mPDF
        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            // Add your custom font directory
            'fontDir' => array_merge($fontDirs, [
                public_path('fonts'),
            ]),
            // Register the font
            'fontdata' => $fontData + [
                'nikosh' => [
                    'R' => 'Nikosh.ttf', // Must match your filename in public/fonts/
                    'useOTL' => 0xFF,    // Required for correct Bangla rendering
                    'useKashida' => 75,
                ]
            ],
            // FORCE this font for the entire document
            'default_font' => 'nikosh' 
        ]);

        $html = view('admin.order.print_a4', compact('order', 'companyInfo'))->render();
        $pdf->WriteHTML($html);

        return $pdf->Output('invoice-'.$order->invoice_no.'.pdf', 'I');

    } catch (\Exception $e) {
        Log::error('Error generating A4 PDF: ' . $e->getMessage());
        return response('Could not generate PDF.', 500);
    }
}

/**
 * Generate and stream a POS receipt PDF.
 */
public function printPOS(Order $order)
{
    try {
        $order->load('customer', 'orderDetails.product', 'payments');
        $companyInfo = DB::table('system_information')->first(); 

        // 1. Get default mPDF font configurations
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        // 2. Initialize mPDF with POS size and Nikosh font
        $pdf = new Mpdf([
            'mode' => 'utf-8', 
            'format' => [75, 100], // Your specific POS paper size
            'fontDir' => array_merge($fontDirs, [
                public_path('fonts'),
            ]),
            'fontdata' => $fontData + [
                'nikosh' => [
                    'R' => 'Nikosh.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ]
            ],
            'default_font' => 'nikosh' // Forces Bangla support globally
        ]);

        $html = view('admin.order.print_pos', compact('order', 'companyInfo'))->render();
        $pdf->WriteHTML($html);
        return $pdf->Output('receipt-'.$order->invoice_no.'.pdf', 'I');

    } catch (\Exception $e) {
        Log::error('Error generating POS PDF: ' . $e->getMessage());
        return response('Could not generate PDF.', 500);
    }
}

/**
     * Store a new payment for an order.
     */
    public function storePayment(Request $request, Order $order)
    {
         try {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $order->due,
            'payment_date' => 'required|date_format:d-m-Y',
            'payment_method' => 'required|string',
        ]);

        DB::transaction(function () use ($request, $order) {
            $order->payments()->create([
                'amount' => $request->amount,
                'payment_date' => Carbon::createFromFormat('d-m-Y', $request->payment_date)->format('Y-m-d'),
                'payment_method' => $request->payment_method,
                'note' => $request->note,
            ]);

            // Update the order's payment status
            $order->total_pay += $request->amount;
            $order->due -= $request->amount;
            $order->save();
        });

        return redirect()->route('order.show', $order->id)->with('success', 'Payment added successfully.');
    
    } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error storing payment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while adding the payment.')->withInput();
        }
    }

   public function updateStatusWithPrices(Request $request, $id)
{
    // ১. ভ্যালিডেশন
    $request->validate([
        'status' => 'required|string',
        'prices' => 'array', // প্রাইস অ্যারে চেক করা হচ্ছে
    ]);

    try {
        DB::transaction(function () use ($request, $id) {
            $order = Order::with('orderDetails')->findOrFail($id);
            
            $newSubtotal = 0;

            // ২. প্রাইস আপডেট লজিক (যদি প্রাইস পাঠানো হয়)
            if($request->has('prices')) {
                foreach ($request->prices as $detailId => $newPrice) {
                    // নির্দিষ্ট ডিটেইল খুঁজে বের করা
                    $detail = $order->orderDetails->where('id', $detailId)->first();
                    
                    if ($detail) {
                        // ভ্যালু ফ্লোট বা নাম্বারে কনভার্ট করা এবং নেগেটিভ ভ্যালু আটকানো
                        $price = max(0, floatval($newPrice)); 
                        
                        $detail->unit_price = $price;
                        $detail->subtotal = $price * $detail->quantity;
                        
                        // ডিসকাউন্ট বাদ দিয়ে আফটার ডিসকাউন্ট প্রাইস আপডেট
                        $detail->after_discount_price = $detail->subtotal - ($detail->discount ?? 0);
                        
                        $detail->save();

                        $newSubtotal += $detail->after_discount_price;
                    }
                }
            } else {
                // যদি কোনো কারণে প্রাইস না আসে, আগের সাবটোটালই থাকবে (সেফটি)
                $newSubtotal = $order->subtotal;
            }

            // ৩. অর্ডারের মেইন ক্যালকুলেশন আপডেট
            $order->subtotal = $newSubtotal;
            // টোটাল অ্যামাউন্ট = সাবটোটাল + শিপিং - ডিসকাউন্ট
            $order->total_amount = $newSubtotal + ($order->shipping_cost ?? 0) - ($order->discount ?? 0);
            
            // ডিউ ক্যালকুলেশন (রাউন্ড আপ করা ভালো, তবে এখানে সাধারণ বিয়োগ রাখা হলো)
            $order->due = $order->total_amount - $order->total_pay;
            
            // ৪. স্ট্যাটাস আপডেট (স্টক ম্যানেজমেন্ট লজিক থাকলে এখানে অ্যাড করতে হবে)
            /* নোট: আপনার যদি StockManagementTrait থাকে এবং স্ট্যাটাস চেঞ্জের সাথে স্টক 
               কাটা/ফেরত দেওয়ার লজিক থাকে, তবে সেটি এখানে কল করতে হবে। 
               বর্তমানে শুধু স্ট্যাটাস টেক্সট আপডেট করা হচ্ছে।
            */
            $order->status = $request->status;
            
            $order->save();
        });

        return redirect()->back()->with('success', 'Order status and prices updated successfully!');

    } catch (\Exception $e) {
        // এরর লগ করা এবং ইউজারকে জানানো
        Log::error('Order Update Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}
}
