<!DOCTYPE html>
<html>
<head>
    @vite('resources/css/bd_data_viewer.css')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="prices-page">
        <div class="tab-container" id="tabs">
            <div class="tab open" data-show="prices">Prices</div>
            <div class="tab" data-show="consumptions">Consumptions</div>
        </div>

        <div class="prices-body">
            <div class="prices-table-scroll" id="prices-table">
                <table class="prices-table">
                    <thead>
                        <tr class="sticky-header">
                            <th class="col-sticky col-date">Fecha</th>
                            @for($i = 1; $i < 26; $i++)
                                <th>{{ 'H' . $i}}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['prices'] as $columns)
                            <tr key={row.id}>
                                <td class="col-sticky col-date">{{ $columns['date'] }}</td>
                                @foreach(json_decode($columns) as $key => $col)
                                    @if($key != 'date')
                                        <td>{{$col}}</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="prices-table-scroll" id="consumptions-table" style="display: none">
                <table class="prices-table">
                    <thead>
                        <tr>
                            <th class="col-sticky col-date">Fecha</th>
                            @for($i = 1; $i < 26; $i++)
                                <th>{{ 'H' . $i}}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['consumptions'] as $columns)
                            <tr key={row.id}>
                                <td class="col-sticky col-date">{{ $columns['date'] }}</td>
                                @foreach(json_decode($columns) as $key => $col)
                                    @if($key != 'date')
                                        <td>{{$col}}</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
            function initTabs(){
                var tab_cont = document.getElementById('tabs');

                var tabs = tab_cont.querySelectorAll('.tab');

                for(var tab of tabs){
                    tab.addEventListener('click', (event) => {
                        var show = event.currentTarget.dataset.show;
                        var table_prices        = document.getElementById('prices-table');
                        var table_consumptions  = document.getElementById('consumptions-table');
                        switch(show){
                            case 'prices':
                                table_prices.style.display = "";
                                table_consumptions.style.display = "none";
                                break;
                            case 'consumptions':
                                table_prices.style.display = "none";
                                table_consumptions.style.display = "";
                                break;
                        }

                        for(var tab_ of tabs){
                            tab_.classList.remove('open')
                        }

                        event.currentTarget.classList.add('open');
                        console.log("hola");
                    });
                }
            }

            initTabs();
    </script>
</body>
</html>