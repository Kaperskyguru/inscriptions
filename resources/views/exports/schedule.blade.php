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
        
        @foreach ($data as $item)
        <tr>
            <td colspan="12" style=" font-family:Helvetica Neue;background:#731E6A; color:#ffffff; font-weight:bold; text-align:center">{{$item->name}}</td>
        </tr>
            @foreach ($item->scheduleItems as $subitem)
            <tr>
                <td>
                {{$subitem->organization->accronyme}}
                </td>
                <td>
                    {{$subitem->routine->name}}
                    @if($subitem->routine->category->name == 'Solo')
                        @foreach ($subitem->routine->dancers as $dancer)
                        ({{$dancer->name}})
                        @endforeach
                    @endif
                </td>
                <td style="text-align:center">
                    {{$subitem->routine->average}}
                    ({{$subitem->routine->classification->name}})
                </td>
                <td>
                    {{$subitem->routine->category->name}}
                </td>
                <td>
                    {{$subitem->routine->level->name}}
                </td>
                <td>
                    {{$subitem->routine->style->name}}
                </td>
                <td style="text-align:center">
                    {{count($subitem->routine->dancers)}}
                </td>
                
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
</html>

