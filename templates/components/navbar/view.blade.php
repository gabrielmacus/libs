
@if(!empty($items))

    <nav class="navbar">
        <ul>
            @foreach($items as $k => $item)
                <li >
                    <span class="mask"></span>
                    @if(!empty($item["items"]))
                    <input id="{{$item["text"].$k}}" type="checkbox">
                    @endif
                    <a href="{{!empty($item["href"])?$item["href"]:"#"}}">{{$item["text"]}}  <i class="fas fa-caret-down"></i></a>
                    @if(!empty($item["items"]))


                            <ul>
                            @foreach($item["items"] as  $subitem)
                                <li>
                                    <a href="{{!empty($subitem["href"])?$subitem["href"]:"#"}}">
                                      {{$subitem["text"]}}
                                    </a>

                                    @if(!empty($subitem["image"]))
                                        <img src="{{$subitem["image"]}}">
                                    @endif

                                </li>
                            @endforeach
                        </ul>
                    @endif

                </li>
            @endforeach
        </ul>
    </nav>

@endif