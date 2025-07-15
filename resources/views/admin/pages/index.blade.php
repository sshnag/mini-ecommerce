@extends('adminlte::page')

@section('title', 'Site Pages')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">

<div class="admin-section">
    <div class="section-header">
        <h2>Pages</h2>
        <a href="{{ route('admin.pages.create') }}" class="btn-add">+ Add Page</a>
    </div>

    <div class="table-wrapper">
        <table>
            <thead><tr><th>Title</th><th>Slug</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($pages as $page)
                <tr>
                    <td>{{ $page->title }}</td>
                    <td>{{ $page->slug }}</td>
                    <td>{{ $page->is_published ? 'Published' : 'Draft' }}</td>
                    <td>
                        <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn-action edit">Edit</a>
                        <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn-action delete">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
