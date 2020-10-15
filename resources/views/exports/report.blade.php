<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table>
    <thead>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color:#731E6A; font-family:DIN Condensed; font-weight:bold; font-size:30px;">
                {{mb_strtoupper($data['event'], 'UTF-8')}}
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="color:#212529; font-family:DIN Condensed; font-size:22px;">
                {{$data['organization']['name']}}
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$data['user']['name']}}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$data['user']['email']}}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
                {{substr($data['organization']['phone'], 0, 3) .'-'. substr($data['organization']['phone'], 3, 3) .'-'.
                substr($data['organization']['phone'], 6)}}
            </td>
        </tr>
        <tr>
            <td style="color:#731E6A; font-family:DIN Condensed; font-weight:bold; font-size:26px;">{{
                mb_strtoupper(__('admin.export.students'), 'UTF-8')}}</td>
        </tr>
        <tr>
            <td style="font-family:Helvetica Neue; background:#212529; color:#ffffff; font-weight:bold;">#</td>
            <td style=" font-family:Helvetica Neue; background:#212529; color:#ffffff; font-weight:bold;">{{
                __('dashboard.table.title.name') }}</td>
            <td colspan="4" style=" font-family:Helvetica Neue;background:#212529; color:#ffffff; font-weight:bold;">{{
                __('dashboard.table.title.age') }}</td>
        </tr>
        @foreach ($data['dancers'] as $dancer)
        <tr>
            <td style="font-family:Helvetica Neue;">
                {{$loop->iteration}}
            </td>

            <td style="font-family:Helvetica Neue;">
                {{$dancer->last_name}}, {{$dancer->first_name}}
            </td>

            <td align="center" style="font-family:Helvetica Neue;">
                {{$dancer->age}}
            </td>
        </tr>
        @endforeach

        <tr>
            <td style="color:#731E6A; font-family:DIN Condensed; font-weight:bold; font-size:26px;">{{
                mb_strtoupper(__('admin.export.teachers'), 'UTF-8') }}</td>
        </tr>
        <tr>
            <td style="font-family:Helvetica Neue; background:#212529; color:#ffffff; font-weight:bold;">#</td>
            <td style="font-family:Helvetica Neue; background:#212529; color:#ffffff; font-weight:bold;">{{
                __('admin.table.title.routine') }}</td>
            <td style="font-family:Helvetica Neue; background:#212529; color:#ffffff; font-weight:bold;" colspan="4">{{
                __('admin.table.title.teachers') }}</td>
        </tr>
        @foreach ($data['routines'] as $routine)
        <tr>
            <td style="font-family:Helvetica Neue;">
                <span>{{$loop->iteration}}</span>
            </td>

            <td style="font-family:Helvetica Neue;">
                <span>{{$routine->name}}</span>
            </td>

            <td colspan="4" style="font-family:Helvetica Neue;">
                <span>{{$routine->teacher}}</span>
            </td>
        </tr>
        @endforeach
        @foreach ($data['categories'] as $category)
        <tr>
            <td style="color:#731E6A; font-family:DIN Condensed; font-weight:bold; font-size:26px;">{{
                mb_strtoupper($category->name, 'UTF-8') }}</td>
        </tr>
        <tr>
            <td style="font-family:Helvetica Neue; background:#212529; color:#ffffff; font-weight:bold;">#</td>
            <td style="font-family:Helvetica Neue; background:#212529; color:#ffffff; font-weight:bold;">{{
                __('admin.table.title.routine') }}</td>
            <td style="font-family:Helvetica Neue; background:#212529; color:#ffffff; font-weight:bold;" align="center">
                {{ __('admin.table.title.age') }}</td>
            <td style="font-family:Helvetica Neue; background:#212529; color:#ffffff; font-weight:bold;" align="center">
                {{ __('admin.table.title.level') }}</td>
            <td style="font-family:Helvetica Neue; background:#212529; color:#ffffff; font-weight:bold;" align="center">
                {{ __('admin.table.title.style') }}</td>
            <td style="font-family:Helvetica Neue; background:#212529; color:#ffffff; font-weight:bold;">{{
                __('admin.table.title.teachers') }}</td>
        </tr>
        @foreach ($category->routines as $routine)
        <tr>
            <td style="font-family:Helvetica Neue; background:#D4D8DB; color:#212529;"></td>
            <td style="font-family:Helvetica Neue; background:#D4D8DB; color:#212529;">{{ $routine->name }}</td>
            <td style="font-family:Helvetica Neue; background:#D4D8DB; color:#212529;" align="center">{{
                $routine->average }}</td>
            <td style="font-family:Helvetica Neue; background:#D4D8DB; color:#212529;" align="center">{{
                $routine->level->name }}</td>
            <td style="font-family:Helvetica Neue; background:#D4D8DB; color:#212529;" align="center">{{
                $routine->style->name }}</td>
            <td style="font-family:Helvetica Neue; background:#D4D8DB; color:#212529;">{{ $routine->teacher }}</td>
        </tr>
        @foreach ($routine->dancers as $dancer)
        <tr>
            <td style="font-family:Helvetica Neue;">
                {{$loop->iteration}}
            </td>

            <td style="font-family:Helvetica Neue;">
                {{$dancer->last_name}}, {{$dancer->first_name}}
            </td>

            <td style="font-family:Helvetica Neue;" align="center">
                {{$dancer->age}}
            </td>
        </tr>
        @endforeach

        @endforeach
        <tr></tr>
        @endforeach
        <tr>
            <td style="background:#731E6A; color:#ffffff; font-family:DIN Condensed; font-weight:bold; font-size:26px;"
                colspan="6">{{ $data['organization']['name'] }}</td>
        </tr>
        <tr>
            <td style="font-family:Helvetica Neue; background:#D4D8DB; color:#212529; font-weight:bold;"></td>
            <td style="font-family:Helvetica Neue; background:#D4D8DB; color:#212529; font-weight:bold;"></td>
            <td style="font-family:Helvetica Neue; background:#D4D8DB; color:#212529; font-weight:bold;"></td>

            <td
                style="font-family:Helvetica Neue; background:#D4D8DB; color:#212529; font-weight:bold;text-align:center;">
                {{ __('dashboard.table.title.totalSubscription') }}</td>
            <td
                style="font-family:Helvetica Neue; background:#D4D8DB; color:#212529; font-weight:bold; text-align:center;">
                {{ __('dashboard.table.title.unit_cost') }}</td>
            <td style="font-family:Helvetica Neue; background:#D4D8DB; color:#212529; font-weight:bold; text-align:right;"
                data-format="0.00">{{ __('dashboard.table.title.total_cost') }}</td>
        </tr>
        @foreach ($data['categories'] as $category)
        <tr>
            <td></td>
            <td style="font-family:Helvetica Neue; font-weight:bold; text-align:center;">{{$category->name}}</td>
            <td style="font-family:Helvetica Neue; text-align:center;">{{count($category->routines)}} {{
                __('dashboard.table.title.routine') }}</td>
            <td style="font-family:Helvetica Neue; text-align:center;">{{$category->entries}}</td>
            <td style="font-family:Helvetica Neue; text-align:right;" data-format="0.00">
                {{$category->formatted_rebate_price}} $</td>
            <td style="font-family:Helvetica Neue; text-align:right;" data-format="0.00">{{$category->total}} $</td>
        </tr>
        @endforeach
        <tr>
            <td style="font-family:Helvetica Neue; background:#D4D8DB; color:#212529; font-weight:bold; text-align:right;"
                colspan="5" data-format="0.00">Sous-total</td>
            <td style="font-family:Helvetica Neue; background:#D4D8DB; color:#212529; font-weight:bold; text-align:right;"
                colspan="1" data-format="0.00">{{$data['subscription']['subtotal']}} $</td>
        </tr>
        @if($data['state_id'] == 57)
        <tr>
            <td colspan="5" style="font-family:Helvetica Neue; text-align:right;">TPS 737664490 RT 0001 (5%)</td>
            <td colspan="1" style="font-family:Helvetica Neue; text-align:right;" data-format="0.00">
                {{$data['subscription']['tps']}} $</td>
        </tr>
        <tr>
            <td colspan="5" style="font-family:Helvetica Neue; text-align:right;">TVQ 1224260896 TQ 0001 (9,975%)</td>
            <td colspan="1" style="font-family:Helvetica Neue; text-align:right;" data-format="0.00">
                {{$data['subscription']['tvq']}} $</td>
        </tr>
        @elseif($data['state_id'] == 58)
        <tr>
            <td colspan="5" style="font-family:Helvetica Neue; text-align:right;">TVH 737664490 RT 0001 (13%)</td>
            <td colspan="1" style="font-family:Helvetica Neue; text-align:right;" data-format="0.00">
                {{$data['subscription']['tvh']}} $</td>
        </tr>
        @endif
        <tr>
            <td style="font-family:Helvetica Neue; background:#D4D8DB; color:#212529; font-weight:bold; text-align:right;"
                colspan="5">{{ __('admin.text.totalToPay') }}</td>
            <td style="font-family:Helvetica Neue; background:#D4D8DB; color:#212529; font-weight:bold; text-align:right;"
                colspan="1" data-format="0.00">{{$data['subscription']['total']}} $</td>
        </tr>
        @foreach($data['subscription']->payments as $payment)
        <tr>
            <td colspan="5" style="font-family:Helvetica Neue; text-align:right;">{{ __('admin.export.payment') }} {{
                $payment['paymentType']['name'] }} {{ __('admin.export.receive_on') }} {{ $payment['receive_on']}}</td>
            <td colspan="1" style="font-family:Helvetica Neue; text-align:right;" data-format="0.00">{{
                $payment['formatted_amount']}} $</td>
        </tr>
        @endforeach
        <tr>
            <td style="font-family:Helvetica Neue; background:#D4D8DB; color:#212529; font-weight:bold; text-align:right;"
                colspan="5">{{ __('admin.text.totalPayments') }}</td>
            <td colspan="1"
                style="font-family:Helvetica Neue; background:#D4D8DB; color:#212529; font-weight:bold; text-align:right;"
                data-format="0.00">{{ $data['subscription']['sum_payments']}} $</td>
        </tr>
        <tr>
            <td style="font-family:Helvetica Neue; background:#e5e5e5; color:#212529; font-weight:bold; text-align:right;"
                colspan="5">{{ __('admin.text.balance') }}</td>
            <td style="font-family:Helvetica Neue; background:#e5e5e5; color:#212529; font-weight:bold; text-align:right;"
                colspan="1" data-format="0.00">{{ $data['subscription']['balance']}} $</td>
        </tr>
        <tr>
            <td style="text-align:center;" colspan="6"></td>
        </tr>
        <tr>
            <td style="text-align:center;" colspan="6"><i>{{ __('admin.export.thanks') }}</i></td>
        </tr>


    </tbody>
</table>

</html>