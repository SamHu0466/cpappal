<?php

namespace App\Http\Controllers;

use App\Customer;
use App\OrderProduct;
use App\OrderSubProduct;
use App\OrderType;
use Illuminate\Http\Request;
use App\Order;
use DB;
use Session;
use App\Country;
use App\Currency;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = DB::table('orders')->get();

        return view('admin.order.index',compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $products = DB::table('products')
            ->join('categories','products.category_id','categories.id')
            ->select('products.*','categories.name as category_name')
            ->orderBy('categories.priority','asc')
            ->get();
        $customers = Customer::all();

        $order_types = OrderType::all();

        $countries = Country::all();

        $currencies = Currency::all();

        $max_order_no = DB::table('orders')->max('order_no');

        return view('admin.order.add',compact('customers','products','order_types','max_order_no','countries','currencies'));
    }

    public function create(Request $request)
    {
        if(is_null($request->customer_id)){
            $customer_id = Customer::createCustomer($request);
        }else{
            $customer_id = $request->customer_id;
        }
        $order = Order::createOrder($request,$customer_id);

        for($i = 0 ;$i <count($request->ui_product_id); $i++){
            $orderProduct = OrderProduct::createOrderProduct($request,$order->id,$i);

            for($y = 0 ;$y <count($request->ui_sub_product_id); $y++){
                if($request->ui_product_id[$i] == $request->ui_sub_product_id[$y]){
                    OrderSubProduct::createOrderSubProduct($request,$orderProduct->id,$y);
                }
            }
        }
/**Auto Download file**/
//        Session::flash('exportExcel', 'export/'.$order->id);

        return redirect()->route('order')->with('success', true)->with('message','Order created successfully!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customers = Customer::all();
        $countries = Country::all();
        $currencies = Currency::all();

        $products = DB::table('products')
            ->join('categories','products.category_id','categories.id')
            ->select('products.*','categories.name as category_name')
            ->get();

        $orders = DB::table('orders')
            ->join('order_products','order_products.order_id','orders.id')
            ->join('products','products.id','order_products.product_id')
            ->join('categories','categories.id','products.category_id')
            ->where('orders.id',$id)
            ->select('orders.id as ordersid' ,'orders.*','order_products.id as order_productsid', 'order_products.*','categories.name as category_name')
            ->orderBy('categories.priority','asc')
            ->get();

        $orderProductsIds = DB::table('orders')
            ->join('order_products','order_products.order_id','orders.id')
            ->where('orders.id',$id)
            ->select('order_products.id')
            ->get();

        $orderProductsIdArray = [];
        foreach ($orderProductsIds as $orderProductsId) {
            array_push($orderProductsIdArray,$orderProductsId->id);
        }

        $orderSubProducts = DB::table('order_sub_products')
            ->join('products','products.id','order_sub_products.sub_product_id')
            ->join('categories','categories.id','products.category_id')
            ->whereIn('order_sub_products.order_product_id',$orderProductsIdArray)
            ->select('order_sub_products.*','categories.name as category_name')
            ->orderBy('categories.priority','asc')
            ->get();

        $order_types = OrderType::all();

        return view('admin.order.edit',compact('orders','customers','products','orderSubProducts','order_types','countries','currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->order_type = $request->invoice_type;
        $order->order_no = $request->invoice_number;
        $order->order_date = date('Y-m-d',strtotime($request->invoice_date));
        $order->separate = $request->separate_check;
        $order->referral = $request->referral;
        $order->customer_id = $request->customer_id;
        $order->customer_country_name = $request->customer_country_name;
        $order->customer_name = $request->customer_name;
        $order->customer_address = $request->customer_address;
        $order->customer_delivery_address = $request->customer_delivery_address;
        $order->customer_phone = $request->customer_phone;
        $order->customer_remark = $request->customer_remark;
        $order->total_price = $request->order_total_price;
        $order->order_currency = $request->currency_name;
        $order->order_currency_ratio = $request->currency_ratio;
        $order->save();

        for($i = 0 ;$i <count($request->ui_product_id); $i++) {
            if($request->order_productsid[$i] == null){
                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $order->id;
                $orderProduct->product_id = $request->product_id[$i];
                $orderProduct->product_name = $request->product_name[$i];
                $orderProduct->product_model_no = $request->product_model_no[$i];
                $orderProduct->product_price = $request->product_price[$i];
                $orderProduct->product_quantity = $request->product_quantity[$i];
                $orderProduct->product_total_price = $request->product_total_price[$i];
                $orderProduct->product_serial_no = $request->product_serial_no[$i];
                $orderProduct->product_remark = $request->product_remark[$i];
                $orderProduct->save();

                for ($y = 0; $y < count($request->ui_sub_product_id); $y++) {
                    if ($request->ui_product_id[$i] == $request->ui_sub_product_id[$y]) {
                        $orderSubProduct = new OrderSubProduct();
                        $orderSubProduct->order_product_id = $orderProduct->id;
                        $orderSubProduct->sub_product_id = $request->sub_product_id[$y];
                        $orderSubProduct->sub_product_name = $request->sub_product_name[$y];
                        $orderSubProduct->sub_product_quantity = $request->sub_product_quantity[$y];
                        $orderSubProduct->sub_product_price = $request->sub_product_price[$y];
                        $orderSubProduct->sub_product_total_price = $request->sub_product_total_price[$y];
                        $orderSubProduct->sub_product_model_no = $request->sub_product_model_no[$y];
                        $orderSubProduct->sub_product_serial_no = $request->sub_product_serial_no[$y];
                        $orderSubProduct->sub_product_remark = $request->sub_product_remark[$y];
                        $orderSubProduct->save();
                    }
                }
            }else{
                $orderProduct = OrderProduct::find($request->order_productsid[$i]);
                $orderProduct->order_id = $order->id;
                $orderProduct->product_id = $request->product_id[$i];
                $orderProduct->product_name = $request->product_name[$i];
                $orderProduct->product_model_no = $request->product_model_no[$i];
                $orderProduct->product_price = $request->product_price[$i];
                $orderProduct->product_quantity = $request->product_quantity[$i];
                $orderProduct->product_total_price = $request->product_total_price[$i];
                $orderProduct->product_serial_no = $request->product_serial_no[$i];
                $orderProduct->product_remark = $request->product_remark[$i];
                $orderProduct->save();

                for ($y = 0; $y < count($request->ui_sub_product_id); $y++) {
                    if ($request->ui_product_id[$i] == $request->ui_sub_product_id[$y]) {
                        if($request->sub_product_db_id[$y] == null){
                            $orderSubProduct = new OrderSubProduct();
                            $orderSubProduct->order_product_id = $orderProduct->id;
                            $orderSubProduct->sub_product_id = $request->sub_product_id[$y];
                            $orderSubProduct->sub_product_name = $request->sub_product_name[$y];
                            $orderSubProduct->sub_product_quantity = $request->sub_product_quantity[$y];
                            $orderSubProduct->sub_product_price = $request->sub_product_price[$y];
                            $orderSubProduct->sub_product_total_price = $request->sub_product_total_price[$y];
                            $orderSubProduct->sub_product_model_no = $request->sub_product_model_no[$y];
                            $orderSubProduct->sub_product_serial_no = $request->sub_product_serial_no[$y];
                            $orderSubProduct->sub_product_remark = $request->sub_product_remark[$y];
                            $orderSubProduct->save();
                        }else{
                            $orderSubProduct = OrderSubProduct::find($request->sub_product_db_id[$y]);
                            $orderSubProduct->order_product_id = $orderProduct->id;
                            $orderSubProduct->sub_product_id = $request->sub_product_id[$y];
                            $orderSubProduct->sub_product_name = $request->sub_product_name[$y];
                            $orderSubProduct->sub_product_quantity = $request->sub_product_quantity[$y];
                            $orderSubProduct->sub_product_price = $request->sub_product_price[$y];
                            $orderSubProduct->sub_product_total_price = $request->sub_product_total_price[$y];
                            $orderSubProduct->sub_product_model_no = $request->sub_product_model_no[$y];
                            $orderSubProduct->sub_product_serial_no = $request->sub_product_serial_no[$y];
                            $orderSubProduct->sub_product_remark = $request->sub_product_remark[$y];
                            $orderSubProduct->save();
                        }
                    }
                }
            }
        }
        return redirect()->route('order')->with('success', true)->with('message','Order updated successfully!');
    }

    public function template($id){
        $customers = Customer::all();
        $countries = Country::all();
        $currencies = Currency::all();

        $products = DB::table('products')
            ->join('categories','products.category_id','categories.id')
            ->select('products.*','categories.name as category_name')
            ->get();

        $orders = DB::table('template_orders')
            ->join('template_order_products','template_order_products.order_id','template_orders.id')
            ->join('products','products.id','template_order_products.product_id')
            ->join('categories','categories.id','products.category_id')
            ->where('template_orders.id',$id)
            ->select('template_orders.id as ordersid' ,'template_orders.*','template_order_products.id as order_productsid', 'template_order_products.*','categories.name as category_name')
            ->get();

        $orderProductsIds = DB::table('template_orders')
            ->join('template_order_products','template_order_products.order_id','template_orders.id')
            ->where('template_orders.id',$id)
            ->select('template_order_products.id')
            ->get();

        $orderProductsIdArray = [];
        foreach ($orderProductsIds as $orderProductsId) {
            array_push($orderProductsIdArray,$orderProductsId->id);
        }

        $orderSubProducts = DB::table('template_order_sub_products')
            ->join('products','products.id','template_order_sub_products.sub_product_id')
            ->join('categories','categories.id','products.category_id')
            ->whereIn('template_order_sub_products.order_product_id',$orderProductsIdArray)
            ->select('template_order_sub_products.*','categories.name as category_name')
            ->get();

        $order_types = OrderType::all();

        $max_order_no = DB::table('orders')->max('order_no');

        return view('admin.order.edit',compact('orders','customers','products','orderSubProducts','order_types','max_order_no','currencies','countries'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $orderProductsIds = DB::table('order_products')
            ->join('orders','order_products.order_id','orders.id')
            ->where('orders.id',$request->id)
            ->select('order_products.id')
            ->get();

        $orderProductsIdArray = [];
        foreach ($orderProductsIds as $orderProductsId) {
            array_push($orderProductsIdArray,$orderProductsId->id);
        }

        DB::table('order_sub_products')
            ->whereIn('order_sub_products.order_product_id',$orderProductsIdArray)
            ->delete();

        DB::table('order_products')
            ->join('orders','order_products.order_id','orders.id')
            ->where('orders.id',$request->id)
            ->delete();

        Order::destroy($request->id);

        return redirect()->route('order')->with('success', true)->with('message','Order delete successfully!');
    }
}
