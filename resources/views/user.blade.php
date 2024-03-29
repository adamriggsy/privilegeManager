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
                <div class="card-header">{{$user->name}}</div>

                <div class="card-body">
                    @foreach($user->children as $child)
                        <p><a href="{{route('child.manage', ['id' => $child->id])}}">{{$child->name}}</a> - {{$child->birthdate}}</p>
                    @endforeach

                    <a href="{{route('child.create')}}">Add Child</a>

                    <hr>

                    <h4>Privileges available to your children</h4>
                    <p>
                        @foreach($user->privileges() as $privilege)
                            {{$privilege->name}}<br>
                        @endforeach
                    </p>
                    <p>
                        <a href="{{route('privileges.create')}}">Add Privilege</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
