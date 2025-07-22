<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormSubmitted;
use Illuminate\Http\Request;
class ContactController extends Controller
{
    /**
     * Summary of index
    * Display a listing of the resource.
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        //
        $contacts= Contact::latest()->paginate(10);
        return view('admin.contacts.index',compact('contacts'));
    }


    public function create()
    {
        //
    }

    /**
     * Summary of store
     * Store a newly created contact form and send email to admin and superadmin emails
     * @param \App\Http\Requests\StoreContactRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreContactRequest $request)
    {

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
     * Summary of showForm
     * Showing the contact form for users to fill
     * @return \Illuminate\Contracts\View\View
     */
    public function showForm()
    {
        //
        return view ('contact.form');
    }
    /**
     * Summary of show
     * Displaying the details of the users' requests
     * @param mixed $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id){
        $contact =Contact::findOrFail($id);

        //Mark as read if status is still new when opening the details of the request
        if ($contact['status'] === 'new') {
            $contact->status = 'read';
            $contact->save();
        }
        return view('admin.contacts.show',compact('contact'));
    }


    /**
     * Updatating status of the requests from users
     */

    public function updateStatus(Request $request,$id){
        $contact=Contact::findOrFail($id);
        $validated=$request->validate([
            'status'=>'required|in:new,read,replied'
        ]);
        $contact->status=$validated['status'];
        $contact->save();
        return back()->with('success','Status updated successfully!');
    }

    // public function edit(Contact $contact)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateContactRequest $request, Contact $contact)
    // {
    //     //
    // }

    /**
     * Summary of destroy
     * Deleting the requests from users
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        //
        $contact=Contact::findOrFail($id);
        $contact->delete();
        return back()->with('success','Request has been archieved');

    }
}
