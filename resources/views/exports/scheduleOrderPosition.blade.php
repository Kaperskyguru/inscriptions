<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table>
    <thead>
        
    </thead>
    <tbody>
        <tr>
            <td colspan="13" width="984">

                <img src="img/export-excel-header.jpg" style="display:block; margin:0; padding:0; width:984px; height:327px;"/>

            </td>
        </tr>
        <tr>
            <td colspan="13" style=" font-family:DIN Condensed; font-weight:bold; font-size:20px; background:#4D0F58; color:#ffff; text-align:center">HIT THE FLOOR GATINEAU 2020</td>
        </tr>
        <tr>
            <td  style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center">ORDRE</td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center">JOUR</td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center">HEURE</td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center">BLOC</td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center">ÉCOLE</td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center">TITREDE LA ROUTINE</td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center">DANSEURS</td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center">ÂGE</td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center">PROFESSEUR (S)</td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center">CAT. D'ÂGE</td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center">NIVEAU</td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center">STYLE</td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center">TYPE</td>
        </tr>
        @foreach ($data as $title)
        <tr>
            <td colspan="13" style=" font-family:Helvetica Neue;background:#4D0F58; color:#ffffff; font-weight:bold; text-align:center">{{$title->name}}</td>
        </tr>
        
        @foreach ($title->scheduleItems as $item)
        <tr>
            <td style="font-family:Helvetica Neue; font-size:10px; text-align:center">{{$item->position}}</td>
            <td style="font-family:Helvetica Neue; font-size:10px; text-align:center">{{$item->year}}</td>
            <td style="font-family:Helvetica Neue; font-size:10px; text-align:center">{{ date("H:i", strtotime($item->start_date)) }}</td>
            <td style="font-family:Helvetica Neue; font-size:10px; text-align:center">{{$item->block}}</td>
            <td style="font-family:Helvetica Neue; font-size:10px; text-align:center">{{$item->organization->accronyme}}</td>
           <td style="font-family:Helvetica Neue; font-size:10px; text-align:center">{{$item->routine->name}}</td>
            <td style="font-family:Helvetica Neue; font-size:10px; text-align:center">{{ count($item->routine->dancers)}}</td>
            <td style="font-family:Helvetica Neue; font-size:10px; text-align:center">{{$item->routine->average}}</td>
            <td style="font-family:Helvetica Neue; font-size:10px; text-align:center">{{$item->routine->teacher}}</td>
            <td style="font-family:Helvetica Neue; font-size:10px; text-align:center">{{$item->routine->classification->translate('en')->name}}</td>
            <td style="font-family:Helvetica Neue; font-size:10px; text-align:center">{{$item->routine->level->translate('en')->name}}</td>
            <td style="font-family:Helvetica Neue; font-size:10px; text-align:center">{{$item->routine->style->name}}</td>
            <td style="font-family:Helvetica Neue; font-size:10px; text-align:center">{{$item->routine->category->translate('en')->name}}</td> -->
        </tr>
           
        @endforeach
        @endforeach
    </tbody>
</table>
</html>

