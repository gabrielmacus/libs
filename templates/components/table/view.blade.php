
@if(!empty($rows) && !empty($headers))

    <div class="table {{$class}}">

        @isset($title)
        <h3 class="ttitle">{{$title}}</h3>
        @endisset

        <div class="thead">
            <div class="tr">
                @foreach($headers as $k=> $header)
                <div class="th {{$k}}">
                        {{$header}}
                </div>
                @endforeach
            </div>
        </div>


        <div class="tbody">
            @foreach($rows as $row)
            <div class="tr">

                @foreach($headers as $k => $header)

                    <div class="td {{$k}}">

                        @if(!isset($iterator))
                        {{$row[$k]}}
                        @else

                            @include($iterator,["property"=>$k,"value"=>$row[$k],"rows"=>$rows])

                        @endif


                    </div>
                @endforeach

            </div>
            @endforeach
        </div>
    </div>
@endif