<?php

namespace App\Http\Controllers\Api;

use DB;
use PDF;
use Excel;
use Session;
use App\Level;
use App\Price;
use App\Style;
use App\Status;
use App\FeeType;
use App\Payment;
use App\Routine;
use App\Category;
use App\Schedule;
use Carbon\Carbon;
use App\PaymentType;
use App\Organization;
use App\ScheduleItem;
use App\Subscription;
use App\ScheduleTitle;
use App\Classification;
use Illuminate\Http\Request;
//use App\Exports\RoutinesExport;
//use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;

use App\Services\QuickBookService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Core\ServiceContext;

use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (config('EVENT_TYPE_ID') == 2) {
            $ids = [1];

            // TODO change to 1 request

            $gatineau = [];
       
            $toronto = [];
            $levis = [];
            $saintehyacinthe = [];

            $flofest = Status::withCount(['subscriptions' => function ($query) {
                $query->where('event_id', 4);
            }])->orderByRaw('FIELD (id, ' . implode(', ', $ids) . ') ASC')->get();

            //  $sainte_hyacinthe = Status::with(['subscriptions' => function($query){
            //     $query->where('event_id', 4);
            //  }])->orderByRaw('FIELD (id, ' . implode(', ', $ids) . ') ASC')->get();

        

            return response()->json(compact('gatineau', 'toronto', 'levis', 'flofest', 'saintehyacinthe'), 200);
        }
        $ids = [2,1,3,4,5];

        // TODO change to 1 request

        $gatineau = Status::withCount(['subscriptions' => function ($query) {
            $query->where('event_id', 1);
        }])->orderByRaw('FIELD (id, ' . implode(', ', $ids) . ') ASC')->get();
        $toronto = Status::withCount(['subscriptions' => function ($query) {
            $query->where('event_id', 2);
        }])->orderByRaw('FIELD (id, ' . implode(', ', $ids) . ') ASC')->get();
        $levis = Status::withCount(['subscriptions' => function ($query) {
            $query->where('event_id', 3);
        }])->orderByRaw('FIELD (id, ' . implode(', ', $ids) . ') ASC')->get();
        $saintehyacinthe = Status::withCount(['subscriptions' => function ($query) {
            $query->where('event_id', 5);
        }])->orderByRaw('FIELD (id, ' . implode(', ', $ids) . ') ASC')->get();
        //  $sainte_hyacinthe = Status::with(['subscriptions' => function($query){
        //     $query->where('event_id', 4);
        //  }])->orderByRaw('FIELD (id, ' . implode(', ', $ids) . ') ASC')->get();

        

        return response()->json(compact('gatineau', 'toronto', 'levis', 'saintehyacinthe'), 200);
    }

    /**
      * Display the specified resource.
      *
      * @param  string  $event
      * @return \Illuminate\Http\Response
      */
    public function event($event)
    {
        //
        if ($event === 'gatineau') {
            $city = 'Gatineau';
            $id = 1;
        } elseif ($event === 'toronto') {
            $city = 'Toronto';
            $id = 2;
        } elseif ($event === 'levis') {
            $city = 'Lévis';
            $id = 3;
        } elseif ($event === 'flofest') {
            $city = 'FLOFEST';
            $id = 4;
        } elseif ($event === 'saintehyacinthe') {
            $city = 'Sainte-Hyacinthe';
            $id = 5;
        }
        
        $organizations = Organization::orderBy('name')->whereHas('subscriptions', function ($query) use ($id) {
            $query->where('subscriptions.event_id', $id);
        })
        ->with('organizationType')
        ->with(['subscriptions' => function ($query) use ($id) {
            $query->where('subscriptions.event_id', $id)->withCount('routines');
        }, 'subscriptions.routines.category','subscriptions.routines.level','subscriptions.routines.style', 'subscriptions.routines.dancers', 'subscriptions.status'])
        ->orderBy('name', 'ASC')
        ->get();
        //$subscriptions = Subscription::where('event_id', $id)->get();
        return response()->json(compact('city', 'organizations'), 200);
    }
    public function search(Request $request)
    {
        $data = $request->toArray();
        $event = $data['event'];

        if ($event === 'gatineau') {
            $city = 'Gatineau';
            $id = 1;
        } elseif ($event === 'toronto') {
            $city = 'Toronto';
            $id = 2;
        } elseif ($event === 'levis') {
            $city = 'Lévis';
            $id = 3;
        } elseif ($event === 'flofest') {
            $city = 'FLOFEST';
            $id = 4;
        } elseif ($event === 'saintehyacinthe') {
            $city = 'Sainte-Hyacinthe';
            $id = 5;
        }

        $filters = $data['filters'];

        
        $organizations = Organization::orderBy('name')->whereHas('subscriptions', function ($query) use ($id, $filters) {
            $query->where('subscriptions.event_id', $id);
            if (count($filters) > 0) {
                $query->whereIn('subscriptions.status_id', $filters);
            }
        })
        ->with('organizationType')
        ->with(['subscriptions' => function ($query) use ($id) {
            $query->where('subscriptions.event_id', $id)->withCount('routines');
        },
        'subscriptions.status',
        ])
        ->where('name', 'like', '%'.$data['query'].'%')
        ->get();
        return response()->json(compact('city', 'organizations'), 200);
    }
    public function addPayment(Request $request)
    {
        $v = Validator::make($request->all(), [
            'payment_type_id' => 'required',
            'receive_on' => 'required',
            'amount' => 'required|string'
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $data = $request->toArray();

        $data['amount'] = number_format((float)$data['amount']*100., 0, '.', '');

        $payment = Payment::create($data);
        
        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail'),
    
            ], 400);
        }

        $subscription = Subscription::find($data['subscription_id']);

        if (!$subscription) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }

        if ($subscription->balance <= 0) {
            $subscription->status_id = 4;
        
            if (!$subscription->save()) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('messages.global.fail')
                ], 400);
            }
        }
        return response()->json([
            'status' => 'success',
            'msg' => __('messages.global.success'),
        ], 200);
    }
    public function updatePayment(Request $request)
    {
        $v = Validator::make($request->all(), [
            'payment_type_id' => 'required',
            'receive_on' => 'required',
            'amount' => 'required|string'
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $data = $request->toArray();

        $data['amount'] = number_format((float)$data['amount']*100., 0, '.', '');

        $payment = Payment::find($data['id']);
        
        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail'),
    
            ], 400);
        }

        if (!$payment->update($data)) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }

        $subscription = Subscription::find($data['subscription_id']);

        if (!$subscription) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }

        if ($subscription->balance <= 0) {
            $subscription->status_id = 4;
        } else {
            $subscription->status_id = 3;
        }
        if (!$subscription->save()) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }
        return response()->json([
            'status' => 'success',
            'msg' => __('messages.global.success'),
        ], 200);
    }
    public function deletePayment($id)
    {
        $payment = Payment::find($id);

        
        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail'),
    
            ], 400);
        }
        $subscription_id = $payment->subscription_id;

        if (!$payment->delete()) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }

        $subscription = Subscription::find($subscription_id);

        if (!$subscription) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }

        if ($subscription->balance <= 0) {
            $subscription->status_id = 4;
        } else {
            $subscription->status_id = 3;
        }
        if (!$subscription->save()) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }
        return response()->json([
            'status' => 'success',
            'msg' => __('messages.global.success'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $event
     * @param  int  $subscription_id
     * @return \Illuminate\Http\Response
     */
    public function subscription(Request $request, $event, $subscription_id)
    {
        $year = $request->query('year') == 'undefined' ? now()->addYear()->year: $request->query('year');
        session()->put('YEAR', $year);
        // Subscription::sub_total_year == $year;
        $organizations = Organization::orderBy('name')->whereHas('subscriptions', function ($query) use ($subscription_id) {
            $query->where('subscriptions.id', $subscription_id);
        })
        ->with('organizationType')
        ->with([
            'dancers' => function ($query) {
                $query->orderBy('first_name', 'ASC');
            },
        ])
        ->with('user')
        ->with(
            [
            'subscriptions' => function ($query) use ($subscription_id, $year) {
                $query->where('subscriptions.id', $subscription_id)->withCount('routines');
            },
            'subscriptions.routines.category',
            'subscriptions.routines.level',
            'subscriptions.routines.style',
            'subscriptions.routines.dancers',
            'subscriptions.status',
            'subscriptions.payments.paymentType',
            'subscriptions.fees.feeType',
        ]
        )
        ->first();

        // dd($organizations->subscriptions);

        $categories = Category::groupBy('id')->where('id', '!=', 7)
        ->where('event_type_id', config('EVENT_TYPE_ID'))
        ->with([
            'routines' => function ($query) use ($subscription_id) {
                $query->where('subscription_id', $subscription_id);
            },
        ])
        ->withCount([
            'routines' => function ($query) use ($subscription_id) {
                $query->where('subscription_id', $subscription_id);
            },
            
        ])
        ->get();

        foreach ($categories as $key => $category) {
            $entries = 0;
            $price = Price::where('category_id', $category->id)->where('year', $year)->first();
            foreach ($category->routines as $routine) {
                $entries += count($routine->dancers);
            }
            $categories[$key]['entries'] =  $entries;
            $total = $entries *  $price->rebate_price;
            $categories[$key]['total'] = number_format(($total / 100), 2, '.', '');
            $categories[$key]['price'] = $price;
        }
        $paymentTypes = PaymentType::all();
        $feeTypes = FeeType::all();
        $authUrl = '';

        session(['organization_id' => $organizations['id']]);
        session(['subscription_id' => $subscription_id]);

        $years = Price::distinct('year')->pluck('year');

        return response()->json(compact('organizations', 'categories', 'paymentTypes', 'feeTypes', 'authUrl', 'years'), 200);
    }

    public function updateStatus(Request $request)
    {
        $invoice = QuickBookService::getInstance()->create_invoice($request);

        if ($invoice['success']) {
            $data = $request->toArray();
            $subscription = Subscription::find($data['subscription_id']);


            if (!$subscription) {
                return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
            }
       
            // if((int)$subscription->balance <= 0.00) {
            //     $subscription->status_id = 4; // Paid
            // }else {
            //     $subscription->status_id = $data['status_id'];
            // }

            $subscription->status_id = $data['status_id'];
            if ($subscription->save()) {
                return response()->json([
                    'status' => 'success',
                    'msg' => __('messages.global.QBO_created'),
                    'subscription' => $subscription
                ], 200);
            }
        }
        return response()->json([
            'status' => 'error',
            'error' => $invoice['error'],
            'msg' => __('messages.global.fail')
        ], 400);
    }

    public function exportSubscription($subscription_id)
    {
        $data = [];
        $organizations = Organization::orderBy('name')->whereHas('subscriptions', function ($query) use ($subscription_id) {
            $query->where('subscriptions.id', $subscription_id);
        })
        ->with('organizationType')
        ->with(
            [
            'subscriptions' => function ($query) use ($subscription_id) {
                $query->where('subscriptions.id', $subscription_id)->withCount('routines');
            },
            'subscriptions.routines' => function ($query) use ($subscription_id) {
                $query->where('routines.subscription_id', $subscription_id);
            },
            // 'subscriptions.event',
            // 'subscriptions.routines.category',
            // 'subscriptions.routines.level',
            // 'subscriptions.routines.style',
            // 'subscriptions.routines.dancers',
            // 'subscriptions.status',
            'subscriptions.payments.paymentType'
        ]
        )
        ->first();

        $categories = Category::groupBy('id')->where('id', '!=', 7)->where('event_type_id', config('EVENT_TYPE_ID'))
        ->with([
            'routines' => function ($query) use ($subscription_id) {
                $query->where('subscription_id', $subscription_id);
            },
        ])
        ->withCount([
            'routines' => function ($query) use ($subscription_id) {
                $query->where('subscription_id', $subscription_id);
            },
            
        ])
        ->get();

        foreach ($categories as $i => $category) {
            $entries = 0;
            foreach ($category->routines as $routine) {
                $entries += count($routine->dancers);
            }
            $categories[$i]['entries'] =  $entries;
            $total = $entries *  $category->price->rebate_price;
            $categories[$i]['total'] = number_format(($total / 100), 2, '.', '');

            foreach ($category->routines as $j => $routine) {
                $dancers_age = [];

                foreach ($routine->dancers as $k => $dancer) {
                    $dancers_age[] = $dancer['age'];
                }

                if (count($dancers_age)) {
                    $dancerAverageAge = round(array_sum($dancers_age) / count($dancers_age));
                } else {
                    $dancerAverageAge = 0;
                }

                $categories[$i]['routines'][$j]['average'] = $dancerAverageAge;
            }
        }
        $data['export_time'] = Carbon::now();
        $data['organization'] = [
            'name' => $organizations->name,
            'address' => $organizations->address,
            'city' => $organizations->city,
            'zipcode' => strtoupper($organizations->zipcode),
            'phone' => $organizations->phone,
        ];
        $data['event'] = $organizations->subscriptions->first()->event->city;
        $data['state_id'] = $organizations->subscriptions->first()->event->state_id;

        $data['subscription'] =  $organizations->subscriptions->first();
        $data['user'] = [
            'name' => $organizations->user->name,
            'email' => $organizations->user->email
        ];
        $data['dancers'] = $organizations->dancers;
        $data['routines'] = $organizations->subscriptions->first()->routines;
        $data['categories'] = $categories;
       
        $separator = '-';
        $title = $data['organization']['name'] . '_' . 'HTF ' . $data['event'] . '_' . date('d-m-Y', strtotime($data['export_time']));

    
        return Excel::download(new ReportExport($data), $title. '.xlsx');
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $event
     * @return \Illuminate\Http\Response
     */
    public function schedule($event)
    {
        $event = $this->getEventInfos($event);
           
        $city = $event['city'];
        $id = $event['id'];
            
        $data = ScheduleTitle::with([
                // 'scheduleItems.routine.subscription.organization',
                'scheduleItems.routine.dancers',
                'scheduleItems.routine.category',
                'scheduleItems.routine.classification',
                'scheduleItems.routine.style',
                'scheduleItems.routine.level',
                'scheduleItems.organization',
                // 'scheduleItems.routine.dancersCount',
            ])
            ->orderBy('order', 'asc')
            ->whereHas('scheduleItems')
            ->where('schedule_id', $id)
            ->get();

        foreach ($data as $k => $schedule) {
            foreach ($schedule->scheduleItems as $j => $item) {
                $dancers_age = [];
                foreach ($item->routine->dancers as $dancer) {
                    $dancers_age[] = $dancer['age'];
                }
                if (count($dancers_age)) {
                    $dancerAverageAge = round(array_sum($dancers_age) / count($dancers_age));
                } else {
                    $dancerAverageAge = 0;
                }

                $data[$k]['scheduleItems'][$j]['routine']['average'] = $dancerAverageAge;
            }
        }
        $dataArr = $data->toArray();
           
        $noCategory = [
                'id' => '',
                'name' =>  __('admin.title.uncategorized'),
                'schedule_items' => ScheduleItem::with([
                    'routine.category',
                    'organization',
                    'routine.style',
                    'routine.level',
                    'routine.dancers',
                    'routine.classification'
                    // 'scheduleItems.routine.dancersCount',
                ])->where(['schedule_title_id' => null, 'event_id' => $id])->get()
            ];
        foreach ($noCategory['schedule_items'] as $j => $item) {
            $dancers_age = [];
            foreach ($item->routine->dancers as $dancer) {
                $dancers_age[] = $dancer['age'];
            }
            if (count($dancers_age)) {
                $dancerAverageAge = round(array_sum($dancers_age) / count($dancers_age));
            } else {
                $dancerAverageAge = 0;
            }

            $noCategory['schedule_items'][$j]['routine']['average'] = $dancerAverageAge;
        }

        array_unshift($dataArr, $noCategory);
            
        return response()->json($dataArr, 200);
    }
    public function scheduleOrderByPosition($event)
    {
        $event = $this->getEventInfos($event);
           
        $city = $event['city'];
        $id = $event['id'];
            
        $data = ScheduleTitle::with([
                    'scheduleItems' => function ($query) {
                        $query->orderBy(DB::raw('ISNULL(position), position'), 'ASC');
                    },
                    'scheduleItems.routine.dancers',
                    'scheduleItems.routine.classification',
                    'scheduleItems.organization',
                    'scheduleItems.routine.category',
                    'scheduleItems.routine.style',
                    'scheduleItems.routine.level',
                    // 'scheduleItems.routine.dancersCount',
                ])
                ->orderBy(DB::raw('ISNULL(position), position'), 'ASC')
                ->whereHas('scheduleItems')
                ->where('schedule_id', $id)
                ->get();
        foreach ($data as $k => $schedule) {
            $item_ids = [];
            foreach ($schedule->scheduleItems as $j => $item) {
                $dancers_age = [];
                $item_ids[] = $item->id;
                foreach ($item->routine->dancers as $dancer) {
                    $dancers_age[] = $dancer['age'];
                }
                if (count($dancers_age)) {
                    $dancerAverageAge = round(array_sum($dancers_age) / count($dancers_age));
                } else {
                    $dancerAverageAge = 0;
                }
    
                $data[$k]['scheduleItems'][$j]['routine']['average'] = $dancerAverageAge;
            }
        }
        //ScheduleItem::whereNotIn('id', $item_ids)->delete();
        $dataArr = $data->toArray();
              
        $noCategory = [
                'id' => '',
                'name' =>  __('admin.title.uncategorized'),
                'schedule_items' => ScheduleItem::with([
                    'routine.category',
                    'organization',
                    'routine.style',
                    'routine.level',
                    'routine.dancers',
                    'routine.classification'
                    // 'scheduleItems.routine.dancersCount',
                ])->where(['schedule_title_id' => null, 'event_id' => $id])->get()
            ];

        foreach ($noCategory['schedule_items'] as $j => $item) {
            $dancers_age = [];
            foreach ($item->routine->dancers as $dancer) {
                $dancers_age[] = $dancer['age'];
            }
            if (count($dancers_age)) {
                $dancerAverageAge = round(array_sum($dancers_age) / count($dancers_age));
            } else {
                $dancerAverageAge = 0;
            }

            $noCategory['schedule_items'][$j]['routine']['average'] = $dancerAverageAge;
        }

        array_unshift($dataArr, $noCategory);
            
        return response()->json($dataArr, 200);
    }
    public function scheduleGetItems($event)
    {
        $event = $this->getEventInfos($event);
           
        $city = $event['city'];
        $id = $event['id'];
        $data = ScheduleItem::with(['scheduleTitle', 'routine', 'routine.category'])
            ->where('event_id', $id)
            ->orderBy(DB::raw('ISNULL(position), position'), 'ASC')
            ->get();
           
            
        return response()->json($data, 200);
    }

    public function getCategoriesByPriceYear(Request $request, $year)
    {
        $categories = Category::whereHas('price', function ($query) use ($year) {
            $query->where('year', $year);
        })->get();

        return response()->json($categories, 200);
    }

    private function getEventInfos($eventSlug)
    {
        $events = [
                'gatineau' => [
                    'city' => 'Gatineau',
                    'id' => 1
                ],
                'toronto' => [
                    'city' => 'Toronto',
                    'id' => 2
                ],
                'levis' => [
                    'city' => 'Lévis',
                    'id' => 3
                ],
                'flofest' => [
                    'city' => 'FLOFEST',
                    'id' => 4
                ],
                'saintehyacinthe' => [
                    'city' => 'Saintehyacinthe',
                    'id' => 5
                ]
            ];
        return $events[$eventSlug];
    }
}
