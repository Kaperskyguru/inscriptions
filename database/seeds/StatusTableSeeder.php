<?php

use Illuminate\Database\Seeder;
use App\Status;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $statuses = [
            [
                'type' => 'default',
                'admin_label' => [
                    'fr' => 'Non-soumis',
                    'en' => 'Not submitted'
                ],
                'public_label' => [
                    'fr' => 'Non-soumis',
                    'en' => 'Not submitted'
                ],
            ],
            [
                'type' => 'danger',
                'admin_label' => [
                    'fr' => 'En révision',
                    'en' => 'To be reviewed'
                ],
                'public_label' => [
                    'fr' => 'En révision',
                    'en' => 'To be reviewed'
                ],
            ],
            [
                'type' => 'warning',
                'admin_label' => [
                    'fr' => 'En attente de paiement',
                    'en' => 'Wating for paiement'
                ],
                'public_label' => [
                    'fr' => 'En attente de paiement',
                    'en' => 'Wating for paiement'
                ],
            ],
            [
                'type' => 'success',
                'admin_label' => [
                    'fr' => 'Payé',
                    'en' => 'Paid'
                ],
                'public_label' => [
                    'fr' => 'Payé',
                    'en' => 'Paid'
                ],
            ],
            [
                'type' => 'waiting',
                'admin_label' => [
                    'fr' => 'Liste d\'attente',
                    'en' => 'Waiting list'
                ],
                'public_label' => [
                    'fr' => 'Liste d\'attente',
                    'en' => 'Waiting list'
                ],
            ],
        ];
        foreach($statuses as $status) {
            $statusModel = new Status();
            $statusModel->type = $status['type'];
            foreach($status['admin_label'] as $locale => $label) {
                app()->setLocale($locale);
                $statusModel->admin_label = $label;
            }
            foreach($status['public_label'] as $locale => $label) {
                app()->setLocale($locale);
                $statusModel->public_label = $label;
            }
            $statusModel->save();

        }
    }
}
