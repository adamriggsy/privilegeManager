<form action="{{route('child.privilege.restore.process', ['id' => $child->id])}}" method="post" id="restorePrivilegeForm">
    @csrf
    {{-- <input type="hidden" name="privilegeName" id="privilegeName" value="{{$parameters['privilege']}}"> --}}
    <p>
        <div class="form-group">
            <label for="privilege">Privilege to Restore: </label>
            <select name="privilege" class="form-control">
                @foreach($allPrivileges as $aPrivilege)
                    {{-- <option value="{{$aPrivilege->id}}" {{$parameters['privilege'] == $aPrivilege->name ? 'selected' : ''}}>{{$aPrivilege->name}}</option> --}}
                    <option value="{{$aPrivilege->id}}">{{$aPrivilege->name}}</option>
                @endforeach
            </select>
        </div>
    </p>
    <div class="form-row">
        <div class="col">
            <label for="datepicker_start">Start Date:</label><br>
            <input type="text" id="datepicker_start_restore" name="datepicker_start" class="datepicker form-control" placeholder="Start Date" autocomplete="off">
        </div>
    
        <div class="col">
            <label for="datepicker_end">End Date:</label><br>
            <input type="text" id="datepicker_end_restore" name="datepicker_end" class="datepicker form-control" placeholder="End Date" autocomplete="off">
        </div>
    </div>
    <br>
    <button type="submit" id="submitRestore" class="btn btn-block btn-primary btn-lg">Restore Privilege</button>    
</form>