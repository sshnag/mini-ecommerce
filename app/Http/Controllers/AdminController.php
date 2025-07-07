<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Database\Eloquent\ComparesCastableAttributes;
use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Summary of index
     * displaying users list admin view
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        //
        $admins=User::role(['admin','supplier'])->get();
        return view('admin.users.index',compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
