<!doctype html>
<html lang="es">
<head>
    @include("main.head",["title"=>"Volvé a jugar"])
</head>
<body>

<header>

    @section("menu")


        @component('components.navbar.view',
         ["items"=>[
             ["text"=>"La Liga","active"=>true,"href"=>"http://google.com.ar"],
             ["text"=>"Torneos","items"=>[["text"=>"Torneo A","active"=>true,"href"=>"http://google.com.ar"]]],
             ["text"=>"Equipos","items"=>[["text"=>"Liverfoul","active"=>true,"image"=>"https://2.bp.blogspot.com/-Rdm2dy_jCtU/WVP6h-xjjOI/AAAAAAABKLU/EVTBYSpPo4cELRP6Trz0mTOpL3zoGd7BwCLcBGAs/s1600/Real%2BOviedo.png"],
              ["text"=>"Don Mateo","image"=>"https://as01.epimg.net/img/comunes/fotos/fichas/equipos/medium/16.png"]]]
         ]])
        @endcomponent




    @endsection




    @yield('menu')


</header>


@hasSection('left-bar')
    <aside class="left-bar">
        @yield("left-bar")
    </aside>
@endif



@hasSection('main')
<main>
    @yield('main')

</main>
@endif


@hasSection('right-bar')
<aside class="right-bar">
    @yield("right-bar")
</aside>
@endif

@hasSection('footer')
    <footer>
        @yield("footer")
    </footer>
@endif




</body>
</html>