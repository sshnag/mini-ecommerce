@extends('adminlte::page')

@section('title', 'Contact Message Details')

@section('content_header')
    <h1 class="fw-bold text-dark">Contact Message Details</h1>
@stop

@section('content')
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-light">
            <strong>From:</strong> {{ $contact->name }} ({{ $contact->email }})
        </div>
        <div class="card-body">
            <p><strong>Subject:</strong> {{ $contact->subject }}</p>
            <p><strong>Message:</strong></p>
            <div class="border rounded p-3 bg-light">
                {{ $contact->message }}
            </div>
            <hr>
            <p><strong>Status:</strong>
                <span class="badge
                    @if($contact->status == 'new') bg-primary
                    @elseif($contact->status == 'read') bg-warning
                    @elseif($contact->status == 'replied') bg-success
                    @endif
                ">
                    {{ ucfirst($contact->status) }}
                </span>
            </p>
            <p><strong>Submitted on:</strong> {{ $contact->created_at->format('d M Y H:i') }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
@stop
