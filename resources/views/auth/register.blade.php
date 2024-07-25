@extends('layouts.app')
@section('title','Register')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Register
            </div>
            <div class="card-body">
                {!! Form::open(['url' => '#','id' => 'register-form']) !!}
                <div class="md-3">
                    <label class="form-label">Name</label>
                    {!! Form::text('name', "", ["class"=>"form-control"]) !!}
                </div>
                <div class="md-3">
                    <label class="form-label">Email</label>
                    {!! Form::email('email', "", ["class"=>"form-control"]) !!}
                </div>
                <div class="md-3">
                    <label class="form-label">Password</label>
                    {!! Form::password('password', ['class' => 'form-control']) !!}
                </div>
                <div class="md-3">
                    <label class="form-label">Confirm Password</label>
                    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                </div>
                {!! Form::button('Register',['type'=>"submit",'class'=>'btn btn-primary my-3']) !!}
            {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $('#register-form').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: '{{ route('do-register') }}',
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alert(response.message);
                window.location.href = '{{ route('register') }}';
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });
    })
    
</script>
@endsection