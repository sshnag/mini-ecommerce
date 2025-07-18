@extends('adminlte::page')

@section('title', 'Contact Message Details')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="fw-bold text-dark">Contact Message Details</h1>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
@stop

@section('content')
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-light">
            <strong>From:</strong> {{ $contact->name }} ({{ $contact->email }})
        </div>
        <div class="card-body">
            <p><strong>Subject:</strong> {{ $contact->subject }}</p>
            <p><strong>Message:</strong></p>
            <div class="border rounded p-3 bg-black">
                {{ $contact->message }}
            </div>
            <hr>
            <p><strong>Status:</strong>
                <span class="badge text-info"
                    style="
            background-color:
            @if ($contact->status == 'new') #C4B5FD
            @elseif($contact->status == 'read') #FBCFE8
            @elseif($contact->status == 'replied') #A5F3FC @endif;
        ">
                    {{ ucfirst($contact->status) }}
                </span>
            </p>

            <p><strong>Submitted on:</strong> {{ $contact->created_at->format('d M Y') }}</p>
        </div>
        <div class="card-footer">

        </div>
    </div>
@stop
