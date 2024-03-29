@if($jsonRequest)
    <html>
        <head>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
            <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
            <link rel="dns-prefetch" href="//fonts.gstatic.com">
            <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
            <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        
            <!-- Styles -->
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
            <style>
                form {
                    width:90%;
                    margin:0 auto;
                }
            </style>
            @include('includes.child.privilegeManageJS')
        </head>
        <body>
    @php
        $formRoute = "child.privilege.ban.process.json";
    @endphp
@else
    @php
        $formRoute = "child.privilege.ban.process";
    @endphp
@endif

<form action="{{route($formRoute, ['id' => $child->id])}}{{$jsonRequest ? '?api_token=' . $api_token : ''}}" method="post" id="banPrivilegeForm">
    @csrf
    {{-- <input type="hidden" name="privilegeName" id="privilegeName" value="{{$parameters['privilege']}}"> --}}
    <p>
        <div class="form-group">
            <label for="privilege">Privilege to Ban: </label>
            
            <select name="privilege" class="form-control">
                @foreach($allPrivileges as $aPrivilege)
                    @if($jsonRequest)
                        <option value="{{$aPrivilege->id}}" {{$parameters['privilege'] == $aPrivilege->name ? 'selected' : ''}}>{{$aPrivilege->name}}</option>
                    @else
                        <option value="{{$aPrivilege->id}}">{{$aPrivilege->name}}</option> 
                    @endif
                @endforeach
            </select>
        </div>
    </p>
    <div class="form-row">
        <div class="col">
            <label for="datepicker_start">Start Date:</label><br>
            <input type="text" id="datepicker_start_ban" name="datepicker_start" class="datepicker form-control" placeholder="Start Date" autocomplete="off" {{$jsonRequest ? "readonly" : ""}}>
        </div>
    
        <div class="col">
            <label for="datepicker_end">End Date:</label><br>
            <input type="text" id="datepicker_end_ban" name="datepicker_end" class="datepicker form-control" placeholder="End Date" autocomplete="off" {{$jsonRequest ? "readonly" : ""}}>
        </div>
    </div>
    <br>
    
    <button type="submit" id="submitBan" class="btn btn-block btn-primary btn-lg">Submit</button>
    
</form>

@if($jsonRequest)
            
        </body>
    </html>
@endif