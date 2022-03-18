<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\PurchaseApprove;
use App\Models\PurchaseOrder;
use App\Models\ProductDetails;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;

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
    public function myHome()
    {

        return view('theme.myHome');

    }

    public function ExportData() 
    {
        return Excel::download(new ProductExport, 'data.xlsx');
    }    

    public function index()
    {
        return view('home');
    }

    public function page2()
    {
        return view('test');
    }

    public function viewAllProduct()
    {
        $product = DB::table('products')
            ->join('product_details', 'products.brand_id', '=', 'product_details.id')
            ->select('products.*', 'product_details.brandName')
            ->where('products.quantity', '>=', '0')
            ->get();
    
        return response()->json([
            'product'   => $product,
            'userType' => User::find(\Auth::id())->role,
            'username' => User::find(\Auth::id())->name
        ]);
        
    }

    public function viewProductInfo(Request $request)
    {

        $prodInfo = DB::table('products as T1')
                ->join('product_details as T2', 'T1.brand_id', '=', 'T2.id')
                ->select('T1.name', 'T1.quantity', 'T2.brandName', 'T2.details', 'T2.price')
                ->where('T1.id', '=', $request['id'])
                ->get();

        $prodPurchase = DB::table('products as T1')
        ->join('purchase_orders as T2', 'T1.id', '=', 'T2.product_id')
        ->select('T2.po_reference_no', 'T2.quantity_order', 'T2.date_purchase', 'T2.username', 'T2.status')
        ->where('T1.id', '=', $request['id'])
        ->get();
        

        return response()->json([
            'product'   => $prodInfo,
            'productPurchase' => $prodPurchase
        ]);
    }

    public function viewBrandInfo()
    {
        $brandInfo = DB::table('product_details as T1')
                ->join('products as T2', 'T1.id', '=', 'T2.brand_id')
                ->select('T1.*', 'T2.name', 'T2.quantity')
                ->where('T1.id', request()->get('id'))
                ->get();

        return $brandInfo;
    }

    public function updatePurchaseDate(Request $request)
    {
      
        $purchaseDate = PurchaseOrder::find($request['id']);

        $purchaseDate->date_purchase = $request['purchase_date'];

        $purchaseDate->update();

        return 'Purchase Date Updated';
    }

    public function showPurchaseOrder()
    {
        $purchase = DB::table('purchase_orders')
            ->join('products', 'products.id', '=', 'purchase_orders.product_id')
            ->join('product_details', 'product_details.id', '=', 'products.brand_id')
            ->select('purchase_orders.*', 'products.name', 'product_details.brandName', 'products.quantity', 'product_details.price', 'product_details.id as brand_id')
            ->where('purchase_orders.status', '=', '0', 'and', 'products.quantity', '>', 0)
            ->get();

        return response()->json([
            'product' => $purchase,
        ]);
        
    }

    public function showApprove()
    {
        $purchaseApprove = DB::table('purchase_approves as T1')
            ->join('products as T2', 'T1.product_id', '=', 'T2.id')
            ->join('product_details as T3', 'T1.brand_id', '=', 'T3.id')
            ->join('purchase_orders as T4', 'T1.purchase_order_id', '=', 'T4.id')
            ->select('T2.name', 'T3.brandName', 'T4.quantity_order', 'T1.total_price')
            ->get();

        return $purchaseApprove;

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
        $brand->price = $request['brandPrice'];

        $brand->save();

        return 'Brand Created';
    }

    public function editProductDetails(Request $request)
    {   
        $brand = ProductDetails::find($request['id']);

        $brand->brandName = $request['brandName'];
        $brand->details = $request['brandDetails'];
        $brand->price = $request['brandPrice'];

        $brand->save();

        return 'Brand Updated';
    }

    public function edit(Request $request)
    {

        if($request['id'] == null){
            return 'Select item first to Edit';
        }
        else {
            $product = product::find($request['id']);

            $product->name = $request['prodName'];
            $product->brand_id = $request['brandId'];
            $product->quantity = $request['quantity'];

            $product->save();

            return 'Product Updated';
        }
    }

    public function deleteItem(Request $request)
    {
        $product = product::find($request['id'])->delete();

        return 'Product Deleted';
    }

    public function deleteBrand(Request $request)
    {
        $brand = ProductDetails::find($request['id'])->delete();

        return 'Brand Deleted';
    }

    public function getCustomIdAttribute() {
        return str_pad($this->id, 'C00000', STR_PAD_LEFT);
    }

    public function savePurchaseOrder(Request $request)
    {

        $formatDate = date('Ym');
        $currentDate = date('Y-m-d');

        $po = new PurchaseOrder();
        $product = Product::find($request['product_id']);

        if ($request['quantity_order'] > $product->quantity) {

            return 'Quantity order is more than available stock';
        }
        else { 
        
        $po->product_id = $request['product_id'];
        $po->po_reference_no = '';
        $po->quantity_order = $request['quantity_order'];
        $po->username = $request['username'];
        $po->status = $request['orderStatus'];
        $po->date_purchase = $currentDate;

        $po->save();
        
        $po->po_reference_no = ($formatDate.'-'.str_pad($po->id, 3, 0, STR_PAD_LEFT));

        $po->update();
    

        return 'Purchase Order Created';
        }
    }

    public function approvePurchaseOrder(Request $request)
    {

        $product = Product::find($request['product_id']);
        $purchaseOrder = PurchaseOrder::find($request['purchase_id']);
        $poApprove = new PurchaseApprove();

        if ($request['quantity_order'] > $product->quantity) {

            return 'Insufficient quantity';

        }
        else {

            $totalPrice = $request['quantity_order'] * $request['brandPrice'];

            $product->quantity = $product->quantity - $request['quantity_order'];

            $purchaseOrder->status = 1;
 
            $poApprove->product_id = $request['product_id'];
            
            $poApprove->brand_id = $request['brand_id'];

            $poApprove->purchase_order_id = $request['purchase_id'];

            $poApprove->total_price = $totalPrice;

            $poApprove->save();
            $product->save();
            $purchaseOrder->save();
    
            return 'Purchase Order Approved';
        }

    }
  
}
