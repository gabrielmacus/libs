
@extends("home.layout")

@section('main')


    @component('components.navbar.view',
    ["items"=>[
        ["text"=>"La Liga","href"=>"http://google.com.ar"],
        ["text"=>"Torneos","items"=>[["text"=>"Torneo A","href"=>"http://google.com.ar"]]]
    ]])
    @endcomponent

    @component('components.table.view',["rows"=>$posts,"headers"=>["id"=>"ID","title"=>"Título"]])
    @endcomponent

@endsection