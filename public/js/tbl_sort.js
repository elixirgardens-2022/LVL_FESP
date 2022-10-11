$.fn.extend({
    sortVal: function() {
        let sortText = $(this).attr('sortcol-sorttext');
        if (typeof sortText !== 'undefined') {return sortText;}
        else {return $(this).text();}
    }
});

function isWhitespaceChar(a) {
    let charCode;
    charCode = a.charCodeAt(0);
    if (charCode <= 32) {return true;}
    else {return false;}
}

function isDigitChar(a) {
    let charCode;
    charCode = a.charCodeAt(0);
    if (charCode >= 48  && charCode <= 57) {return true;}
    else {return false;}
}

function compareRight(a, b) {
    let bias = 0;
    let ia = 0;
    let ib = 0;
    let ca;
    let cb;
    // The longest run of digits wins.  That aside, the greatest
    // value wins, but we can't know that it will until we've scanned
    // both numbers to know that they have the same magnitude, so we
    // remember it in BIAS.
    for (;; ia++, ib++) {
        ca = a.charAt(ia);
        cb = b.charAt(ib);
        if (!isDigitChar(ca) && !isDigitChar(cb)) {return bias;}
        else if (!isDigitChar(ca)) {return -1;}
        else if (!isDigitChar(cb)) {return 1;}
        else if (bias == 0 && ca < cb) {bias = -1;}
        else if (bias == 0 && ca > cb) {bias = 1;}
    }
}

function natsort(a, b) {
    let ia = 0, ib = 0;
    let nza = 0, nzb = 0;
    let ca, cb;
    let result;
    while (true) {
        // only count the number of zeroes leading the last number compared
        nza = nzb = 0;
        ca = a.charAt(ia);
        cb = b.charAt(ib);
        // skip over leading spaces or zeros
        while (isWhitespaceChar( ca ) || ca =='0') {
            if (ca == '0') {
                nza++;
            } else {
                // only count consecutive zeroes
                nza = 0;
            }
            ca = a.charAt(++ia);
        }
        while (isWhitespaceChar( cb ) || cb == '0') {
            if (cb == '0') {
                nzb++;
            } else {
                // only count consecutive zeroes
                nzb = 0;
            }
            cb = b.charAt(++ib);
        }
        // process run of digits
        if (isDigitChar(ca) && isDigitChar(cb)) {
            if ((result = compareRight(a.substring(ia), b.substring(ib))) != 0) {
                return result;
            }
        }
        if (ca == 0 && cb == 0) {
            // The strings compare the same.  Perhaps the caller
            // will want to call strcmp to break the tie.
            return nza - nzb;
        }
        if (ca < cb) {
            return -1;
        } else if (ca > cb) {
            return +1;
        }
        ++ia; ++ib;
    }
}

$(function() {
    $('th').mousedown(function() {
        let $selTable = $(this).closest('table');
        let $selTableBody = $(this).closest('table').children('tbody');
        let colNumber = $('th', $selTable).index(this) + 1;
        let sorted = $(this).attr('sortcol');
        
        if (typeof sorted === 'undefined') {
            sorted = 'desc';
            if ('time' == $(this)[0].id) {sorted = 'asc';}
        }
        
        let rowOrderBuffer = document.createDocumentFragment();
        
        $selTableBody.children('tr').sort(function(a, b) {
            if (sorted == 'desc') {
                return natsort($('td:nth-child(' + colNumber + ')', a).sortVal(), $('td:nth-child(' + colNumber + ')', b).sortVal());
            }
            else {
                return natsort($('td:nth-child(' + colNumber + ')', b).sortVal(), $('td:nth-child(' + colNumber + ')', a).sortVal());
            }
        }).appendTo(rowOrderBuffer);
        
        $(rowOrderBuffer).appendTo($selTableBody);
        
        $($('th'), $selTable).removeAttr('sortcol');
        
        if (sorted == 'desc') {$(this).attr('sortcol', 'asc');}
        else {$(this).attr('sortcol', 'desc');}
    });
});