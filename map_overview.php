<?php
require_once('header.php');

$sql = <<<EOT
SELECT 
    a.aid,
    a.service,
    a.umzugsstatus,
    a.antragsdatum,
    usr.personalnr AS KID,
    g.lat,
    g.lng,
    SUM(al.menge_mertens * klg.preis_pro_einheit) AS Summe,
    GROUP_CONCAT(
      CONCAT(ktg.kategorie_abk, klg.leistung_abk) SEPARATOR ","
    ) AS LAbk,
    CONCAT(
        "[",
        GROUP_CONCAT(
          CONCAT(
            "{",
            '"LstId":', al.leistung_id, ",",
            '"KtgId":', ktg.leistungskategorie_id, ",",
            '"Kategorie":"', REPLACE(ktg.leistungskategorie, '"', '\\"'), '",',
            '"Ktg":"', REPLACE(ktg.kategorie_abk, '"', '\\"'), '",',
            '"Lstg":"', REPLACE(klg.leistung_abk, '"', '\\"'), '",',
            '"Bezeichnung":"', REPLACE(klg.Bezeichnung, '"', '\\"'), '",',
            '"Farbe":"', REPLACE(klg.Farbe, '"', '\\"'), '",',
            '"Groesse":"', REPLACE(klg.Groesse, '"', '\\"'), '",',
            '"Menge":', al.menge_mertens,
            "}"
            )
            SEPARATOR ","
        ),
        "]"
    ) AS jsonLeistungen
    FROM mm_umzuege AS a 
    JOIN mm_user AS usr ON (a.antragsteller_uid = usr.uid)
    JOIN mm_umzuege_leistungen AS al ON (a.aid = al.aid)
    JOIN mm_leistungskatalog AS klg ON (al.leistung_id = klg.leistung_id)
    JOIN mm_leistungskategorie AS ktg ON (klg.leistungskategorie_id = ktg.leistungskategorie_id)
    JOIN mm_geolocations AS g ON (g.orig_target = "ors_uniper.mm_umzuege.aid" AND a.aid = g.orig_target_id)
    WHERE a.umzugsstatus = "beantragt" AND IFNULL(a.tour_kennung, "") = "" 
    GROUP BY a.aid, a.service, a.umzugsstatus, usr.personalnr, g.lat, g.lng
EOT;
$rows = $db->query_rows($sql);

function getJsonErrorByCodeId(int $codeId) {
    switch($codeId) {
        case JSON_ERROR_NONE: return "[$codeId] " . 'Kein Fehler aufgetreten.';
        case JSON_ERROR_DEPTH: return "[$codeId] " . ' Die maximale Stacktiefe wurde überschritten.';
        case JSON_ERROR_STATE_MISMATCH: return "[$codeId] " . 'Ungültiges oder missgestaltetes JSON';
        case JSON_ERROR_CTRL_CHAR: return "[$codeId] " . 'Steuerzeichenfehler, möglicherweise unkorrekt kodiert.';
        case JSON_ERROR_SYNTAX: return "[$codeId] " . 'Syntaxfehler.';
        case JSON_ERROR_UTF8: return "[$codeId] " . 'Missgestaltete UTF-8 Zeichen, möglicherweise fehlerhaft kodiert';
        case JSON_ERROR_RECURSION: return "[$codeId] " . 'Eine oder mehrere rekursive Referenzen im zu kodierenden Wert';
        case JSON_ERROR_INF_OR_NAN: return "[$codeId] " . 'Eine oder mehrere NAN oder INF Werte im zu kodierenden Wert';
        case JSON_ERROR_UNSUPPORTED_TYPE: return "[$codeId] " . 'Ein Wert eines Typs, der nicht kodiert werden kann, wurde übergeben';
        case JSON_ERROR_INVALID_PROPERTY_NAME: return "[$codeId] " . 'Ein Eigenschaftsname, der nicht kodiert werden kann, wurde übergeben';
        case JSON_ERROR_UTF16: return "[$codeId] " . 'Deformierte UTF-16 Zeichen; möglicherweise fehlerhaft kodiert';
        default: return "[$codeId] Unbekannter Fehlerocde!";
    }
}

