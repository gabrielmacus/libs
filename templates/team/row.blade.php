@if($property =='team')

    <img class="team-shield" src="{{$row["shield"]}}"> {{$value}}

@else
    {{$value}}
@endif