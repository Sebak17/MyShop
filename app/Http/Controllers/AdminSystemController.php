<?php

namespace App\Http\Controllers;

use App\Category;
use App\Helpers\OrderHelper;
use App\Order;
use App\OrderHistory;
use App\Product;
use App\ProductImage;
use App\Rules\ValidAddress;
use App\Rules\ValidCategoryName;
use App\Rules\ValidCity;
use App\Rules\ValidDistrict;
use App\Rules\ValidIconFA;
use App\Rules\ValidID;
use App\Rules\ValidLockerName;
use App\Rules\ValidOrderStatus;
use App\Rules\ValidProductCategory;
use App\Rules\ValidProductDescription;
use App\Rules\ValidProductImage;
use App\Rules\ValidProductName;
use App\Rules\ValidProductParams;
use App\Rules\ValidProductPrice;
use App\Rules\ValidZipCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminSystemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //
    //      GENERAL
    //

    public function categoryList()
    {

        $results = array();

        $list1 = array();

        $categories = Category::all();

        $i = 0;

        foreach ($categories as $cat) {

            if ($cat['overcategory'] == -1) {
                continue;
            }

            $list1[$i] = array();

            $list1[$i]['id']      = $cat['id'];
            $list1[$i]['name']    = $cat['name'];
            $list1[$i]['order']   = $cat['orderID'];
            $list1[$i]['icon']    = $cat['icon'];
            $list1[$i]['active']  = $cat['active'];
            $list1[$i]['visible'] = $cat['visible'];

            if ($cat['overcategory'] != 0) {
                $list1[$i]['overcategory'] = $cat['overcategory'];
            }

            $i++;
        }

        $results['success'] = true;
        $results['list1']   = $list1;

        return response()->json($results);

    }

    public function productLoadList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => new ValidProductName,
            'minPrice' => new ValidProductPrice,
            'maxPrice' => new ValidProductPrice,
        ]);

        $results = array();

        $useParams = false;

        if (!$validator->fails()) {
            $useParams = true;
        }

        $list = array();

        $products = Product::get();

        $i = 0;

        foreach ($products as $prod) {

            if ($useParams) {

                if ($request->name != "" && !preg_match("/(" . $request->name . ")/i", $prod['title'])) {
                    continue;
                }

                if ($request->minPrice != "" && $prod['price'] < $request->minPrice) {
                    continue;
                }

                if ($request->maxPrice != "" && $prod['price'] > $request->maxPrice) {
                    continue;
                }

            }

            $list[$i] = array();

            $list[$i]['id']    = $prod['id'];
            $list[$i]['name']  = $prod['title'];
            $list[$i]['price'] = number_format((float) $prod['price'], 2, '.', '');

            $list[$i]['image1'] = (count($prod->images) > 0 ? $prod->images[0]->name : null);

            $i++;
        }

        $results['success'] = true;
        $results['list']    = $list;

        return response()->json($results);
    }

    public function dashboardData(Request $request)
    {
        $results = array();

        $results['total']                  = array();
        $results['total']['earningsAll']   = 0;
        $results['total']['earningsMonth'] = 0;
        $results['total']['products']      = count(Product::all());
        $results['total']['reports']       = 0;

        $results['success'] = true;
        return response()->json($results);
    }

    //
    //      PRODUCT CREATE SITES
    //

    public function productAddImageUpload(Request $request)
    {
        $results = array();

        if (!$request->hasFile('images')) {
            $results['success'] = false;
            return response()->json($results);
        }

        $validator = Validator::make($request->all(), [
            'images'   => 'required',
            'images.*' => 'mimes:png,jpeg',
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;
            $results['msg']     = $validator->errors()->first();
            return response()->json($results);
        }

        $files = $request->file('images');

        foreach ($files as $file) {
            $ar   = explode("/", $file->store('public/tmp_images'));
            $hash = end($ar);

            $request->session()->push('tmp_images', $hash);
        }

        $results['success'] = true;

        return response()->json($results);
    }

    public function productAddImageRemove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => new ValidProductImage,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        if (!is_array($request->session()->get('tmp_images'))) {
            $results['success'] = false;
            return response()->json($results);
        }

        if (!Storage::exists("public/tmp_images/" . $request->name)) {
            $results['success'] = false;
            $results['msg']     = "Plik nie istnieje!";
            return response()->json($results);
        }

        Storage::delete("public/tmp_images/" . $request->name);
        $request->session()->put('tmp_images', array_diff($request->session()->get('tmp_images'), [$request->name]));

        $results['success'] = true;
        return response()->json($results);
    }

    public function productLoadOldImages(Request $request)
    {
        $results = array();

        if (!is_array($request->session()->get('tmp_images'))) {
            $results['success'] = false;
            return response()->json($results);
        }

        $results['images'] = array();

        foreach ($request->session()->get('tmp_images') as $hash) {

            if (!Storage::exists("public/tmp_images/" . $hash)) {
                $request->session()->put('tmp_images', array_diff($request->session()->get('tmp_images'), [$hash]));
                continue;
            }

            array_push($results['images'], $hash);
        }

        $results['success'] = true;
        return response()->json($results);
    }

    public function productCreate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'        => new ValidProductName,
            'price'       => new ValidProductPrice,
            'description' => new ValidProductDescription,
            'category'    => new ValidProductCategory,
            'params'      => new ValidProductParams,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        $images = array();

        if (is_array($request->session()->get('tmp_images'))) {

            foreach ($request->session()->get('tmp_images') as $hash) {

                if (!Storage::exists("public/tmp_images/" . $hash)) {
                    $request->session()->put('tmp_images', array_diff($request->session()->get('tmp_images'), [$hash]));
                    continue;
                }

                array_push($images, $hash);
            }

        }

        $product = Product::create([
            'title'       => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'status'      => "INVISIBLE",
            'category_id' => $request->category,
            'params'      => $request->params,
        ]);

        foreach ($images as $value) {
            ProductImage::create([
                'product_id' => $product->id,
                'name'       => $value,
            ]);

            Storage::move("public/tmp_images/" . $value, "public/products_images/" . $value);
        }

        $request->session()->forget('tmp_images');

        $results['success'] = true;

        return response()->json($results);
    }

    //
    //      PRODUCT EDIT SITES
    //

    public function productLoadCurrent(Request $request)
    {
        $results = array();

        $current_id = $request->session()->get("ADMIN_PRODUCT_EDIT_ID");

        $product = Product::where('id', $current_id)->first();

        if ($product == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        $results['product'] = array();

        $results['product']['name']        = $product['title'];
        $results['product']['description'] = $product['description'];
        $results['product']['price']       = $product['price'];
        $results['product']['category_id'] = $product['category_id'];
        $results['product']['params']      = $product['params'];

        $results['product']['images'] = array();

        foreach ($product->images as $image) {

            array_push($results['product']['images'], $image->name);
        }

        $results['success'] = true;
        return response()->json($results);
    }

    public function productEdit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'        => new ValidProductName,
            'price'       => new ValidProductPrice,
            'description' => new ValidProductDescription,
            'category'    => new ValidProductCategory,
            'params'      => new ValidProductParams,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        $current_id = $request->session()->get("ADMIN_PRODUCT_EDIT_ID");

        $product = Product::where('id', $current_id)->first();

        if ($product == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        $product->title       = $request->name;
        $product->price       = $request->price;
        $product->description = $request->description;
        $product->category_id = $request->category;
        $product->params      = $request->params;

        $product->save();

        $results['success'] = true;

        return response()->json($results);
    }

    public function productEditImageList(Request $request)
    {

        $results = array();

        $current_id = $request->session()->get("ADMIN_PRODUCT_EDIT_ID");

        $product = Product::where('id', $current_id)->first();

        if ($product == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        $results['images'] = array();

        foreach ($product->images as $image) {

            array_push($results['images'], $image->name);
        }

        $results['success'] = true;
        return response()->json($results);
    }

    public function productEditImageAdd(Request $request)
    {
        $results = array();

        if (!$request->hasFile('images')) {
            $results['success'] = false;
            return response()->json($results);
        }

        $validator = Validator::make($request->all(), [
            'images'   => 'required',
            'images.*' => 'mimes:png,jpeg',
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $current_id = $request->session()->get("ADMIN_PRODUCT_EDIT_ID");

        $product = Product::where('id', $current_id)->first();

        if ($product == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        $files = $request->file('images');

        foreach ($files as $file) {
            $ar   = explode("/", $file->store('public/products_images'));
            $hash = end($ar);

            ProductImage::create([
                'product_id' => $product->id,
                'name'       => $hash,
            ]);
        }

        $results['success'] = true;

        return response()->json($results);
    }

    public function productEditImageRemove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => new ValidProductImage,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        $current_id = $request->session()->get("ADMIN_PRODUCT_EDIT_ID");

        $product = Product::where('id', $current_id)->first();

        if ($product == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        $productImage = ProductImage::where('name', $request->name)->first();

        if ($productImage == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        if ($product->id != $productImage->product_id) {
            $results['success'] = false;
            return response()->json($results);
        }

        $productImage->delete();

        if (Storage::exists("public/products_images/" . $request->name)) {
            Storage::delete("public/products_images/" . $request->name);
        }

        $results['success'] = true;
        return response()->json($results);
    }

    //
    //      CATEGORIES MANAGER SITES
    //

    public function categoryAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => new ValidCategoryName,
            'icon'  => new ValidIconFA,
            'ovcat' => new ValidID,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;
            $results['msg']     = $validator->errors()->first();
            return response()->json($results);
        }

        $orderID = count(Category::where('overcategory', $request->ovcat)->get()) + 1;

        Category::create([
            'name'         => $request->name,
            'orderID'      => $orderID,
            'overcategory' => $request->ovcat,
            'active'       => 1,
            'visible'      => 1,
            'icon'         => $request->icon,
        ]);

        $results['success'] = true;
        return response()->json($results);
    }

    public function categoryRemove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => new ValidID,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;
            $results['msg']     = $validator->errors()->first();
            return response()->json($results);
        }

        $category = Category::where('id', $request->id)->first();

        if ($category == null) {
            $results['success'] = false;
            $request['msg']     = "Nie znaleziono produktu!";
            return response()->json($results);
        }

        $categories = Category::where('overcategory', $request->id)->get();

        foreach ($categories as $cat) {
            $cat->overcategory = -1;
            $cat->save();
        }

        $category->delete();

        $results['success'] = true;
        return response()->json($results);
    }

    public function categoryEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'   => new ValidID,
            'name' => new ValidCategoryName,
            'icon' => new ValidIconFA,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;
            $results['msg']     = $validator->errors()->first();
            return response()->json($results);
        }

        $category = Category::where('id', $request->id)->first();

        if ($category == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        $category->name = $request->name;
        $category->icon = $request->icon;
        $category->save();

        $results['success'] = true;
        return response()->json($results);
    }

    public function categoryChangeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'newids'   => "required|array",
            'newids.*' => new ValidID,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;
            $results['msg']     = $validator->errors()->first();
            return response()->json($results);
        }

        foreach ($request->newids as $id => $index) {

            $category          = Category::where('id', $id)->first();
            $category->orderID = $index;
            $category->save();
        }

        $results['success'] = true;
        return response()->json($results);
    }

    //
    //      ORDER MANAGER SITES
    //

    public function orderChangeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'     => new ValidID,
            'status' => new ValidOrderStatus,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $order = Order::where('id', $request->id)->first();

        if ($order == null) {
            $results['success'] = false;

            $results['msg'] = "Nie znaleziono zamówienia!";
            return response()->json($results);
        }

        OrderHelper::changeOrderStatus($order, $request->status);

        $results['success'] = true;
        return response()->json($results);
    }

    public function orderChangeDeliverLoc(Request $request)
    {
        $results = array();

        $validator = Validator::make($request->all(), [
            'id' => new ValidID,
            'deliver.type' => "required|in:INPOST_LOCKER,COURIER",
        ]);

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $deliver_info = array();

        switch ($request->input('deliver.type')) {
            case 'INPOST_LOCKER':
                $validator = Validator::make($request->all(), [
                    'deliver.lockerName' => new ValidLockerName,
                ]);

                if ($validator->fails()) {
                    $results['success'] = false;

                    $results['msg'] = $validator->errors()->first();
                    return response()->json($results);
                }

                $deliver_info['lockerName'] = $request->input('deliver.lockerName');

                break;
            case 'COURIER':
                $validator = Validator::make($request->all(), [
                    'deliver.district' => new ValidDistrict,
                    'deliver.city'     => new ValidCity,
                    'deliver.zipcode'  => new ValidZipCode,
                    'deliver.address'  => new ValidAddress,
                ]);

                if ($validator->fails()) {
                    $results['success'] = false;

                    $results['msg'] = $validator->errors()->first();
                    return response()->json($results);
                }

                $deliver_info['district'] = $request->input('deliver.district');
                $deliver_info['city']     = $request->input('deliver.city');
                $deliver_info['zipcode']  = $request->input('deliver.zipcode');
                $deliver_info['address']  = $request->input('deliver.address');

                break;
        }

        if (empty($deliver_info)) {
            $results['success'] = false;
            $results['msg']     = "Wystąpił błąd z danymi do wysyłki!";
            return response()->json($results);
        }

        $order = Order::where('id', $request->id)->first();

        if($order == null) {
           $results['success'] = false;
            $results['msg']     = "Wystapił bład z zamówieniem!";
            return response()->json($results); 
        }


        $order->deliver_name = $request->input('deliver.type');
        $order->deliver_info = json_encode($deliver_info);
        $order->save();

        OrderHistory::create([
            'order_id' => $order->id,
            'data' => 'Zmiana dostawy zamówienia',
        ]);


        $results['success'] = true;
        return response()->json($results);
    }

    public function orderChangePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'     => new ValidID,
            'paymentMethod' => "required|in:PAYU,PAYPAL,PAYMENTCARD",
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $order = Order::where('id', $request->id)->first();

        if ($order == null) {
            $results['success'] = false;

            $results['msg'] = "Nie znaleziono zamówienia!";
            return response()->json($results);
        }

        OrderHistory::create([
            'order_id' => $order->id,
            'data' => 'Zmiana płatności z ' . $order->payment . ' na ' . $request->paymentMethod,
        ]);

        $order->payment = $request->paymentMethod;
        $order->save();

        $results['success'] = true;
        return response()->json($results);
    }

}
