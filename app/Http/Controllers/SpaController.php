<?php

namespace App\Http\Controllers;

use DB;
use App;
use Lang;
use Config;
use Helper;
use App\Routine;
use App\Schedule;
use Carbon\Carbon;
use App\Organization;
use App\ScheduleItem;
use App\ScheduleTitle;
use App\Classification;
use Illuminate\Http\Request;

use App\Services\QuickBookService;

class SpaController extends Controller
{
    public function index(Request $request)
    {
        // return QuickBookService::getInstance()->create_customer($request);
        
        $locale = $request->segment(1);
        
        if (array_key_exists($locale, Config::get('languages'))) {
            app()->setLocale($locale);
        }

        return view('spa');
    }

    public function getLastModification()
    {
        $organizations = $organizations = Organization::orderBy('name')->whereHas('subscriptions', function ($query) {
            $query->where('subscriptions.event_id', 1);
            // if(count($filters) > 0) {
            //     $query->whereIn('subscriptions.status_id', $filters);
            // }
        })
        ->where('id', 105)
        ->with([
            'subscriptions.routines' => function ($query) {
                $query->where('routines.updated_at', '>=', '2020-01-06 16:00:00');
            },
            'subscriptions.routines.category',
            'subscriptions.routines.level',
            'subscriptions.routines.style',
            'subscriptions.routines.classification',
        ])
        ->get();
        // $routines = Routine::where('updated_at', '>=', '2020-01-06 16:00:00')->with('organizations')->get();
        //     foreach($routines as $routine) {

        //     }
        foreach ($organizations as $organization) {
            if (count($organization->subscriptions[0]->routines)) {
                echo $organization->name;
                echo '<br>';
                foreach ($organization->subscriptions[0]->routines as $routine) {
                    echo $routine->id . ' | ' . $routine->name . ' | ' . $routine->classification->name . ' | ' . $routine->category->name . ' | ' . $routine->level->name . ' | ' . $routine->style->name . ' | ' . count($routine->dancers);
                    echo '<br>';
                }
                echo '<br>';
            }
        }
            
        exit;
    }

    
    public function redirect(Request $request)
    {
        return redirect(app()->getLocale());
    }
}
