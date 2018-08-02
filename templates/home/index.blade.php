
@extends("main.layout")

@section("main")

    @component("components.title.view",["type"=>"h2"])
        Tabla de posiciones
    @endcomponent
    @component('components.table.view',    [
          "rows"=>$positions,
          /** iterator: El archivo que levanta al procesar cada fila.
          Esto es por si ademas de mostrar el valor, hay que hacer algo especifico, como es en este caso, mostrar la imagen junto con el nombre del equipo (Ver: templates/team/row.blade.php) **/
          "iterator"=>"team.row",
          "class"=>"team-positions",
          "title"=>"Torneo Apertura 2018",
          /** headers: Encabezados. La clave corresponde al nombre de la propiedad y el valor al texto que se va a mostrar **/
          "headers"=> ["position"=>"Pos.","team"=>"Equipo","played"=>"PJ","won"=>"PG","draw"=>"PE","loses"=>"PP","gf"=>"GF","ga"=>"GC","gd"=>"DG","points"=>"Pts."]
      ])
    @endcomponent



@endsection

