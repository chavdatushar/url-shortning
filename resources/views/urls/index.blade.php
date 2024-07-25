@extends('layouts.app')

@section('title', 'Shortened URLs')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Your Shortened URLs</h1>
        {!! Form::open(['url' => '#','id' => 'shorten-url-form',"class"=>"mb-3"]) !!}
        
            <div class="input-group">
                {!! Form::text('original_url', '',['class'=>"form-control","placeholder"=>"Enter URL to shorten","required"=>true]); !!}
                {!! Form::button('Shorten',['type'=>"submit",'class'=>'btn btn-primary']) !!}
            </div>
        {!! Form::close() !!}
        <table id="urls-table" class="table table-striped">
            <thead>
                <tr>
                    <th>Original URL</th>
                    <th>Short URL</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#shorten-url-form').validate({
            rules: {
                original_url: {
                    required: true,
                    url: true
                }
            },
            messages: {
                original_url: {
                    required: "Please enter a URL",
                    url: "Please enter a valid URL"
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: '{{ route('urls.store') }}',
                    method: 'POST',
                    data: $(form).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Short URL: ' + response.data.url.short_url);
                            $('#urls-table').DataTable().ajax.reload();
                            $('[name=original_url]').val("");
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.message);
                    }
                });
            }
        });
        var table = $('#urls-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('urls.index') }}',
            columns: [
                { 
                    data: 'original_url',
                    name: 'original_url',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            return `
                                <div class="editable-container" data-id="${row.id}">
                                    <span class="original-url-text">${data}</span>
                                    <input type="url" class="form-control original-url-input d-none" value="${data}" required>
                                    <button class="btn btn-success btn-sm save-url d-none">Save</button>
                                    <button class="btn btn-secondary btn-sm cancel-edit d-none">Cancel</button>
                                    <button class="btn btn-warning btn-sm edit-url">Edit</button>
                                </div>`;
                        }
                        return data;
                    },
                    orderable: false,
                    searchable: false
                },
                { 
                    data: 'short_url',
                    name: 'short_url',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            return `<a target="_blank" href="${data}">${data}</a>`;
                        }
                        return data;
                    },
                    orderable: false,
                    searchable: false
                },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });


        $('#urls-table').on('click', '.edit-url', function() {
            var $container = $(this).closest('.editable-container');
            $container.find('.original-url-text').addClass('d-none');
            $container.find('.original-url-input').removeClass('d-none');
            $container.find('.save-url').removeClass('d-none');
            $container.find('.cancel-edit').removeClass('d-none');
            $(this).addClass('d-none');
        });

        $('#urls-table').on('click', '.save-url', function() {
            var $container = $(this).closest('.editable-container');
            var id = $container.data('id');
            var originalUrl = $container.find('.original-url-input').val();

            $.ajax({
                url: '{{ route('urls.update', '') }}/' + id,
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    original_url: originalUrl
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        table.ajax.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });

        $('#urls-table').on('click', '.cancel-edit', function() {
            var $container = $(this).closest('.editable-container');
            $container.find('.original-url-text').removeClass('d-none');
            $container.find('.original-url-input').addClass('d-none');
            $container.find('.save-url').addClass('d-none');
            $container.find('.edit-url').removeClass('d-none');
            $(this).addClass('d-none');
        });

        $('#urls-table').on('click', '.delete-url', function() {
            const id = $(this).data('id');
            $.ajax({
                url: '{{ route('urls.destroy', '') }}/' + id,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        table.ajax.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });

        $('#urls-table').on('click', '.deactivate-url', function() {
            const id = $(this).data('id');
            $.ajax({
                url: '{{ route('urls.deactivate', '') }}/' + id,
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        table.ajax.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });
    });
</script>
@endsection
