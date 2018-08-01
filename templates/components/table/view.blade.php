@if(!empty($rows) && !empty($headers))
    <div class="table">
        <div class="thead">
            <div class="tr">
                @foreach($headers as $header)
                <div class="th">
                        {{$header}}
                </div>
                @endforeach
            </div>
        </div>


        <div class="tbody">
            @foreach($rows as $row)
            <div class="tr">
                @foreach($headers as $k => $header)
                    <div class="td">
                        {{$row[$k]}}
                    </div>
                @endforeach
            </div>
            @endforeach
        </div>


    </div>


@endif
