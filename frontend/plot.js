window.onload=function(){

const url = "https://fbi.gruener-campus-malchow.de/cis/api/wetterstation?getData=1&request=Forschungscontainer";

var data = fetch(url)
.then(data=>{return data.json()});

Plotly.newPlot('myDiv', data, {}, {showSendToCloud: false});

};
