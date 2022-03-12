<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\PurchaseOrder;
use App\Models\ProductDetails;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function viewAllProduct()
    {
        $product = DB::table('products')
                    ->join('product_details', 'products.brand_id', '=', 'product_details.id')
                    ->select('products.*', 'product_details.brandName')
                    ->get();

        

        return response()->json([
            'product'   => $product,
            'userType' => User::find(\Auth::id())->role,
            'username' => User::find(\Auth::id())->name
        ]);
        
    }

    public function showPurchaseOrder()
    {
        $purchase = DB::table('purchase_orders')
                    ->join('products', 'products.id', '=', 'purchase_orders.product_id')
                    ->join('product_details', 'product_details.id', '=', 'products.brand_id')
                    ->select('purchase_orders.*', 'products.name', 'product_details.brandName', 'products.quantity')
                    ->where('purchase_orders.status', '=', '0')
                    ->get();

        return $purchase;
        
    }

    public function showBrand()
    {
        
        $var = ProductDetails::all();

        return $var;
    }

    public function post(Request $request)
    {
        $var = new Product();

        $var->name = $request['productName'];
        $var->brand_id = $request['brandID'];
        $var->quantity = $request['quantity'];

        $var->save();

        return 'Product Created';
    }

    public function saveProductDetails(Request $request)
    {
        $brand = new ProductDetails();
        $brand->brandName = $request['brandName'];
        $brand->details = $request['brandDetails'];

        $brand->save();

        return 'Brand Created';
    }

    public function edit(Request $request)
    {
        $product = product::find($request['id']);

        \Log::info($request);

        $product->name = $request['prodName'];
        $product->brand_id = $request['brandId'];
        $product->quantity = $request['quantity'];

        $product->save();

        return 'Product Updated';
    }

    public function deleteItem(Request $request)
    {
        $product = product::find($request['id'])->delete();

        return 'Product Deleted';
    }

    public function savePurchaseOrder(Request $request)
    {
        $po = new PurchaseOrder();

        \Log::info($request);

        $po->product_id = $request['product_id'];
        $po->quantity_order = $request['quantity_order'];
        $po->username = $request['username'];
        $po->status = $request['orderStatus'];

        $po->save();

        return 'Purchase Order Created';
    }

    public function approvePurchaseOrder(Request $request)
    {
        \Log::info($request);

        $product = Product::find($request['product_id']);
        $purchaseOrder = PurchaseOrder::find($request['purchase_id']);

        $product->quantity = $product->quantity - $request['quantity_order'];

        $product->save();

        $purchaseOrder->status = 1;

        $purchaseOrder->save();

        return 'Purchase Order Approved';

    }
  
}
