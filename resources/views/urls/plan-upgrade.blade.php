@extends('layouts.app')

@section('title', 'Plan Upgrade')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Plan Upgrade</div>
            <div class="card-body">
                {!! Form::open(['url' => '#','id' => 'upgrade-plan-form']) !!}
                    @csrf
                    <div class="mb-3">
                        <label>Choose Plan:</label>
                        {!! Form::select('plan', ['10' => '10', '1000' => '1000', 'Unlimited' => 'Unlimited'],Auth::user()->plan,["class"=>"form-select"]); !!}
                        
                    </div>
                    {!! Form::button('Upgrade',['type'=>"submit",'class'=>'btn btn-primary']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#upgrade-plan-form').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: '{{ route('plan.upgrade') }}',
            method: 'POST',
            data: $(this).serialize(),
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
</script>
@endsection