$jsonData = '[';
for($i = 0; $i < count($rows); $i++) {
    $row = $rows[$i];
    $jsonItem = '{';
    foreach($row as $k => $v) {
        if (strlen($jsonItem) > 3) {
            $jsonItem.= ', ';
        }
        $jsonItem.= $db->quote($k) . ': ';
        if (is_numeric($v)) {
            $jsonItem.= $v;
        } elseif (is_null($v)) {
            $jsonItem.= 'null';
        }
        elseif (strpos($v, '{') !== 0 && strpos($v, '[') !== 0) {
            $jsonItem.= json_encode($v);
        } else {
            $test = json_decode($v, false, 10);
            $error = json_last_error();
            if ($error) {
                echo '#' . __LINE__ . ' ' . __FILE__ . '<br>AID ' . $row['aid'] . ' k: ' . $k . ' ' . getJsonErrorByCodeId($error) . '<br>' . "\n";
                echo '<pre>' . $v . '</pre>' . "\n";
                echo '<pre>' . $sql . '</pre>' . "\n";
                exit;
            }
            $jsonItem.= !json_last_error() ? $v : json_encode($v);
        }
    }
    $jsonItem.= '}';
    $jsonData.= ($i > 0 ? ",\n" : '') . '  ' . $jsonItem;
}
$jsonData.= ']';
/**
 * Created by PhpStorm.
 * User: f.barthold
 * Date: 09.02.2022
 * Time: 15:31
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Waypoints in Directions</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #container {
            height: 100%;
            display: flex;
        }

        #sidebar {
            flex-basis: 15rem;
            flex-grow: 2;
            padding: 1rem;
            max-width: 35rem;
            height: 100%;
            box-sizing: border-box;
            overflow: auto;
        }

        #map {
            flex-basis: 0;
            flex-grow: 2;
            height: 100%;
            width:50%;
        }

        #directions-panel {
            margin-top: 10px;
        }

        .row {
            display:flex;
            box-sizing: border-box;
        }

        .row .col {
            flex: 1 1 auto;
            border: 1px solid gray;
            box-sizing: border-box;
        }
        .row .col.act {
            width: 8%;
            max-width:50px;
            text-overflow: clip;
        }
        .row .col.abfahrt {
            width: 10%;
            text-align: center;
        }
        .row .col.ankunft {
            width: 10%;
            text-align: center;
        }
        .row .col.start_address {
            display: none;
        }
        .row .col.end_address {
            width: 40%;
        }
        .row .col.distance {
            width: 10%;
            text-align: right;
        }

        .row .col.duration {
            width: 10%;
            text-align: right;
        }
    </style>
    <script>
        var jsonData = <?= $jsonData ?>;
        var map = null;

        function setMarker(map, lat, lng, jsonItem) {
            var title = jsonItem.KID;
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng( lat, lng),
                map: map,
                title
            });

            var contentString = '<div id="content"><h1>' + jsonItem.KID+ ' ' + jsonItem.Summe.toFixed(2).replace('.', '.') + ' €</h1>' +
                jsonItem.antragsdatum + ', ' + jsonItem.umzugsstatus + ', ' + jsonItem.Summe.toFixed(2).replace('.', '.') + ' €';

            contentString+= '</div>';
            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });


            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map,marker);
            });
        }

        function addMarker(map, lat, lng, label, title, content) {
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng( lat, lng),
                map: map,
                label,
                title
            });

            var contentString = '<div id="content"><h1>' + title + '</h1>' + content;
            contentString+= '</div>';

            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });


            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map,marker);
            });

            return marker;
        }


        function setMarkersFromJsonData() {
            for(var i = 0; i < jsonData.length; i++) {
                var jsonItem = jsonData[i];
                var lat = jsonItem.lat;
                var lng = jsonItem.lng;
                setMarker(map, lat, lng, jsonItem);
            }
        }

        function clearWaypointsInput() {
            $("#fbWaypoints").val("");
        }


        function secondsToTime(seconds, format = "%H:%M") {
            const s = seconds % 60;
            const m = Math.floor(seconds % 3600 / 60 );
            const h = Math.floor(seconds / 3600);

            if (format.trim() === "" ) {
                format = "%H:%M";
            }

            const S = (s < 10 ? "0" : "") + s.toString(10);
            const M = (m < 10 ? "0" : "") + m.toString(10);
            const H = (h < 10 ? "0" : "") + h.toString(10);

            if (format === "duration") {
                let dur = "";
                if (h) {
                    dur = h + " H";
                }

                dur = (dur.length ? dur + " " : "") + m + " Min";

                return dur;
            }

            const rpl = {
                '%s': s.toString(10),
                '%m': m.toString(10),
                '%h': h.toString(10),
                '%S': S,
                '%M': M,
                '%H': H,
            };

            let re = format;
            for(const n in rpl) {
                if (re.indexOf(n) > -1) {
                    re.split(n).join(rpl[n]);
                }
            }

            return re;
        }

        function elmAttr(elm, attributes = null) {
            if (attributes && Object.keys(attributes).length > 0) {
                for(const a in attributes) {
                    elm.setAttribute(a, attributes[a]);
                }
            }
            return elm;
        }

        function elmClass(elm, classNames = null) {
            if (typeof classNames === 'string' && classNames.trim().length > 0) {
                elm.className = classNames;
            }
            else if (Array.isArray(classNames) && classNames.length > 0) {
                elm.className = classNames.join(" ");
            }
            return elm;
        }

        function elmCss(elm, css = null) {
            if (css && (typeof css === "object") && Object.keys(css).length > 0) {
                for(const c in css) {
                    var cName = c;
                    if (c.indexOf("-") > -1) {
                        cName = c.split("-").map( (val, idx) => {
                            if (idx === 0) {
                                return val.toLowerCase();
                            }
                            return val.charAt(0).toUpperCase() + val.substr(1).toLowerCase();
                        }).join("");
                    }
                    elm.style[cName] = css[c];
                }
            }
            return elm;
        }

        function createElm(tagName, attributes = null,  classNames = null, css = null) {
            const elm = document.createElement(tagName);
            if (attributes) {
                elmAttr(elm, attributes);
            }
            if (classNames) {
                elmClass(elm, classNames);
            }
            if (css) {
                elmCss(elm, css);
            }
            return elm;
        }

        function calculateAndDisplayWaypoints(
            directionsService,
            directionsRenderer
        ) {
            const waypts= [];
            const elmWaypoints = document.getElementById(
                "fbWaypoints"
            );
            const elmAvgStayMinutes = document.getElementById("fbAvgBreak");
            const waypointAvgStayMinutes = +elmAvgStayMinutes.value;
            const waypointsText = elmWaypoints.value.trim();

            const waypointsArray = waypointsText.split("\n");


            const waypointsStay = waypointsArray.map(function(v) {
                if (v.split(";").length > 1) {
                    let dur = v.split(";")[1];
                    let durParseInt = parseInt(dur);
                    return durParseInt || waypointAvgStayMinutes;
                } else {
                    return waypointAvgStayMinutes;
                }
            });

            for(var i  = 0; i < waypointsArray.length; i++) {
                waypointsArray[i] = waypointsArray[i].split(";")[0];
            }

            for (let i = 0; i < waypointsArray.length; i++) {
                if (waypointsArray[i]) {
                    waypts.push({
                        location: waypointsArray[i],
                        stopover: true,
                    });
                }
            }

            const departureTime = new Date();
            departureTime.setHours( departureTime.getHours() + 24);
            departureTime.setHours(8);

            directionsService
                .route({
                    origin: (document.getElementById("fbStart")).value,
                    destination: (document.getElementById("fbEnd")).value,
                    waypoints: waypts,
                    drivingOptions: {
                        departureTime,
                        trafficModel: google.maps.TrafficModel.BEST_GUESS
                    },
                    optimizeWaypoints: true,
                    travelMode: google.maps.TravelMode.DRIVING,
                })
                .then((response) => {
                    directionsRenderer.setDirections(response);

                    var currDateTime = new Date(departureTime);

                    const route = response.routes[0];
                    const summaryPanel = document.getElementById(
                        "directions-panel"
                    );

                    let reorderedWaypointsTxt = '';
                    summaryPanel.innerHTML = "";

                    const waypoint_order = route.waypoint_order;
                    const row = createElm("div", {}, "row", {});
                    const colAct = createElm("div", {}, "col act", {});
                    const colStartTime = createElm("div", {}, "col abfahrt", {});
                    const colArrivalTime = createElm("div", {}, "col ankunft", {});
                    const colFrom = createElm("div", {}, "col start_address", {});
                    const colTo = createElm("div", {}, "col end_address", {});
                    const colDist = createElm("div", {}, "col distance", {});
                    const colDur = createElm("div", {}, "col duration", {});
                    colAct.innerText = 'Nr';
                    colStartTime.innerText = 'Start';
                    colArrivalTime.innerText = 'Ankunft';
                    colTo.innerText = 'Adresse';
                    colDist.innerText = 'Strecke';
                    colDur.innerText = 'Dauer';

                    summaryPanel.appendChild(row);

                    // For each route, display summary information.
                    for (let i = 0; i < route.legs.length; i++) {
                        const routeSegment = i + 1;

                        const origIdx = waypoint_order[i];

                        const row = createElm("div", {}, "row", {});
                        const colAct = createElm("div", {}, "col act", {});
                        const colStartTime = createElm("div", {}, "col abfahrt", {});
                        const colArrivalTime = createElm("div", {}, "col ankunft", {});
                        const colFrom = createElm("div", {}, "col start_address", {});
                        const colTo = createElm("div", {}, "col end_address", {});
                        const colDist = createElm("div", {}, "col distance", {});
                        const colDur = createElm("div", {}, "col duration", {});

                        row.appendChild(colAct);
                        row.appendChild(colTo);
                        row.appendChild(colDist);
                        row.appendChild(colStartTime);
                        row.appendChild(colDur);
                        row.appendChild(colArrivalTime);

                        const h1 = currDateTime.getHours();
                        const m1 = currDateTime.getMinutes();
                        const abfahrt = (h1 < 10 ? "0" : "") + h1.toString() + ":" + (m1 < 10 ? "0" : "") + m1.toString();

                        currDateTime.setSeconds( currDateTime.getSeconds() +  route.legs[i].duration.value );
                        const h = currDateTime.getHours();
                        const m = currDateTime.getMinutes();
                        const ankunft = (h < 10 ? "0" : "") + h.toString() + ":" + (m < 10 ? "0" : "") + m.toString();

                        colAct.innerText = routeSegment.toString(10);
                        colStartTime.innerText = abfahrt;
                        colArrivalTime.innerText = ankunft;
                        colDist.innerText = route.legs[i].distance.text;
                        colDur.innerText = secondsToTime(route.legs[i].duration.value, "duration");
                        colTo.innerText = route.legs[i].end_address;

                        const loc = route.legs[i].end_location;

                        summaryPanel.appendChild( row );

                        const waypointStay = (i < waypointsStay.length) ? waypointsStay[origIdx] : 0;

                        currDateTime.setSeconds( currDateTime.getSeconds() + (waypointStay * 60) );


                        if ((i < waypointsStay.length)) {


                            const title = route.legs[i].end_address + ' ' + waypointStay + 'Min.';
                            const content = "Lat: " + loc.lat() + ", Lng: " + loc.lng();
                            setTimeout(function() {
                                const marker = addMarker(map, loc.lat(), loc.lng(), (i + 1).toString(), title, content);
                                console.log("addMarker(map, " + loc.lat() + ", " + loc.lng() + ", \"" + (i+1) + "\",\"" + title + "\", \"" + content + "\")", marker);
                            }, 2000);
                            reorderedWaypointsTxt += route.legs[i].end_address + "; " + waypointStay + "m\n";
                        }
                    }
                    elmWaypoints.value = reorderedWaypointsTxt;
                })
                .catch(function(e) {
                    console.error(e);
                    window.alert("Directions request failed due to " + JSON.stringify(e));
                });
        }

        async function geocode(request) {
            return geocoder
                .geocode(request)
                .then((response) => {
                    const { results } = response;
                    const location = results[0].geometry.location;
                    console.log({ location });
                    return { lat: location.lat(), lng: location.lng() };
                })
                .catch((e) => {
                    throw Error("Geocoding for " + request.address + " was not successful for the following reason: " + e);
                });
        }

        function initMap() {
            geocoder = new google.maps.Geocoder();
            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer();

            const mapDiv = document.getElementById("map");
            map = new google.maps.Map(
                mapDiv,
                {
                    zoom: 6,
                    center: { lat: 41.85, lng: -87.65 },
                }
            );

            map.setCenter({lat: 51.26770519999999, lng: 6.527355999999999});

            if (0) {
                navigator.geolocation.getCurrentPosition(function (prop) {
                    map.setCenter({lat: prop.coords.latitude, lng: prop.coords.longitude});
                });
            }

            directionsRenderer.setMap(map);

            (document.getElementById("btnOptimizeWaypoints")).addEventListener(
                "click",
                () => {
                    calculateAndDisplayWaypoints(directionsService, directionsRenderer);
                }
            );

            setMarkersFromJsonData();
        }
    </script>
</head>
<body>
<div id="container">
    <div id="map"></div>
    <div id="sidebar">
        Startzeit:
        <div>
            <input type="date" xstyle="width:45%" id="fbStartDate"><input type="time" xstyle="width:45%" id="fbStartTime">
        </div>
        Start:
        <input type="text" style="width:100%" id="fbStart" placeholder="Start-Adresse" value="Stahlwerk Becker 8, 47877 Willich, Deutschland">
        Wegpunkte:
        <textarea id="fbWaypoints" style="resize: vertical;overflow:auto;height: 15vh;min-height:5vh;width:100%">Am alten Burghof 13, 40883 Ratingen, Deutschland
Tack 10, 47918 Tönisvorst, Deutschland
Pastoratsweg 15, 40489 Düsseldorf, Deutschland
Rayener Straße 64, 47506 Neukirchen-Vluyn, Deutschland
Krüllsdyk 16, 47803 Krefeld, Deutschland
Beuthener Str. 60, 40883 Ratingen, Deutschland</textarea>

        Ende:
        <input type="text" style="width:100%" id="fbEnd" placeholder="End-Adresse" value="Stahlwerk Becker 8, 47877 Willich, Deutschland"><br>
        <br>
        Geschätzte Aufenthaltsdauer pro Adresse in Minuten:<br>
        <input type="text" style="width:100%" id="fbAvgBreak" placeholder="End-Adresse" value="30"><br>
        <button type="button" id="btnOptimizeWaypoints">Optimale Verbindungen suchen</button>

        <div id="directions-panel"></div>
    </div>
    <hr>
    <hr>
<!--    <iframe src="https://www.google.com/maps/d/u/1/embed?mid=1TAkoI0s_RjK6JwAeqTsDyBD_y7KYbDjc&ehbc=2E312F" width="640" height="480"></1--iframe> -->
</div>


<!-- Async script executes immediately and must be after any DOM elements used in callback. -->
<!-- ors-unip-key: AIzaSyDR23cAoxzym9G3fQ4yYaXlwrxyyqs_zb0 -->
<!-- ors-unip-key-error: AIzaSyCuemvCO1RtYCnOOgYvVDmYLYqOPYRZOVI -->
<!-- sample-key: AIzaSyDcwGyRxRbcNGWOFQVT87A1mkxEOfm8t0w -->
<!-- gruene-wiese-2022 apikey: AIzaSyBqXGv1kg-0Fz6cHnbvac-LqVs4E4iGSlk -->
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqXGv1kg-0Fz6cHnbvac-LqVs4E4iGSlk&callback=initMap&v=weekly"
    async
></script>
</body>
<script>
    var dt = new Date(),
        h = dt.getHours(),
        m = dt.getMinutes(),
        hh = (h < 10 ? '0' : '') + h,
        mm = (m < 10 ? '0' : '') + m;
    fbStartDate.value = dt.toISOString().substr(0, 10);
    fbStartTime.value = hh + ":" + mm;
</script>
</html>

