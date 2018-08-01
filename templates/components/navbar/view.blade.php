
@if(!empty($items))

    <nav>
        <ul>
            @foreach($items as $item)
                <li >
                    <a href="{{!empty($item["href"])?$item["href"]:""}}">{{$item["text"]}}</a>

                    @if(!empty($item["items"]))
                        <ul>
                            @foreach($item["items"] as $subitem)
                                <li>
                                    <a href="{{!empty($subitem["href"])?$subitem["href"]:""}}">
                                      {{$subitem["text"]}}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                </li>
            @endforeach
        </ul>
    </nav>

@endif