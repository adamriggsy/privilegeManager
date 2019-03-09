@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div id="updateSuccess" class="alert alert-success d-none" role="alert">
                You have updated the available privileges for {{$child->name}}
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>Privileges available to <a href="{{route('child.manage', ['id' => $child->id])}}">{{$child->name}}</a></h4>
                </div>

                <div class="card-body">
                    
                    <p>
                        @foreach($child->user->privileges() as $privilege)
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    name='privileges[]' 
                                    type="checkbox" 
                                    value="{{$privilege->id}}" 
                                    id="privilege_{{$privilege->id}}"
                                    {{in_array($privilege->id, $child->privileges) ? 'checked' : ''}}
                                >
                                
                                <label 
                                    class="form-check-label" 
                                    for="privilege_{{$privilege->id}}"
                                >
                                    {{$privilege->name}}
                                </label>
                            </div>
                        @endforeach
                    </p>

                    <p>
                        <a href="#" id="privilegeSubmit" class="btn btn-lg btn-primary btn-block">Update Privileges</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script>
        $("#privilegeSubmit").on('click touch', function(e){
            e.preventDefault();
            
            var privileges = [];
            $.each($("input[name='privileges[]']:checked"), function(){            
                privileges.push(parseInt($(this).val()));
            });
            
            var data = {'privileges' : privileges};

            $.ajax({
                url: '{{route('childPrivilegeUpdate', ['id' => $child->id])}}',
                type: 'post',
                data: {
                    'privileges': privileges
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                dataType: 'json',
                success: function (data) {
                    if(data){
                        $("#updateSuccess").removeClass('d-none');

                        setTimeout(function(){
                            $("#updateSuccess").addClass('d-none');
                        },3000);
                    }
                }
            });

        });
    </script>
@endsection