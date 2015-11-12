//

if ( 'undefined' == typeof MMSF ) {
    var MMSF = {};
}

MMSF.date = {};
MMSF.date.weeks = [
    'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
];
MMSF.date.getDayname = function() {
    var today = new Date();
    return MMSF.date.weeks[today.getDay()];
}

MMSF.openWeatherMap = {};
MMSF.openWeatherMap.version = 2.5;