<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>

<head>
  <title></title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="{{ asset('css/normalize.css?v=1.1') }}" rel="stylesheet">
  <link href="{{ asset('css/print.css?v=1.1') }}" rel="stylesheet">
</head>

<body class="">
  <div id="export-layout">
    <div class="export-header">
      <img class="export-logo" src="{{ asset('img/logo.svg') }}" alt="">
      <div class="export-subscription-info">
        <h1 class="title-primary">{{$organization['name']}}</h1>
        <h2 class="title-secondary">{{$event}}</h2>
        <ul class="export-list-organization text-subhead">
          <li>({{$export_time}})</li>
          <li>{{$user['name']}} ({{$user['email']}})</li>
          <li>{{$organization['address']}}, {{$event}} {{$organization['zipcode']}}</li>
          <li>{{$organization['phone']}}</li>
        </ul>
      </div>
    </div>
    <div class="clear"></div>
    <div class="export-section export-dancers">
      <h3 class="title-tertiary">{{ __('admin.export.students') }}</h3>
      <div class="table">
        <div class="table-header">
          <ul class="table-list">
            <li class="table-item grid-1">
              <span class="text-subhead">#</span>
            </li>
            <li class="table-item grid-4">
              <span class="text-subhead">{{ __('dashboard.table.title.name') }}</span>
            </li>
            <li class="table-item grid-2">
              <span class="text-subhead">{{ __('dashboard.table.title.age') }}</span>
            </li>
          </ul>
        </div>
        <div class="table-body">
          @foreach ($dancers as $dancer)
          <ul class="table-list table-list-body">

            <li class="table-item grid-1">
              <span class="table-text text-body-display">{{$loop->iteration}}</span>
            </li>
            <li class="table-item grid-4">
              <span class="table-text text-body-display">{{$dancer->last_name}}, {{$dancer->first_name}}</span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display">{{$dancer->age}}</span>
            </li>
          </ul>
          @endforeach
        </div>
      </div>
    </div>
    <div class="page-break"></div>


    <div class="export-section export-teachers">
      <h3 class="title-tertiary">{{ __('admin.export.teachers') }}</h3>
      <div class="table">
        <div class="table-header">
          <ul class="table-list">
            <li class="table-item grid-1">
              <span class="text-subhead">#</span>
            </li>
            <li class="table-item grid-4">
              <span class="text-subhead">{{ __('admin.table.title.routine') }}</span>
            </li>
            <li class="table-item grid-4">
              <span class="text-subhead">{{ __('admin.table.title.teachers') }}</span>
            </li>
          </ul>
        </div>
        <div class="table-body">
          @foreach ($routines as $routine)
          <ul class="table-list table-list-body">

            <li class="table-item grid-1">
              <span class="table-text text-body-display">{{$loop->iteration}}</span>
            </li>
            <li class="table-item grid-4">
              <span class="table-text text-body-display">{{$routine->name}}</span>
            </li>
            <li class="table-item grid-4">
              <span class="table-text text-body-display">{{$routine->teacher}}</span>
            </li>
          </ul>
          @endforeach
        </div>
      </div>
    </div>
    @foreach ($categories as $category)
    <div class="export-section export-categories">
      <h3 class="title-tertiary">{{ $category->name }}</h3>
      <div class="table">
        <div class="table-header">
          <ul class="table-list">
            <li class="table-item grid-1">
              <span class="text-subhead">#</span>
            </li>
            <li class="table-item grid-2">
              <span class="text-subhead">{{ __('admin.table.title.routine') }}</span>
            </li>
            <li class="table-item grid-2">
              <span class="text-subhead">{{ __('admin.table.title.age') }}</span>
            </li>
            <li class="table-item grid-2">
              <span class="text-subhead">{{ __('admin.table.title.level') }}</span>
            </li>
            <li class="table-item grid-2">
              <span class="text-subhead">{{ __('admin.table.title.style') }}</span>
            </li>
            <li class="table-item grid-3">
              <span class="text-subhead">{{ __('admin.table.title.teachers') }}</span>
            </li>
          </ul>
        </div>
        <div class="table-body">
          @foreach ($category->routines as $routine)
          <ul class="table-list table-list-body table-list-body-routine">
            <li class="table-item grid-1">
              <span class="table-text text-body-display"></span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display">{{ $routine->name }}</span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display">{{ $routine->average }}</span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display">{{ $routine->level->name }}</span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display">{{ $routine->style->name }}</span>
            </li>
            <li class="table-item grid-3">
              <span class="table-text text-body-display">{{ $routine->teacher }}</span>
            </li>
          </ul>
          @foreach ($routine->dancers as $dancer)
          <ul class="table-list table-list-body">

            <li class="table-item grid-1">
              <span class="table-text text-body-display">{{$loop->iteration}}</span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display">{{$dancer->last_name}}, {{$dancer->first_name}}</span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display">{{$dancer->age}}</span>
            </li>
          </ul>
          @endforeach
          @endforeach
        </div>
      </div>

    </div>
    @endforeach
    <div class="page-break"></div>
    <div class="export-section export-invoices">
    <h3 class="title-tertiary">{{ __('admin.export.cost') }} - {{$organization['name']}}</h3>
      <div class="table">
        <div class="table-header">
          <ul class="table-list">
            <li class="table-item grid-4">
              <span class="text-subhead">{{ __('dashboard.table.title.category') }}</span>
            </li>
            <li class="table-item grid-2">
              <span class="text-subhead">{{ __('dashboard.table.title.routine') }}</span>
            </li>
            <li class="table-item grid-2">
              <span class="text-subhead">{{ __('dashboard.table.title.totalSubscription') }}</span>
            </li>
            <li class="table-item grid-2">
              <span class="text-subhead">{{ __('dashboard.table.title.unit_cost') }}</span>
            </li>
            <li class="table-item grid-2">
              <span class="text-subhead">{{ __('dashboard.table.title.total_cost') }}</span>
            </li>
          </ul>
        </div>
        <div class="table-body">
        @foreach ($categories as $category)
          <ul class="table-list table-list-body">
            <li class="table-item grid-4">
              <span class="table-text text-body-display">{{$category->name}}</span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display">{{$category->routines_count}}</span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display">{{$category->entries}}</span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display">{{$category->formatted_rebate_price}} $</span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display">{{$category->total}} $</span>
            </li>
          </ul>
          @endforeach
        </div>
      </div>
      <ul class="invoice-list-total">
        <li class="invoice-item text-body-display">
          <span class="invoice-data">Sous-total</span>
          <span class="invoice-int">{{$subscription['subtotal']}} $</span>
        </li>
        @if($state_id == 57)
        <li class="invoice-item text-body-display">
          <span class="invoice-data">TPS 737664490 RT 0001 (5%)</span>
          <span class="invoice-int"> {{$subscription['tps']}} $</span>
        </li>
        <li class="invoice-item text-body-display">
          <span class="invoice-data">TVQ 1224260896 TQ 0001 (9,975%)</span>
          <span class="invoice-int"> {{$subscription['tvq']}} $</span>
        </li>
        @elseif($state_id == 58)
        <li class="invoice-item text-body-display">
          <span class="invoice-data">TVH 737664490 RT 0001 (13%)</span>
          <span class="invoice-int"> {{$subscription['tvh']}} $</span>
        </li>
        @endif
        <li class="invoice-item text-body-display">
          <span class="invoice-data">{{ __('admin.text.totalToPay') }}</span>
          <span class="invoice-int">{{$subscription['total']}} $</span>
        </li>
        @foreach($subscription->payments as $payment)
        <li class="invoice-item text-body-display">
          <span class="invoice-data">{{ __('admin.export.payment') }} {{ $payment['paymentType']['name'] }} {{ __('admin.export.receive_on') }} {{ $payment['receive_on']}}</span>
          <span class="invoice-int">{{ $payment['formatted_amount']}} $</span>
        </li>
        @endforeach
        <li class="invoice-item text-body-display">
          <span class="invoice-data">{{ __('admin.text.totalPayments') }}</span>
          <span class="invoice-int">{{ $subscription['sum_payments']}} $</span>
        </li>
        <li class="invoice-item invoice-total text-body-display">
          <span class="invoice-data">{{ __('admin.text.balance') }}</span>
          <span class="invoice-int">{{ $subscription['balance']}} $</span>
        </li>
      </ul>
      <p class="export-thanks text-footnote">{{ __('admin.export.thanks') }}</p>
    </div>
  </div>
  
  </div>
</body>

</html>