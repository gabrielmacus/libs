@if($property =='team')

    <!-- Si la propiedad es el nombre del equipo ('team') agrego la imagen -->
    <img class="team-shield" src="{{$row["shield"]}}"> {{$value}}

@else
    <!-- Si no, muestro el valor solamente -->
    {{$value}}
@endif