<?php

namespace App\Http\Controllers;

use App\Promotion;
use Illuminate\Http\Request;
use App\Country;
use App\Product;
use App\ProductPromotion;
use App\Location;
use App\LocationProduct;
use App\Notification;
use App\Coupon;
use App\Helper;

class PromotionController extends Controller
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
        $promotions = Promotion::latest()->paginate(10);       
        return view('admin.promotion.index', compact('promotions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        $promotion = Promotion::latest()->first();
        $promoName = (is_null($promotion['id'])) ? 1 : $promotion['id']+1;       
        $promoName = 'PROMO-NO-'.$promoName;
        return view('admin.promotion.create', compact('countries','promoName'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'         => 'required|string|max:255|unique:promotions,title',
            'country_id'    => 'required',
            'promotion_type'    => 'required',
            'start_date'    => 'required',
            'end_date'      => 'required',
            'description'   => 'required',
            'file'          => 'required|image|mimes:jpeg,png,jpg',
            'start_time'     => 'required',
            'end_time'     => 'required',
        ]);

        $start_time = strtotime($request->start_date.' '.$request->start_time);
        $end_time = strtotime($request->end_date.' '.$request->end_time);
        $dStart = date("Y-m-d H:i:s", $start_time);
        $dEnd = date("Y-m-d H:i:s", $end_time);
        $dS =  new \DateTime($dStart);
        $dE =  new \DateTime($dEnd);

        $promotion = new Promotion;
        $promotion->title = $request->title;
        $promotion->country_id = $request->country_id;
        $promotion->start_date = $dS;
        $promotion->end_date = $dE;
        $promotion->description = $request->description;
        $promotion->type = $request->promotion_type;
        $promotion->save();

        if ($request->hasFile('file')) {
            $file = $request->file;
            $fileName = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
            $fileExten = $file->getClientOriginalExtension();
            $fileCompName = rand().'_'.$promotion->id.'.'.$fileExten;
            $file->move('promotion/images/',$fileCompName);

            $fileCompName = url('/').'/promotion/images/'.$fileCompName;

            $promotion->image = $fileCompName;
            $promotion->save();
        }

        return redirect()->route('choose_products_for_promotion',['promotionId'=>$promotion->id,'countryId'=>$request->country_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function show(Promotion $promotion)
    {
        return view('admin.promotion.details', compact('promotion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $countries = Country::all();
        $promotion = Promotion::find($id);
        return view('admin.promotion.edit', compact('countries','promotion'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Promotion $promotion)
    {
        $this->validate($request, [
            'title'       => 'required|string|max:255',
            'country_id'  => 'required',
            'start_date'  => 'required',
            'end_date'    => 'required',
            'description' => 'required',
            'file'        => 'image|mimes:jpeg,png,jpg',
            'start_time'  => 'required',
            'end_time'    => 'required',
        ]);

        $start_time = strtotime($request->start_date.' '.$request->start_time);
        $end_time = strtotime($request->end_date.' '.$request->end_time);
        $dStart = date("Y-m-d H:i:s", $start_time);
        $dEnd = date("Y-m-d H:i:s", $end_time);
        $dS =  new \DateTime($dStart);
        $dE =  new \DateTime($dEnd);

        $prom = Promotion::find($promotion->id);
        $prom->title = $request->title;
        $prom->country_id = $request->country_id;
        $prom->start_date = $dS;
        $prom->end_date = $dE;
        $prom->description = $request->description;
        $prom->save();

        $fileCompName = null;
        if ($request->hasFile('file')) {
            $file = $request->file;
            $fileName = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
            $fileExten = $file->getClientOriginalExtension();
            $fileCompName = rand().'_'.$prom->id.'.'.$fileExten;
            if ($fileCompName != $prom->image) {

                $fileToBeDeleted = Helper::getFileNameForDelete($promotion->image);

                $fileToBeDeleted = public_path().'/promotion/images/'.$fileToBeDeleted;
                @unlink($fileToBeDeleted);
                $file->move('promotion/images/',$fileCompName);

                $fileCompName = url('/').'/promotion/images/'.$fileCompName;

                $prom->image = $fileCompName;
                $prom->save();
            }
        }

        return redirect()->route('edit_choosen_products_for_promotion',['promotionId'=>$promotion->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promotion $promotion)
    {
        $promoNotifications = Notification::whereObject('promotion')->whereObjectId($promotion->id)->get();
        foreach ($promoNotifications as $key => $notification) {
            $notification->delete();
        }

        $promotion->delete();

        $fileToBeDeleted = Helper::getFileNameForDelete($promotion->image);
        
        $fileToBeDeleted = public_path().'/promotion/images/'.$fileToBeDeleted;
        @unlink($fileToBeDeleted);
        return redirect()->route('promotions.index')->with('message','Promotion has been deleted successfully!');
    }

    public function showProductsByCountry($promotionId,$countryId)
    {
        $promotion = Promotion::find($promotionId);

        // $locations = Location::where('country_id',$countryId)->get();
        // $locationIds = array();
        // foreach ($locations as $key => $location) {
        //     array_push($locationIds, $location->id);
        // }

        // $locationProducts = LocationProduct::whereIn('location_id',$locationIds)->get();
        // $productIds = array();
        // foreach ($locationProducts as $key => $locationProd) {
        //     if (!in_array($locationProd->product_id, $productIds)) {
        //         array_push($productIds, $locationProd->product_id);
        //     }
        // }

        // $products = Product::whereIn('id',$productIds)->get();
        // return view('admin.promotion.choose_products', compact('products','promotion'));
        return view('admin.promotion.choose_products', compact('promotion'));
    }

    public function getProductsByUpcByCountry($countryId,$upc)
    {
        $product = Product::whereUpc($upc)->whereCountryId($countryId)->first();

        // $locations = Location::where('country_id',$countryId)->get();
        // $locationIds = array();
        // foreach ($locations as $key => $location) {
        //     array_push($locationIds, $location->id);
        // }

        // $locationProducts = LocationProduct::whereIn('location_id',$locationIds)->get();
        // $checkProduct = false;
        // foreach ($locationProducts as $key => $locationProd) {
        //     if ($locationProd->product_id === $product->id) {
        //         $checkProduct = true;
        //         break;
        //     }
        // }
        // dd($product);
        // if ($checkProduct) {
        if (isset($product) && count((array)$product) > 0) {
            return response()->json(['product'=>$product]);
        }else{
            return response()->json(['product'=>[]]);
        }
    }

    // public function getProductsPromotionByPage($countryId,$pageId)
    // {
    //     $products = Product::with(['locations'=> function($query) use($countryId) {
    //         $query->where('country_id', $countryId);
    //     }])->get();

    //     $products = $products->forPage($pageId,4)->all();
    //     return response()->json(['products'=>$products]);
    // }

    public function savePromotionProducts(Request $request, $promotionId)
    {
        dd($request);
        $promotion = Promotion::find($promotionId);
        $coupons = $request->coupon;
        if($promotion->type == 'bundle'){
            Coupon::where('promotion_id', $promotionId)->update([
                'promotion_id' => null,
            ]); 
            if (isset($coupons)) {
                foreach ($coupons as $key => $value) {
                    $coupon = Coupon::find($value);
                    Coupon::where('id', $coupon->id)->update([
                        'promotion_id' => $promotionId,
                    ]); 
                }
                $message = 'Product successfully added into promotion';
            }else{
                $message = 'Promotion successfully saved without any product';
            }
        }
        if($promotion->type == 'product'){
            if (isset($request->products)) {
                foreach ($request->products as $key => $promotionPrice) {
                 $productPromotion = ProductPromotion::updateOrCreate([
                    'promotion_id' => $promotionId,
                    'product_id'   => $key,
                 ],
                 [
                    'promotion_id' => $promotionId,
                    'product_id'   => $key,
                    'sale_price'   => $promotionPrice,
                 ]);  
                }
                $message = 'Product successfully added into promotion';
            }else{
                $message = 'Promotion successfully saved without any product';
            }
        }

        return redirect()->route('promotions.index')->with('message',$message);
    }

    public function editChoosenProductsForPromotion($promotionId)
    {
        $promotion = Promotion::find($promotionId);

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

        return view('admin.promotion.edit_choose_products', compact('promotion'));
    }

    public function removeProductByPromotionId($promotionId,$productId)
    {
        $productPromotion = ProductPromotion::wherePromotionId($promotionId)->whereProductId($productId)->first();
        $productPromotion->delete();
        
        return response()->json(['status'=>true]);
    }

    public function promotionsFilter($filter)
    {
        $promotions = array();
        if (isset($filter)) {
            if ($filter === 'active') {
                $promotions = Promotion::Active()->latest()->paginate(10);
            }else{
                $promotions = Promotion::Expire()->latest()->paginate(10);
            }
        }

       return view('admin.promotion.index', compact('promotions', 'filter'));
    }
}
