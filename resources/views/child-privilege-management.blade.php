@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h3>{{$child->name}}</h3>
                    @if($action == 'ban')
                        <form action="{{route('child.privilege.ban.process', ['id' => $child->id])}}" method="post" id="banPrivilegeForm">
                    @else
                        <form action="{{route('child.privilege.restore.process', ['id' => $child->id])}}" method="post" id="restorePrivilegeForm">
                    @endif
                        @csrf
                        <input type="hidden" name="privilegeName" id="privilegeName" value="{{$parameters['privilege']}}">
                        <p>
                            <div class="form-group">
                                @if($action == 'ban')
                                    <label for="privilege">Privilege to Ban: </label>
                                @else
                                    <label for="privilege">Privilege to Restore: </label>
                                @endif
                                <select name="privilege" class="form-control">
                                    @foreach($allPrivileges as $aPrivilege)
                                        <option value="{{$aPrivilege->id}}" {{$parameters['privilege'] == $aPrivilege->name ? 'selected' : ''}}>{{$aPrivilege->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </p>
                        <div class="form-row">
                            <div class="col">
                                <label for="datepicker_start">Start Date:</label><br>
                                <input type="text" id="datepicker_start" name="datepicker_start" class="datepicker form-control" placeholder="Start Date">
                            </div>
                        
                            <div class="col">
                                <label for="datepicker_end">End Date:</label><br>
                                <input type="text" id="datepicker_end" name="datepicker_end" class="datepicker form-control" placeholder="End Date">
                            </div>
                        </div>
                        <br>
                        @if($action == 'ban')
                            <button type="submit" id="submitBan" class="btn btn-block btn-primary btn-lg">Submit</button>
                        @else
                            <button type="submit" id="submitRestore" class="btn btn-block btn-primary btn-lg">Restore Privilege</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script>
        $(function(){
            var from = $( "#datepicker_start" ).datepicker({
                dateFormat : 'yy-mm-dd',
                changeMonth: true,
                numberOfMonths: 1
            }).on( "change", function() {
                to.datepicker( "option", "minDate", getDate( this ) );
            }).datepicker('setDate', "{{isset($parameters['date']) ? $parameters['date'] : ''}}");

            var to = $( "#datepicker_end" ).datepicker({
                dateFormat : 'yy-mm-dd',
                changeMonth: true,
                numberOfMonths: 1
            }).on( "change", function() {
                from.datepicker( "option", "maxDate", getDate( this ) );
            }).datepicker('setDate', "{{isset($parameters['date']) ? $parameters['date'] : ''}}");
        });

        function getDate( element ) {
            var date;
            var dateFormat = 'yy-mm-dd';
            try {
                date = $.datepicker.parseDate( dateFormat, element.value );
            } catch( error ) {
                date = null;
            }
            return date;
        }
    </script>
    <script>
        $("#submitBan").on('click touch', function(e){
            //e.preventDefault();

        });
    </script>
@endsection