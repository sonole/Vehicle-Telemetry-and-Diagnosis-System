import './bootstrap';
import AirDatepicker from "air-datepicker";
const customLocale = {
    days: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
    daysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    daysMin: ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],
    months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    today: 'Today',
    clear: 'Clear',
    dateFormat: 'dd/MM/yyyy',
    timeFormat: 'HH:mm',
    firstDay: 0
};

window.air = () => {
    let dpMin, dpMax;
    dpMin = new AirDatepicker('#datestart', {
        locale: customLocale,
        buttons: ['clear'],
        timepicker: true,
        onSelect({date}) {
            dpMax.update({
                minDate: date
            })
        }
    });
    dpMax = new AirDatepicker('#datefinish', {
        locale: customLocale,
        buttons: ['clear'],
        timepicker: true,
        onSelect({date}) {
            dpMin.update({
                maxDate: date,
            })
        }
    });

}

//Draw the $path var
window.draw = async (ms) => {
    let drawnPath = [];
    for(let i=0; i < packetsNo ; i++ ) {
        drawnPath.push([path[i]['GPS']['lat'], path[i]['GPS']['lng']]);
    }
    //add start point
    addCustomMarker([path[0]['GPS']['lat'], path[0]['GPS']['lng']], 'Start', 'Trip starts', '\ue569' );
    //draw the line
    for (let i=0; i < packetsNo -1; i++ ) {
        await sleep(ms);
        map.drawPolyline({
            path: [
                [ drawnPath[i][0], drawnPath[i][1] ],
                [ drawnPath[i+1][0], drawnPath[i+1][1] ],
            ],
            strokeColor: '#ff0000', strokeOpacity: 0.8,  strokeWeight: 4
        });
    }
    //add finish point
    addCustomMarker([path[packetsNo-1]['GPS']['lat'], path[packetsNo-1]['GPS']['lng']], 'Finish', 'Trip ends', '\ue153' );

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms))
    }
}

//Custom marker with more functionality
window.addCustomMarker = (coordinates, title, content , image) => {
    //https://fonts.google.com/icons?selected=Material+Icons
    let opts = {
        lat: coordinates[0],
        lng: coordinates[1],
    }
    if (title != null) {
        opts.title = title
    }
    if (content != null) {
        opts.infoWindow = { content: '<p>Details: '+content+'</p>' }
    }
    if (image != null ) {
        opts.label =  { text: image, fontFamily: "Material Icons", fontSize: "18px" }
    }
    map.addMarker(opts);
}

//Extra buttons at map
window.button = (type) => {
    if (type === 'remove_markers') {
        map.addControl({
            position: 'top_right',
            content: 'Remove Markers',
            style: { margin: '10px', padding: '5px 11px',
                border: 'groove 1px #717B87', background: '#fff'  },
            events: { click: function(){ map.removeMarkers(); } }
        });
    }
}

