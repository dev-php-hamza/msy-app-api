<?php

namespace App\Http\Controllers;

use App\Offer;
use Illuminate\Http\Request;
use App\Country;
use App\Product;
use App\OfferProduct;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = Offer::latest()->paginate(10);
        return view('admin.offer.index',compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('admin.offer.create', compact('countries'));
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
            'name' => 'required|string|max:255',
            'country_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $offer = Offer::create($request->all());
        return redirect()->route('choose_products_for_offer',['offerId'=>$offer->id,'countryId'=>$request->country_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show(Offer $offer)
    {
        dd(Offer::all());
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function edit(Offer $offer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, offer $offer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy(offer $offer)
    {
        //
    }

    public function saveOfferProducts(Request $request, $offerId)
    {

        foreach ($request->products as $key => $productId) {
         $productPromotion = OfferProduct::updateOrCreate([
            'offer_id' => $offerId ,
            'product_id' => $productId,
         ],
         [
            'promotion_id' => $offerId ,
            'product_id' => $productId,
         ]);  
        }

        return redirect()->route('offers.index')->with('message','All promotions are successfully added into '.$request->offer_name);
    }

    public function showProductsByCountry($offerId,$countryId)
    {
        $offer = Offer::find($offerId);
        $products = Product::with(['locations'=> function($query) use($countryId) {
            $query->where('country_id', $countryId);
        }])->get();
        return view('admin.offer.choose_products', compact('products','offer'));
    }
}
