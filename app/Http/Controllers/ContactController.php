<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormSubmitted;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreContactRequest $request)
    {
        //
        $validated= $request->validated();
        //Saving to database
        $contact= Contact::create($validated);

        //sending email to all admins and superadmin
        $admins=User::role(['admin','superadmin'])->get();

        foreach($admins as $admin){
            Mail::to($admin->email)->send(new ContactFormSubmitted($contact));

        }
        return back()->with('success','Your Message has been sent!');
    }

    /**
     * Display the specified resource.
     */
    public function showForm()
    {
        //
        return view ('contact.form');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        //
    }
}
