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
            	<div class="card-header">Add a Child</div>

                <div class="card-body">
                    <form action="{{route('child.store')}}" method="post" id="addChildForm">
					    @csrf
					    {{-- <input type="hidden" name="privilegeName" id="privilegeName" value="{{$parameters['privilege']}}"> --}}
					    <div class="form-row">
					        <div class="col">
					            <label for="childName">Child Name:</label><br>
					            <input type="text" id="childName" name="childName" class="form-control" placeholder="Child Name" autocomplete="off">
					        </div>
					    </div>
					    <br>

					    <div class="form-row">
					        <div class="col">
					            <label for="birthdate">Birth Date:</label><br>
					            <input type="text" id="birthdate" name="birthdate" class="form-control" placeholder="BirthDate" autocomplete="off">
					        </div>
					    </div>
					    <br>
					    
					    <button type="submit" id="submitChild" class="btn btn-block btn-primary btn-lg">Submit</button>
					    
					</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
