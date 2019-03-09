<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Http\Controllers\Helpers;

class Child extends Model
{

    protected $casts = [
        'privileges' => 'array'
    ];

    protected $appends = ['start_ban'];

    /**
     * Set attribute to date format
     * @param $input
     */
    public function setStartBanAttribute($input)
    {
        $this->attributes['start_ban'] =
            \Timezone::convertToUTC($input, auth()->user()->timezone, 'Y-m-d');
    }

    /**
     * Set attribute to date format
     * @param $input
     */
    public function getStartBanAttribute($input)
    {
        return \Timezone::convertFromUTC($input, auth()->user()->timezone, 'Y-m-d');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function privileges()
    {
        return $this->belongsToMany('App\Privilege')->withPivot('start_ban')->withTimestamps();
    }

    public function current_bans()
    {
    	return $this->belongsToMany('App\Privilege')
    		->wherePivot('start_ban', '=', Helpers::userTimeCurrent())
    		->withPivot('start_ban');	
    }

    public function bans($childID, $start, $end)
    {
        // $bans = \DB::table('child_privilege')->where('child_id', $childID)
        //     ->whereDate('start_ban', '<=', $end->format('Y-m-d'))
        //     ->whereDate('start_ban', '>=', $start->format('Y-m-d'));

        //$results = $bans->get();
        //dd($start->format('Y-m-d'), $end->format('Y-m-d'), $bans->toSql(), $results);
        //return $results;
        //
        return $this->belongsToMany('App\Privilege')
            ->wherePivot('start_ban', '<=', $end->format('Y-m-d'))
            ->wherePivot('start_ban', '>=', $start->format('Y-m-d'))
            ->withPivot('start_ban')->get();
        
    }

    public static function setPrivilegeStatus($child, $allPrivileges, $dateRange){
        $privilegeStatus = [];

        $bans = $child->bans($child->id, reset($dateRange), end($dateRange))->toArray();
        
        $days = [];
        
        foreach($bans as &$ban){
            $ban['start_ban'] = $ban['pivot']['start_ban'];
            unset($ban['pivot']);
        }
        
        foreach($dateRange as $day){
            foreach($allPrivileges as $privilege){
                //set the privilege status for each day
                $privilegeStatus[$privilege['name']] = false;
                foreach($bans as $oneBan){
                    $privilegeStatus[$privilege['name']] = Helpers::in_array_all([$day->format('Y-m-d'), $privilege['name']], $oneBan);
                    if($privilegeStatus[$privilege['name']])
                        break;
                }
            }
            $days[$day->format('Y-m-d')] = $privilegeStatus;
        }

        $child->privilegeStatus = $days;
        
        return $child;
    }

    public static function privilegeEvents($daysPrivileges){
        $allEventInfo = [];
        foreach($daysPrivileges as $date => $privileges){
            foreach($privileges as $name => $banned){
                $eventInfo = new \stdClass();
                $eventInfo->title = $name;
                $eventInfo->start = $date;
                $eventInfo->allDay = true;

                if($banned){
                    $eventInfo->backgroundColor = '#f5c6cb';
                    $eventInfo->borderColor = '#f5c6cb';
                    $eventInfo->className = 'danger';
                }else{
                    $eventInfo->backgroundColor = '#c3e6cb';
                    $eventInfo->borderColor = '#c3e6cb';
                    $eventInfo->className = 'success';
                }

                $allEventInfo[] = $eventInfo;
            }
        }
        return $allEventInfo;
    }
}
