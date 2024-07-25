<button class="btn btn-danger btn-sm delete-url" data-id="{{ $row->id }}">Delete</button>
@if($row->is_active)
    <button class="btn btn-secondary btn-sm deactivate-url" data-id="{{ $row->id }}">Deactivate</button>
@else
    <button class="btn btn-primary btn-sm deactivate-url" data-id="{{ $row->id }}">Activate</button>
@endif
