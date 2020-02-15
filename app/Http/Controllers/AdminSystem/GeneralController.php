<?php

namespace App\Http\Controllers\AdminSystem;

use App\Category;
use App\Http\Controllers\Controller;
use App\Order;
use App\Product;
use App\Rules\ValidProductName;
use App\Rules\ValidProductPrice;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GeneralController extends Controller
{

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

            $list[$i]['id']     = $prod['id'];
            $list[$i]['name']   = $prod['title'];
            $list[$i]['status'] = config('site.product_status.' . $prod['status']);
            $list[$i]['price']  = number_format((float) $prod['price'], 2, '.', '');

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

        $results['total'] = array();

        $res_earningsTotal = DB::table("orders")
            ->select(DB::raw("SUM(orders.cost) as sum"))
            ->where(function ($query) {
                $query->where('orders.status', '=', 'PAID')->orWhere('orders.status', '=', 'REALIZE')->orWhere('orders.status', '=', 'SENT')->orWhere('orders.status', '=', 'RECEIVE');
            })
            ->first();

        $results['total']['earningsAll'] = number_format(($res_earningsTotal->sum ?? 0), 2, '.', ' ');

        $res_earningsMonth = DB::table("orders")
            ->select(DB::raw("SUM(orders.cost) as sum"))
            ->where(function ($query) {
                $query->where('orders.status', '=', 'PAID')->orWhere('orders.status', '=', 'REALIZE')->orWhere('orders.status', '=', 'SENT')->orWhere('orders.status', '=', 'RECEIVE');
            })
            ->whereRaw('YEAR(orders.created_at) = YEAR(CURDATE()) AND MONTH(orders.created_at) = MONTH(CURDATE())')
            ->first();

        $results['total']['earningsMonth'] = number_format(($res_earningsMonth->sum ?? 0), 2, '.', ' ');

        $results['total']['products'] = count(Product::all());
        $results['total']['orders']   = count(Order::all());

        $results['earningsMonth'] = array();

        $res_earningsByMonth = DB::table("orders")
            ->select(DB::raw("MONTH(orders.created_at) as month, SUM(orders_products.price) as sum"))
            ->join('orders_products', 'orders.id', '=', 'orders_products.order_id')
            ->where(function ($query) {
                $query->where('orders.status', '=', 'PAID')->orWhere('orders.status', '=', 'REALIZE')->orWhere('orders.status', '=', 'SENT')->orWhere('orders.status', '=', 'RECEIVE');
            })
            ->whereRaw('YEAR(orders.created_at) = YEAR(CURDATE())')
            ->groupBy(DB::raw('YEAR(orders.created_at), MONTH(orders.created_at)'))
            ->get();

        foreach ($res_earningsByMonth as $v) {
            $results['earningsMonth'][$v->month] = $v->sum;
        }

        $results['success'] = true;
        return response()->json($results);
    }

}
