
@if(!empty($items))

    <nav   data-navbar class="navbar">
        <ul>
            @foreach($items as $k => $item)
                <li data-item class="{{!empty($item["active"])?"active":""}}">
                    @if(!empty($item["items"]))
                    <input id="{{$item["text"].$k}}" type="checkbox">
                    @endif
                    <a href="{{!empty($item["href"])?$item["href"]:"#"}}">{{$item["text"]}}  <i class="fas fa-caret-down"></i></a>
                    @if(!empty($item["items"]))


                     <ul data-submenu class="submenu">

                            @foreach($item["items"] as  $k => $subitem)

                                <li class="{{!empty($subitem["active"])?"active":""}}" data-item>

                                    <a href="{{!empty($subitem["href"])?$subitem["href"]:"#"}}">
                                      {{$subitem["text"]}}
                                    </a>

                                    @if(!empty($subitem["image"]))
                                        <img src="{{$subitem["image"]}}">
                                    @endif

                                </li>
                                @if($k == 0)

                                 <li class="caret"><i class="fas fa-caret-up"></i></li>

                                @endif

                            @endforeach
                        </ul>
                    @endif

                </li>
            @endforeach
        </ul>
    </nav>

    <script>

        /*
        (function () {

            var navbar = $($("[data-navbar]").get(-1));
            $(document).on("click",function (e) {

                $("[data-navbar]").prop("checked",false);

                if(navbar.has(e.target).length && !(navbar[0] == e.target))
                {
                    console.log(navbar[0]);

                    navbar.prop("checked",true);
                }

            })




        })()*/
        /*if(navbar.has($(event.target)).length && !navbar.is($(event.target)))
         {
         console.log("CLICK");
         }*/
        
    </script>

@endif