@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Your Children</div>

                <div class="card-body">
                    <table class="table">
                        <tbody>
                            @foreach($children as $child)
                                <tr>
                                    <td><h5>{{$child->name}}</h5></td>
                                    <td><a href="{{route('children.edit', ['child' => $child])}}">Edit</a></td>
                                    <td><a href="{{route('children.manage', ['child' => $child])}}">Manage</a></td>
                                    <td>Delete</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><button href="" class="btn btn-primary">Add Child</button></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
