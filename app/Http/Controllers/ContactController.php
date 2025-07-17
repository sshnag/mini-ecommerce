<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormSubmitted;
use Illuminate\Support\Facades\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $contacts= Contact::latest()->paginate(10);
        return view('admin.contacts.index',compact('contacts'));
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
    public function show($id){
        $contact =Contact::findOrFail($id);

        //Mark as read if status is still new
        if ($contact->status==='new') {
            $contact->update(['status'=>'read']);
        }
        return view('admin.contacts.show',compact('contact'));
    }

    public function updateStatus(Request $request,$id){
        $contact=Contact::findOrFail($id);
        $validated=$request->validate([
            'status'=>'required|in:new,read,replied'
        ]);
        $contact->status=$validated['status'];
        $contact->save();
        return back()->with('success','Status updated successfully!');
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
    public function destroy($id)
    {
        //
        $contact=Contact::findOrFail($id);
        $contact->delete();
        return back()->with('success','Request has been archieved');

    }
}
