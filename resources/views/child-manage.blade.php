@php
    use App\Http\Controllers\Helpers;
@endphp

@extends('layouts.app')

@section('localStyles')
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />

    <style>
        .managePrivilege {
            cursor: pointer;
        }

        .managePrivilege:hover {
            background-color: #000;
            color:#fff;
        }

        .fc-listWeek-view .fc-list-table .fc-list-item {
            display: flex;
            float: left;
        }

        .fc-listWeek-view .fc-list-table .fc-list-item-time {
            display: none;
        }
    </style>
@endsection

@section('headJavascript')
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
    <script>

        $(document).ready(function() {
            var calendar = $('#calendar').fullCalendar({
                defaultView: 'listWeek',
                firstDay: 1,
                events: {
                    url: '{{route('childPrivilegeFeed', ['id' => $child->id])}}',
                    type: 'GET', // Send post data
                    error: function() {
                        alert('There was an error while fetching events.');
                    }
                },
            });

            if(calendar) {
                var calHeight = $(window).height();
                $('#calendar').fullCalendar('option', 'contentHeight', calHeight);
            };
        });
    </script>
@endsection

@section('javascript')
    <script>
        $("body").on('click touch', ".managePrivilege, .fc-list-item", function(e){
            e.preventDefault();
            var $this = $(this);
            if($this.hasClass('fc-list-item')){
                var isBanned = $this.attr('class').replace('fc-list-item', '').trim();
                var theDate = $this.prevAll(".fc-list-heading").data('date');
                var privilegeName = $this.find('.fc-list-item-title').text().trim();
            }else{
                var isBanned = $this.attr('class').replace('managePrivilege', '').trim().replace('table-', '');    
                var theDate = $this.closest("tr").data('date');
                var privilegeName = $this.text().trim();
            }
            
            if(isBanned == 'danger'){
                $("#bannedPrivilege").modal('toggle').data({'date' : theDate, 'privilege' : privilegeName});
                var dateNew = moment(theDate);
                
                $("#bannedPrivilege #datepicker_start_restore").datepicker('setDate', dateNew.format('YYYY-MM-DD'));
                $("#bannedPrivilege #datepicker_end_restore").datepicker('setDate', dateNew.format('YYYY-MM-DD'));
                
                $("#bannedPrivilege select[name='privilege'] option").each(function() {
                    //may want to use $.trim in here
                    this.selected = (this.text == privilegeName);
                });
            }else{
                $("#earnedPrivilege").modal('toggle').data({'date' : theDate, 'privilege' : privilegeName});
                
                var dateNew = moment(theDate);
                
                $("#earnedPrivilege #datepicker_start_ban").datepicker('setDate', dateNew.format('YYYY-MM-DD'));
                $("#earnedPrivilege #datepicker_end_ban").datepicker('setDate', dateNew.format('YYYY-MM-DD'));
                
                $("#earnedPrivilege select[name='privilege'] option").each(function() {
                    //may want to use $.trim in here
                    this.selected = (this.text == privilegeName);
                });
            }
        });

        $("#bannedSubmit").on('click touch', function(e){
            e.preventDefault();

            var theDate = $(this).closest('.modal').data('date');
            var privilege = $(this).closest('.modal').data('privilege');
            
            window.location.href = '/child/{{$child->id}}/privilege/restore?date=' + theDate + '&privilege=' + privilege;
        });

        $("#earnedSubmit").on('click touch', function(e){
            e.preventDefault();

            var theDate = $(this).closest('.modal').data('date');
            var privilege = $(this).closest('.modal').data('privilege');
            
            window.location.href = '/child/{{$child->id}}/privilege/ban?date=' + theDate + '&privilege=' + privilege;
        });
    </script>
@endsection

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
                    <div class="card-header">
                        <h3><a href="{{route('child.edit', ['id' => $child->id])}}">{{$child->name}}</a> <span class="float-right">{{ Helpers::userTimeCurrent('m/d/Y') }}</span></h3>
                    </div>
                    <div class="card-body">
                        <table class="table text-center">
                            <tbody>
                                <tr data-date="{{ Helpers::userTimeCurrent('Y-m-d') }}">
                                    @foreach($child->privilegeStatus as $date => $privileges)
                                        @foreach($privileges as $name => $privilege)
                                            <td class="{{$privilege ? 'table-danger' : 'table-success'}} managePrivilege">
                                                {{$name}}
                                            </td>
                                        @endforeach
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>                            
                    </div>
                </div>

                <br><br>

                <div class="card">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('includes.child.privilege-manager-restore')
    @include('includes.child.privilege-manager-ban')
    @include('includes.child.privilegeManageJS')
@endsection
