@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Your Children</div>
                        @foreach($children as $child)
                            <div class="card-body">
                                <h3>{{$child->name}}</h3>
                                <table class="table text-center">
                                    <thead class="thead-dark">
                                        <tr>
                                            @foreach($availablePrivileges as $privilege)
                                                <td>{{$privilege['name']}}</td>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @foreach($availablePrivileges as $privilege)
                                                <td class="{{$child->privilegeStatus[$privilege['name']] ? 'table-danger' : 'table-success'}}">
                                                    {{$child->privilegeStatus[$privilege['name']] ? 'X' : 'Y'}}
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
