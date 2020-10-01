<?php

namespace App\Observers;

use App\Dancer;
use App\Classification;

class DancerObserver
{
    /**
     * Handle the dancer "created" event.
     *
     * @param  \App\Dancer  $dancer
     * @return void
     */
    public function created(Dancer $dancer)
    {
        //
    }

    /**
     * Handle the dancer "updated" event.
     *
     * @param  \App\Dancer  $dancer
     * @return void
     */
    public function updated(Dancer $dancer)
    {
        //
    }

    /**
     * Handle the dancer "deleted" event.
     *
     * @param  \App\Dancer  $dancer
     * @return void
     */
    public function deleted(Dancer $dancer)
    {
        $routines = $dancer->routines()->get();
    
        $dancer->routines()->detach();


        foreach($routines as $routine) {
            $dancers = $routine->dancers;
            $total_dancer = count($dancers);
            $dancers_age = [];
            foreach ($dancers as $key => $dancer) {
                $dancers_age[] = $dancer['age'];
            }

            if(count($dancers_age)) {
                $dancerAverageAge = round(array_sum($dancers_age) / count($dancers_age));
                $classification = Classification::where('minage', '<=', $dancerAverageAge)->where('maxage', '>=', $dancerAverageAge)->first();
                $routine->classification_id = $classification->id;
            }else {
                $dancerAverageAge = 0;
                $routine->classification_id = 7;
            }

            
            
            
            if ($total_dancer >= 16) {
                $routine->category_id =  6; // Production
            } else if ($total_dancer <= 15 && $total_dancer >= 10) {
                $routine->category_id = 5; // Grand Group
            } else if ($total_dancer <= 9 && $total_dancer >= 4) {
                $routine->category_id = 4; // Petit Group
            } else if ($total_dancer == 3) {
                $routine->category_id = 3; // Trio
            } else if ($total_dancer == 2) {
                $routine->category_id = 2; // Duo
            } else if ($total_dancer == 1){
                $routine->category_id = 1; // Solo
            }else {
                $routine->category_id = 7; // Solo
            }
            $routine->save();
        }
        
    }

    /**
     * Handle the dancer "restored" event.
     *
     * @param  \App\Dancer  $dancer
     * @return void
     */
    public function restored(Dancer $dancer)
    {
        //
    }

    /**
     * Handle the dancer "force deleted" event.
     *
     * @param  \App\Dancer  $dancer
     * @return void
     */
    public function forceDeleted(Dancer $dancer)
    {
        //
    }
}
