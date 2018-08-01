
@extends("main.layout")

@section("main")

    @component("components.title.view",["type"=>"h2"])
        Tabla de posiciones
    @endcomponent
    @component('components.table.view',    [
          "rows"=>$positions,
          "iterator"=>"team.row",
          "class"=>"team-positions",
          "title"=>"Torneo Apertura 2018",
          "headers"=> ["position"=>"Pos.","team"=>"Equipo","played"=>"PJ","won"=>"PG","draw"=>"PE","loses"=>"PP","gf"=>"GF","ga"=>"GC","gd"=>"DG","points"=>"Pts."]
      ])
    @endcomponent



@endsection

