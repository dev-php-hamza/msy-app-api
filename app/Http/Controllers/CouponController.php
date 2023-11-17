<?php

namespace App\Http\Controllers;

use App\Coupon;
use Illuminate\Http\Request;
use App\Product;
use App\CouponProduct;
use App\Country;
use App\Bundle;
use App\Notification;
use App\Helper;
use App\MixAndMatchCondition;
use App\CouponList;
use Illuminate\Support\Str;

class CouponController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('admin.coupon.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        $barcode   = $this->generateBarcode();
        return view('admin.coupon.create', compact('countries', 'barcode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'title'             => 'required|string|max:255|unique:coupons,title',
            'country_id'        => 'required|numeric',
            // 'start_date'        => 'required',
            // 'end_date'          => 'required',
            'short_description' => 'required',
            'description'       => 'required',
            'coupon_type'       => 'required',
            // 'barcode'           => ['required','regex:/(^(\d{11}|\d{13})$)/'],
            // 'barcode_image'     => 'nullable|image|mimes:jpeg,png,jpg',
            'file'              => 'nullable|image|mimes:jpeg,png,jpg',
            // 'start_time'     => 'required',
            // 'end_time'     => 'required',
        ]);

        // $start_time = strtotime($request->start_date.' '.$request->start_time);
        // $end_time = strtotime($request->end_date.' '.$request->end_time);
        // $dStart = date("Y-m-d H:i:s", $start_time);
        // $dEnd = date("Y-m-d H:i:s", $end_time);
        // $dS =  new \DateTime($dStart);
        // $dE =  new \DateTime($dEnd);
        $coupon = new Coupon;
        $coupon->title = $request->title;
        // $coupon->start_date = $request->start_date;
        // $coupon->start_time = $dS;
        // $coupon->end_date = $request->end_date;
        // $coupon->end_time = $dE;
        $coupon->short_description = $request->short_description;
        $coupon->description = $request->description;
        $coupon->country_id = $request->country_id;
        $coupon->barcode = $request->barcode;
        $coupon->coupon_type = $request->coupon_type;
        $coupon->active =  0;
        $coupon->is_featured =  0;
        if (isset($request->checkbox)) {
            $coupon->active =  $request->checkbox[0];
        }
        if (isset($request->featured_checkbox)) {
            $coupon->is_featured =  $request->featured_checkbox[0];
        }
        $coupon->save();

        // if ($request->hasFile('barcode_image')) {
        //     $this->saveImage($coupon, $request->barcode_image, 'barcode_image');
        // }

        if ($request->hasFile('file')) {
            $this->saveImage($coupon, $request->file, 'file');
        }

        return redirect()->route('choose_products_for_coupon',['couponId'=>$coupon->id, 'couponType' => $coupon->coupon_type]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        $conditions_data = array();
        if(isset($coupon->mix_and_match_conditions) && count($coupon->mix_and_match_conditions)){
            $conditions = array();
            foreach ($coupon->mix_and_match_conditions as $conditon) {
                $conditions = $conditon->conditions;
                $conditions_data['selection_quantity'] = 'Select '.$conditon->selection_quantity.' to get for free';
            }
            if(!empty($conditions)){
                $cond_arr = explode(",", $conditions);
                foreach ($cond_arr as $single) {
                    $single_arr = explode("-", $single);
                    $conditions_data['conditions'][] = 'Buy '.$single_arr[0].' of Product(s)';
                }
            }
        }
        return view('admin.coupon.details', compact('coupon','conditions_data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        $countries = Country::all();
        return view('admin.coupon.edit', compact('countries','coupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        $this->validate($request, [
            'title'             => 'required|string|max:255',
            'country_id'        => 'required|numeric',
            // 'start_date'        => 'required',
            // 'end_date'          => 'required',
            'short_description' => 'required',
            'description'       => 'required',
            // 'barcode'           => ['required','regex:/(^(\d{11}|\d{13})$)/'],
            // 'barcode_image'     => 'nullable|image|mimes:jpeg,png,jpg',
            'file'              => 'image|mimes:jpeg,png,jpg',
            // 'start_time'     => 'required',
            // 'end_time'     => 'required',
        ]);

        // $start_time = strtotime($request->start_date.' '.$request->start_time);
        // $end_time = strtotime($request->end_date.' '.$request->end_time);
        // $dStart = date("Y-m-d H:i:s", $start_time);
        // $dEnd = date("Y-m-d H:i:s", $end_time);
        // $dS =  new \DateTime($dStart);
        // $dE =  new \DateTime($dEnd);

        $copon = Coupon::find($coupon->id);
        $copon->title = $request->title;
        // $copon->start_date = $request->start_date;
        // $copon->start_time = $dS;
        // $copon->end_date = $request->end_date;
        // $copon->end_time = $dE;
        $copon->short_description = $request->short_description;
        $copon->description = $request->description;
        $copon->country_id = $request->country_id;
        $copon->barcode = $request->barcode;
        $copon->save();

        // $barCodeFileCompName = null;
        // if ($request->hasFile('barcode_image')) {
        //     $barcodeFile = $request->barcode_image;
        //     $fileName = pathinfo($barcodeFile->getClientOriginalName(),PATHINFO_FILENAME);
        //     $fileExten = $barcodeFile->getClientOriginalExtension();
        //     $barCodeFileCompName = rand().'_'.$coupon->id.'.'.$fileExten;
        //     if ($barCodeFileCompName != $coupon->barcode_image) {

        //         $fileToBeDeleted = Helper::getFileNameForDelete($coupon->barcode_image);
        //         $fileToBeDeleted = public_path().'/coupon/images/'.$fileToBeDeleted;

        //         if (file_exists($fileToBeDeleted)) {
        //             @unlink($fileToBeDeleted);
        //             $barcodeFile->move('coupon/images/',$barCodeFileCompName);

        //             $barCodeFileCompName  = url('/').'/coupon/images/'.$barCodeFileCompName;

        //             $copon->barcode_image = $barCodeFileCompName;
        //             $copon->save();
        //         }
        //     }
        // }

        $fileCompName = null;
        if ($request->hasFile('file')) {
            $file = $request->file;
            $fileName = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
            $fileExten = $file->getClientOriginalExtension();
            $fileCompName = rand().'_'.$coupon->id.'.'.$fileExten;
            if ($fileCompName != $coupon->image) {

                $fileToBeDeleted = Helper::getFileNameForDelete($coupon->image);
                $fileToBeDeleted = public_path().'/coupon/images/'.$fileToBeDeleted;

                if (file_exists($fileToBeDeleted)) {
                    @unlink($fileToBeDeleted);
                    $file->move('coupon/images/',$fileCompName);

                    $fileCompName  = url('/').'/coupon/images/'.$fileCompName;
                    $copon->image = $fileCompName;
                    $copon->save();
                }
            }
        }


        return redirect()->route('edit_choosen_products_for_coupon',['couponId'=>$copon->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        $couponNotifications = Notification::whereObject('coupon')->whereObjectId($coupon->id)->get();
        foreach ($couponNotifications as $key => $notification) {
            $notification->delete();
        }
        
        $coupon->delete();
        $path = public_path().'/coupon/images/';
        $fileToBeDeleted = $path.Helper::getFileNameForDelete($coupon->image);
        $barcodeFile     = $path.Helper::getFileNameForDelete($coupon->barcode_image);
        if (file_exists($fileToBeDeleted)) {
            @unlink($fileToBeDeleted);
        }

        if (file_exists($barcodeFile)) {
            @unlink($barcodeFile);
        }
        return redirect()->route('coupons.index')->with('message','coupon has been deleted successfully!');
    }

    public function showProductsByCountry($couponId)
    {
        $coupon = Coupon::find($couponId);
        return view('admin.coupon.choose_products', compact('coupon'));
    }

    public function saveCouponProducts(Request $request, $couponId)
    {
        // dd($request->all());
        $bundle_id = Null;
        if (isset($request->products)) {

            // Save bundle number and price for standard bundles 
            if ($request->cupn_type == 'std_bundle' || $request->cupn_type == 'mix_and_match') {
                if (isset($request->create)) {
                    $bundle_id = Bundle::updateOrCreate([
                        'coupon_id' => $couponId,
                        'name' => $request->cupn_type,
                    ],[
                        'number' => $this->generteRand(),
                    ])->id;
                }else{
                    $bundle_id = Bundle::updateOrCreate([
                        'coupon_id' => $couponId,
                    ],[
                        'name' => $request->cupn_type,
                    ])->id;
                }
            }

            // Save coupon products
            $p_index = 0;
            foreach ($request->products as $key => $productUpc) {
                // Get mix and match product type if exists
                $mm_type = NULL;
                if(isset($request->mix_and_match_type) && $request->mix_and_match_type == 'different_cost_products'){
                    if(isset($request->mix_and_match_product_type)){
                        $mm_type = $request->mix_and_match_product_type[$p_index];
                    }
                }
                // Save products data
                $couponProduct = CouponProduct::updateOrCreate([
                    'coupon_id' => $couponId,
                    'product_id'   => $key,
                ],[
                    'coupon_id' => $couponId,
                    'product_id'   => $key,
                    'total_price'   => (isset($request->total_price)) ? $request->total_price[$key] : NULL,
                    'discount_price'   => (isset($request->discounted_price)) ? $request->discounted_price[$key] : NULL,
                    'discount_percentage'   => (isset($request->discount_percentage)) ? $request->discount_percentage[$key] : NULL,
                    'discount_type'   => (isset($request->discount_type)) ? $request->discount_type[$key] : NULL,
                    'quantity'   => (isset($request->discount_percentage)) ? $request->quantity[$key] : NULL,
                    'type' => $mm_type,
                    'bundle_id'   => $bundle_id,
                ]);
                $p_index = $p_index + 1;
            }

            // Save Bundle Price for standard bundle
            if ($request->cupn_type == 'std_bundle') {
                $discounted_price = CouponProduct::where('coupon_id', $couponId)->get();
                $data = array();
                foreach ($discounted_price as $key => $value) {
                    $data[$key] = $value->discount_price;
                }
                $sum = array_sum($data);
                Bundle::updateOrCreate([
                    'id' => $bundle_id,
                ],[
                    'bundle_price' => number_format($sum,2),
                ]);
            }

            
            if ($request->cupn_type == 'mix_and_match') {
                // Save Mix and Match Conditions
                // delete mixmatchconditions on coupon based
                MixAndMatchCondition::where('coupon_id', $couponId)->delete();
                $conditions = '';
                for ($i=0; $i < count($request->buy_q); $i++) { 
                    $conditions .= $request->buy_q[$i].'-'.$request->prod_q[$i].',';
                }
                $conditions = rtrim($conditions,',');
                $conditions = rtrim($conditions,'-');
                $cond = new MixAndMatchCondition();
                $cond->coupon_id = $couponId;
                $cond->conditions = $conditions;
                $cond->selection_quantity = $request->sel_q;
                $cond->save();

                // Save mix and match type in its coupon record
                $coupon = Coupon::where('id', $couponId)->first();
                if(isset($coupon)){
                    $coupon->mix_and_match_type = $request->mix_and_match_type;
                    $coupon->save();
                }

                // Save Bundle Price for mix and match
                if ($request->mix_and_match_type == 'different_cost_products') {
                    $price = CouponProduct::where('coupon_id', $couponId)->where('type', 'buy')->get();
                    $data = array();
                    foreach ($price as $key => $value) {
                        $product = Product::where('id', $value->product_id)->first();
                        $unit_value = $product->unit_retail;
                        $quantity = $value->quantity;
                        if ($quantity == null) {
                            $quantity = 1;
                        }
                        $data[$key] = $unit_value * $quantity;
                    }
                    $total_sum = array_sum($data);

                    Bundle::updateOrCreate([
                        'id' => $bundle_id,
                    ],[
                        'bundle_price' => number_format($total_sum,2),
                    ]);
                }
                if ($request->mix_and_match_type == 'same_cost_products') {
                    $price = CouponProduct::where('coupon_id', $couponId)->get();
                    $data = array();
                    foreach ($price as $key => $value) {
                        $product = Product::where('id', $value->product_id)->first();
                        $unit_value = $product->unit_retail;
                        $quantity = $value->quantity;
                        if ($quantity == null) {
                            $quantity = 1;
                        }
                        $data[$key] = $unit_value * $quantity;
                    }
                    $product_count = count($data);
                    $sum = array_sum($data);
                    $avg_prd_prc = $sum / $product_count;
                    // selection
                    $sel = $request->sel_q;
                    $disc_price = $sel * $avg_prd_prc;
                    // $selection = CouponProduct::where('coupon_id', $couponId)->take($sel)->get();
                    // $new_data = array();
                    // foreach ($selection as $key => $value) {
                    //     $product = Product::where('id', $value->product_id)->first();
                    //     $unit_value = $product->unit_retail;
                    //     $quantity = $value->quantity;
                    //     if ($quantity == null) {
                    //         $quantity = 1;
                    //     }
                    //     $new_data[$key] = $unit_value * $quantity;
                    // }
                    // $new_sum = array_sum($new_data);

                    // total value
                    $total_sum = $sum - $disc_price;
                    Bundle::updateOrCreate([
                        'id' => $bundle_id,
                    ],[
                        'bundle_price' => number_format($total_sum,2),
                    ]);
                }
            }

            $message = 'Product successfully added into coupon';
        }else{
            $message = 'Coupon successfully saved without any product';
        }

        return redirect()->route('coupons.index')->with('message',$message);
    }

    public function updateCouponStatus(Request $request)
    {
        $coupon = Coupon::where('id', $request->id)->first();
        if (count($coupon) > 0) {
            $coupon->active = !$coupon->active;
            $coupon->save();
            if(!$coupon->active){
                CouponList::where('coupon_id', $coupon->id)->delete();
            }
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }

    public function updateCouponFeature(Request $request)
    {
        $coupon = Coupon::where('id', $request->id)->first();
        if (count($coupon) > 0) {
            $coupon->is_featured = !$coupon->is_featured;
            $coupon->save();
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }

    public function generteRand()
    {
        $classification = 'BUN';
        $forward = substr(time(), 6, 9);
        $upper = mt_rand(1001, 9999);
        $lower = str_pad(mt_rand(101, 999),7,$forward,STR_PAD_RIGHT);
        return $classification.$upper.'-'.$lower;
    }

    public function getProductsByUpcByCountry($countryId,$upc)
    {
        $product = Product::whereUpc($upc)->whereCountryId($countryId)->first();

        if (isset($product) && count($product) > 0) {
            return response()->json(['product'=>$product]);
        }else{
            return response()->json(['product'=>[]]);
        }
    }

    public function editChoosenProductsForCoupon($couponId)
    {
        $coupon = Coupon::find($couponId);

        $conditions_data = array();
        if(isset($coupon->mix_and_match_conditions) && count($coupon->mix_and_match_conditions)){
            $conditions = array();
            foreach ($coupon->mix_and_match_conditions as $conditon) {
                $conditions = $conditon->conditions;
                $conditions_data['selection_quantity'] = $conditon->selection_quantity;
            }
            if(!empty($conditions)){
                $cond_arr = explode(",", $conditions);
                foreach ($cond_arr as $single) {
                    $temp = array();
                    $single_arr = explode("-", $single);
                    $temp['buy'] = $single_arr[0];
                    $temp['products'] = $single_arr[1]??'';
                    
                    $conditions_data['conditions'][] = $temp;
                }
            }
        }

        // $locations = Location::where('country_id',$countryId)->get();
        // $locationIds = array();
        // foreach ($locations as $key => $location) {
        //     array_push($locationIds, $location->id);
        // } editChoosenProductsForPromotion

        // $locationProducts = LocationProduct::whereIn('location_id',$locationIds)->get();
        // $locationProductIds = array();
        // foreach ($locationProducts as $key => $locationProd) {
        //     if (!in_array($locationProd->product_id, $locationProductIds)) {
        //         array_push($locationProductIds, $locationProd->product_id);
        //     }
        // }

        // $productIds = array();
        // $promotionProducts = $promotion->products;
        // foreach ($promotionProducts as $key => $promoProduct) {
        //     array_push($productIds, $promoProduct->id);
        // }

        // $products = Product::whereIn('id',$locationProductIds)->get();

        // return view('admin.promotion.edit_choose_products', compact('products','promotion','productIds'));

        return view('admin.coupon.edit_choose_products', compact('coupon','conditions_data'));
    }

    public function removeProductByCouponId($couponId,$productId)
    {
        $productCoupon = CouponProduct::whereCouponId($couponId)->whereProductId($productId)->first();
        $productCoupon->delete();
        
        return response()->json(['status'=>true]);
    }

    public function saveImage($modelObejct,$file, $fileType)
    {
        $fileName = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
        $fileExten = $file->getClientOriginalExtension();
        $fileCompName = rand().'_'.$modelObejct->id.'.'.$fileExten;
        $file->move('coupon/images/',$fileCompName);

        $fileCompName = url('/').'/coupon/images/'.$fileCompName;

        if ($fileType === 'file') {
            $modelObejct->image = $fileCompName;
        }else{
            $modelObejct->barcode_image = $fileCompName;
        }

        $modelObejct->save();
    }

    public function generateBarcode()
    {
        $barcode = str_shuffle(str_pad(mt_rand(10000000001, 99999999999),11,'1',STR_PAD_RIGHT));
        return $barcode;
    }

    public function couponsFilter($filter)
    {
        $coupons = array();
        if (isset($filter)) {
            if ($filter === 'active') {
                $coupons = Coupon::Active()->latest()->paginate(10);
            }else{
                $coupons = Coupon::Expire()->latest()->paginate(10);
            }
        }

       return view('admin.coupon.index', compact('coupons', 'filter'));
    }
}
