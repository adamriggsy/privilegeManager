@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Your Children</div>
                        @foreach($children as $child)
                            <div class="card-body">
                                <h3>{{$child->name}} <small class="float-right"><a href="{{route('child.manage', ['id' => $child->id])}}">Manage</a></small></h3>
                                <table class="table text-center">
                                    
                                    <tbody>
                                        <tr>
                                            @foreach($child->privilegeStatus as $name => $status)
                                                    <td class="{{$status ? 'table-danger' : 'table-success'}} managePrivilege">
                                                        {{$name}}
                                                    </td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
