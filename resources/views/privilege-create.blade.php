@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="card">
            	<div class="card-header">Add a Privilege</div>

                <div class="card-body">
                    <form action="{{route('privileges.store')}}" method="post" id="addPrivilegeForm">
					    @csrf
					    {{-- <input type="hidden" name="privilegeName" id="privilegeName" value="{{$parameters['privilege']}}"> --}}
					    <div class="form-row">
					        <div class="col">
					            <label for="privilegeName">Privilege Name:</label><br>
					            <input type="text" id="privilegeName" name="privilegeName" class="form-control" placeholder="Privilege Name" autocomplete="off">
					        </div>
					    </div>
					    <br>
					    
					    <button type="submit" id="submitPrivilege" class="btn btn-block btn-primary btn-lg">Submit</button>
					    
					</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
