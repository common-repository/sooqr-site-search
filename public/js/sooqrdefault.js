var _wssq = _wssq || [];
var setResizeFunction = false;
var sooqrAccount = '';
var inputID = 'search';

//DO NOT EDIT ANYTHING ABOVE THIS LINE

if (!(document.querySelector('input#'+inputID))) {
    var inputFields = document.querySelectorAll('input[name=s]');
    var inputArrayIDs = [];
    for (i = 0; i < inputFields.length; i++) {
        inputFields[i].id = inputID + i;
        inputArrayIDs.push(inputID + i);
    }
}
else {
    inputArrayIDs = inputID;
}

_wssq.push(['_load', {
    'suggest': {
        'account': 'SQ-' + sooqrAccount,
        'version': 4,
        fieldId: inputArrayIDs
    }
}]);
_wssq.push(['suggest._setPosition', 'screen-middle', {
    top: 10
}]);
_wssq.push(['suggest._setLocale', 'nl_NL']);
_wssq.push(['suggest._excludePlaceholders', 'Zoek producten']);
_wssq.push(['suggest._bindEvent', 'open', function() {
    if (!setResizeFunction) {
        $jQ(window).resize(function() {
            if ($jQ('.sooqrSearchContainer-' + sooqrAccount).is(':visible')) {
                websight.sooqr.instances['SQ-' + sooqrAccount].positionContainer(null, null, true);
            }
        });
        setResizeFunction = true;
    }
}]);
(function() {
    var ws = document.createElement('script');
    ws.type = 'text/javascript';
    ws.async = true;
    ws.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'static.sooqr.com/sooqr.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(ws, s);
})();