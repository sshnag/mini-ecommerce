<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;


class AddressController extends Controller
{
    /**
     * Summary of index
     * Displaying the address data
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        //
        $addresses=Address::where('user_id'!==Auth::id())->get();
        return view('profile.addresses',compact('addresses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Summary of store
     * Storing the address data
     * @param \App\Http\Requests\StoreAddressRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreAddressRequest $request)
    {
        //
        $data=$request->validated();
        $data['user_id']=Auth::id();
        Address::create($data);
        return back()->with('success','Address saved');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
