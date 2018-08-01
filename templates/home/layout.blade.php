@extends("main.layout")

@section("content")

    @component("components.sidebar.view")


    @endcomponent

    <main>
        @yield("main")
    </main>



    @component("components.sidebar.view")


    @endcomponent

@endsection