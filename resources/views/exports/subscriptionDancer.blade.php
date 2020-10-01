<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table>
    <thead>
        
    </thead>
    <tbody>
        <tr>
            <td colspan="13" style=" font-family:DIN Condensed; font-weight:bold; font-size:20px; background:#4D0F58; color:#ffff; text-align:center; text-transform:uppercase;">HIT THE FLOOR {{$data['city']}} 2020</td>
        </tr>
        @foreach ($data['organizations'] as $organization)
        <tr>
            <td colspan="13" style=" font-family:Helvetica Neue;background:#731E6A; color:#ffffff; font-weight:bold; text-align:center">{{$organization->name}}</td>
        </tr>
        <!-- <tr>
            <td  style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center">NOM</td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center">ÂGE</td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center"></td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center"></td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center"></td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center"></td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center"></td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center"></td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center"></td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center"></td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center"></td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center"></td>
            <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; background:#731E6A; color:#ffff; text-align:center"></td>
        </tr> -->
            <tr>
                <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; color:#731E6A; text-align:center; text-transform:uppercase;">Professeurs</td>
                    
            </tr>
            @foreach ($organization['teachers'] as $item)
            
            <tr>
                <td style="font-family:Helvetica Neue; font-size:10px; text-align:center">{{$item}}</td>
                    
            </tr>
            @endforeach
            <tr>
                <td style="font-family:DIN Condensed; font-weight:bold; font-size:15px; color:#731E6A; text-align:center; text-transform:uppercase;">Danseurs</td>
                    
            </tr>
            
                @foreach ($organization['dancers'] as $subitem)
                    <tr>
                        <td style="font-family:Helvetica Neue; font-size:10px; text-align:center">{{$subitem['name']}}</td>
                        <td style="font-family:Helvetica Neue; font-size:10px; text-align:center">{{$subitem['age']}}</td>
                        
                    </tr>
                @endforeach
            
           
        @endforeach
    </tbody>
</table>
</html>

