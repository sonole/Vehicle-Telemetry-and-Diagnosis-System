<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Telematic App</title>

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
        <link href="{{ asset('css/tail.css') }}" rel="stylesheet"/>
        <link href="{{ asset('dist/air-datepicker/air-datepicker.css') }}" rel="stylesheet"/>

        <script src="{{asset('js/gmaps.js')}}"></script>
        <script src="{{asset('js/app.js')}}"></script>
        <script src={{'https://maps.googleapis.com/maps/api/js?key='.env('GOOGLE_MAPS_API_KEY').'&callback=GMaps'}}></script>
    </head>

    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
            <div class="mx-auto dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg dark:text-white my-6">
                <div class="max-w-6xl items-center">
                    <!-- FORM -->
                    <div id="form" class="p-6 text-center">
                        <h1 class="leading-normal font-medium text-3xl mt-0 mb-2 text-emerald-700 p-4">Telematic App</h1>
                        <form action="{{ route('searchRoute') }}" method="GET" autocomplete="off" >
                            <input name="datestart" id="datestart"
                                   type='text' value="{{old('datestart')}}"
                                   class="text-center text-sm text-gray-600 bg-gray-300 border-2 border-gray-300 rounded-lg duration-300 mr-2"
                                   placeholder="Select start date"/>
                            <input name="datefinish" id="datefinish"
                                   type='text' value="{{old('datefinish')}}"
                                   class="text-center text-sm text-gray-600 bg-gray-300 border-2 border-gray-300 rounded-lg duration-300 mr-2"
                                   placeholder="Select finish date"/>
                            <script type="text/javascript">
                                air();
                            </script>
                            <button type="submit"
                                    class="text-center text-sm text-white bg-cyan-500 hover:bg-cyan-600 border-2 border-cyan-500 rounded-lg duration-300 px-4">Search</button>
                        </form>
                    </div>

                    <!-- MAP -->
                    <div id="map" class="w-full" style="color: black"></div>

                    <!-- EXTRA TEXT -->
                    @if( $errors->any() || isset($infoData) )
                        <div class="w-auto items-center text-center p-4">
                            <!-- ERRORS -->
                            @if ($errors->any())
                                <div class="text-red-600">
                                    <ul>
                                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                                    </ul>
                                </div>
                            @endif
                            <!-- INFO DATA -->
                            @isset($infoData)
                                <div class="p-6">
                                    @isset($infoData['text'])
                                        <h2 class="text-emerald-700	leading-normal font-medium text-xl mt-0 mb-1 p-3">{{ $infoData['text'] }}</h2>
                                    @endisset
                                    @isset($infoData['dt'])
                                        <h3 class="text-emerald-700 leading-normal font-medium text-md mb-2">
                                            Results from <strong class="text-emerald-500">{{ $infoData['dt']['start'] }}</strong>
                                            to <strong class="text-emerald-500"> {{ $infoData['dt']['end'] }}</strong>
                                        </h3>
                                    @endisset
                                </div>
                            @endisset
                        </div>
                    @endif

                    @isset($path)
                        <script type="text/javascript">
                            let avgLat = 0; let avgLng = 0; let path = @json($path);
                            let packetsNo = Object.keys(path).length;
                            let styles = [
                                { "featureType": "poi.business", "stylers": [ { "visibility": "off" } ] },
                                { "featureType": "poi.park", "elementType": "labels.text",  "stylers": [ { "visibility": "off" } ] }
                            ];
                        </script>
                        @if( count($path) > 1)
                            <div class="items-center text-center p-3 mx-auto">
                                <div class="border-solid border-2 border-emerald-700 rounded-t-lg" >
                                    <table class="shadow-sm overflow-hidden w-full table-auto text-medium text-emerald-700">
                                        <!-- JS prints data on head and body-->
                                        <thead id="tablehead"></thead>
                                        <tbody class="py-1.5" id="tablebody"></tbody>
                                        <tfoot>
                                            <!-- Pagination -->
                                            <tr><td><div>
                                                <span>Results</span>
                                                @php
                                                    $pageNumber = 1;
                                                    $pageSize = 10;
                                                    $totalPages = ceil(count($path) / $pageSize);
                                                @endphp
                                                @while($pageNumber <= $totalPages)
                                                    <button type="button" id="page-btn-{{$pageNumber}}" data-page="{{ $pageNumber }}">{{ $pageNumber }}</button>
                                                    @php($pageNumber++)
                                                @endwhile
                                            </div></td></tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <script type="text/javascript">
                                //Center map
                                window.getAvg();
                                map = new GMaps({
                                    styles: styles, div: '#map', zoom: 15,
                                    lat: avgLat, lng: avgLng,
                                    click: function (e) {
                                        let clickedCoordinates = [e.latLng.lat(), e.latLng.lng()];
                                        let closestCoordinates = findClosestPair(clickedCoordinates);
                                        addCustomMarker([closestCoordinates[0], closestCoordinates[1]]);
                                    },
                                });
                                //Add tor right button
                                window.button('remove_markers');
                                //Create table
                                window.tableWithPagination();
                                //Click pagination page 1
                                document.getElementById("page-btn-1").click();
                                // Draw the Path with delay
                                draw(30);
                            </script>
                        @else
                            <script type="text/javascript">
                                new GMaps({
                                    styles: styles, div: '#map', zoom: 11,
                                    lat: path[0][0], lng: path[0][1]
                                });
                            </script>
                        @endif
                    @endisset
                </div>
            </div>
        </div>
    </body>
</html>
