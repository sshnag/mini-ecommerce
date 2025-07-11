@extends('adminlte::page')

@section('title', 'User Management')

@section('content_header')
    <h1>User Management</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered" id="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@section('js')
<script>
    $(function() {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.users.datatable') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                {
                    data: 'role',
                    name: 'role',
                    render: function(data) {
                        return `<span class="badge badge-${data === 'admin' ? 'success' : 'info'}">${data}</span>`;
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return `
                            <a href="${data.edit}" class="btn btn-xs btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-xs btn-danger delete-btn" data-id="${data.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        `;
                    }
                }
            ]
        });
    });
</script>
@stop
