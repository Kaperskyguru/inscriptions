<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ScheduleTitle;
use Artisan;


class ServicesController extends Controller
{
    //
    public function setOrder($id) {
        $search = ['Mini', 'Junior', 'Intermediate', 'Senior', 'Senior +', 'Adult'];

        foreach($search as $key => $needle) {
            $data = ScheduleTitle::with([
                ])
                ->orderBy('order', 'asc')
                ->whereHas('scheduleItems')
                ->where('schedule_id', $id)
                ->whereTranslationLike('name', '%' . $needle . '%')
                ->get();
                foreach($data as $title) {
                    $title->order = $key + 1;
                    $title->save();
                }
        }
        
    }

    public function cleanScheduleTitle() {

         $data = ScheduleTitle::whereDoesntHave('scheduleItems')
        ->delete();
    }

    public function clearCache() {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

    }
    public function storageLink() {
        Artisan::call('storage:link');

    }
}