window.tableWithPagination = () => {
    // Select all the pagination buttons
    const paginateButtons = document.querySelectorAll('button[data-page]');

    // Add an event listener to each button that displays the appropriate elements
    paginateButtons.forEach((button) => {
        button.addEventListener('click', () => {
            //Init color at all paginate buttons
            for(let i=0; i < paginateButtons.length; i++) {
                document.getElementById(paginateButtons[i]['id']).style.color = '#047857';
            }

            // Get the page number from the button's data-page attribute
            const pageNumber = button.getAttribute('data-page');
            //Change color at the clicked one
            document.getElementById(button['id']).style.color = 'white';

            // Get the elements for the current page
            const data = paginateArray(path, 10, pageNumber);

            // For each pagination number make the thead only 1 time
            // For each pagination number make the tbody
            document.getElementById("tablebody").innerHTML = '';
            let thead = [];
            const elem = document.querySelector("#tablehead")
            let isHeadCreated = elem.childNodes.length ;
            for(let i=0; i < data.length; i++) {
                let cols = '';

                Object.keys(data[i]['general']).forEach( ( key ) => {
                    if ( key !== 'sn'  ){
                        if (isHeadCreated === 0 ) { thead.push(key) }
                        cols += '<td class="p-1.5">'+ data[i]['general'][key] +'</td>';
                    }
                })

                Object.keys(data[i]['GPS']).forEach( ( key ) => {
                    if ( key !== 'lat' && key !== 'lng') {
                        if (isHeadCreated === 0 ) { thead.push(key) }
                        cols += '<td class="p-1.5">'+ data[i]['GPS'][key] +'</td>';
                    }
                })
                Object.keys(data[i]['OBD']).forEach( ( key ) => {
                    if ( isHeadCreated === 0 ) { thead.push(key) }
                    cols += '<td class="p-1.5">'+ data[i]['OBD'][key] +'</td>';
                })

                if (isHeadCreated === 0 ) {
                    let thRow = '<tr class="border-b border-emerald-700">';
                    thead.forEach( ( key ) => {
                        thRow += '<th class="px-1.5 py-4">'+key+'</th>';
                    })
                    thRow += '</tr>';
                    document.getElementById("tablehead").innerHTML = thRow;
                    isHeadCreated = 1;
                }

                let row = '<tr onclick="rowClick(' + data[i]['GPS']['lat'] + ',' + data[i]['GPS']['lng']  + ',' + '\''+data[i]['general']['Date & Time'] +'\''+')" ' +
                    'onmouseout="rowHoverOut(this)" onmouseover="rowHoverIn(this)" class="text-emerald-500 text-md text-center">' +cols+' </tr>';

                document.getElementById("tablebody").innerHTML += row;
            }
        });
    });

    function paginateArray(array, pageSize, pageNumber) {
        // Calculate the starting index and ending index for the current page
        const startIndex = (pageNumber - 1) * pageSize;
        const endIndex = startIndex + pageSize;
        // Return a subarray of the original array that contains only the elements for the current page
        return array.slice(startIndex, endIndex);
    }
}

/*
 * Helpers
*/
window.rowClick = (lat, lng, dt) => {
    addCustomMarker([lat, lng],dt);
}
window.rowHoverIn = (e) => {
    e.style.cursor = 'pointer';
    e.style.color = '#01579b';
}
window.rowHoverOut = (e) => {
    e.style.cursor = 'auto';
    e.style.color = '#10b981';
}


//Gets the avg of $path to center the map correctly
window.getAvg = () => {
    for ( let i=0; i < packetsNo ; i++ ) {
        path[i]['GPS']['lat'] = parseFloat(path[i]['GPS']['lat']);
        path[i]['GPS']['lng'] = parseFloat(path[i]['GPS']['lng']);
        avgLat += path[i]['GPS']['lat']
        avgLng += path[i]['GPS']['lng']
        if (i === (packetsNo -1 )) {
            avgLat = avgLat / packetsNo;
            avgLng = avgLng / packetsNo;
        }
    }
}

//Used when clicking random at map
window.findClosestPair = (clickedCoordinates) => {
    let min =  getDistance(clickedCoordinates[0], clickedCoordinates[1], path[0]['GPS']['lat'], path[0]['GPS']['lng']);
    let minIndex = 0;
    for (let i = 1; i <= packetsNo-1 ; i++ ) {
        let temp = getDistance(clickedCoordinates[0], clickedCoordinates[1], path[i]['GPS']['lat'], path[i]['GPS']['lng'])
        if( temp < min ) {
            min = temp;
            minIndex = i;
        }
    }
    return [ path[minIndex]['GPS']['lat'], path[minIndex]['GPS']['lng'] ];
}

//Get the closest distance between 2 points
window.getDistance = (lat1, lon1, lat2, lon2) => {
    const R = 6371; // km
    var dLat = toRad(lat2-lat1);
    var dLon = toRad(lon2-lon1);
    var lat1 = toRad(lat1);
    var lat2 = toRad(lat2);

    var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;  //returns distance in meters

    function toRad(Value) {
        return Value * Math.PI / 180;
    }
}


