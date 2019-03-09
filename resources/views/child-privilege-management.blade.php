@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h3>{{$child->name}}</h3>
                    @if($action == 'ban')
                        @include('includes.child.privilege-manager-ban')
                    @else
                        @include('includes.child.privilege-manager-restore')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    @include('includes.child.privilegeManageJS')
@endsection