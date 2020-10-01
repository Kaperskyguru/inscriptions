<?php

use Illuminate\Database\Seeder;
use App\Style;

class AddFloFestStylesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            
            [
                'event_type_id' => 2,
                'name' => [
                    'fr' => 'Contemporain',
                    'en' => 'Contemporary'
                ],
                'description' => [
                    'fr' => 'La routine contient des mouvements et des techniques modernes en intégrant le contrôle, l’équilibre et les extensions. Ce style est caractérisé par des passages au sol, des contractions et des mouvements abstraits.',
                    'en' => 'Contain modern dance technique and movement while incorporating balance, control and extensions. May involve floor works, use of contraction and release and abstract style.'
                ],
                'note' => [
                    'fr' => '*Maximum de 3 acrobaties',
                    'en' => '*Maximum 3 acrobatics'
                ],
            ],
            [
                'event_type_id' => 2,
                'name' => [
                    'fr' => 'Danse Urbaine',
                    'en' => 'Urban Dance'
                ],
                'description' => [
                    'fr' => 'La routine doit être influencée par un ou plusieurs styles de la liste suivante : Breaking // Waacking // Locking // Popping/Boogaloo // House Dance // Voguing // Party Dances ou Club Dances // Krumping // Stepping // Gumboot // Dancehall // Hip Hop Choreo',
                    'en' => 'The routine must be influenced by one or many of the following list: Breaking // Waacking // Locking // Popping/Boogaloo // House Dance // Voguing // 	Party Dances or Club Dances // Krumping // Stepping // Gumboot // Dancehall // Hip Hop Choreo'
                ],
                'note' => [
                    'fr' => '*Nombre d’acrobaties illimité',
                    'en' => '*Unlimited number of acrobatics'
                ],
            ],
            [
                'event_type_id' => 2,
                'name' => [
                    'fr' => 'Jazz',
                    'en' => 'Jazz'
                ],
                'description' => [
                    'fr' => 'La routine contient des techniques et mouvements de Jazz (extensions, battements, tours, etc.). Ce style est caractérisé par l’énergie, le rythme et la dynamique. ',
                    'en' => 'Contain standard jazz technique and performance (extensions, kicks, turns, etc.) Characterized by high energy, rhythm and dynamics.'
                ],
                'note' => [
                    'fr' => '*Maximum de 3 acrobaties',
                    'en' => '*Maximum 3 acrobatics'
                ],
            ],
            [
                'event_type_id' => 2,
                'name' => [
                    'fr' => 'Lyrique',
                    'en' => 'Lyrical'
                ],
                'description' => [
                    'fr' => 'La routine contient une combinaison des techniques de Jazz et de Ballet. Ce style est caractérisé par l’accent mis sur l’émotion et sur les éléments qui racontent une histoire, en portant une attention particulière aux paroles de la chanson.',
                    'en' => 'Contain a combination of jazz and ballet techniques while incorporating emotional and storytelling elements with specific attention to music lyrics.'
                ],
                'note' => [
                    'fr' => '*Maximum de 3 acrobaties',
                    'en' => '*Maximum 3 acrobatics'
                ],
            ],
            [
                'event_type_id' => 2,
                'name' => [
                    'fr' => 'Open',
                    'en' => 'Open'
                ],
                'description' => [
                    'fr' => 'La routine présente un style qui n’est pas mentionné dans les styles ci-haut ou qui représente une combinaison de plusieurs styles du Volet Technique.',
                    'en' => 'Any routine which does not fit into any of our styles or a routine which is a combination of two or more of our technical styles.'
                ],
                'note' => [
                    'fr' => '*Nombre d’acrobaties illimité',
                    'en' => '*Unlimited number of acrobatics'
                ],
            ],
            
        ];
        foreach($data as $item) {
            $model = new Style();
            $model->event_type_id = $item['event_type_id'];
            foreach($item['name'] as $locale => $subitem) {
                app()->setLocale($locale);
                $model->name = $subitem;
            }
            foreach($item['description'] as $locale => $subitem) {
                app()->setLocale($locale);
                $model->description = $subitem;
            }
            foreach($item['note'] as $locale => $subitem) {
                app()->setLocale($locale);
                $model->note = $subitem;
            }
            $model->save();
        }
    }
}
