export function slugify(value) {
    value = value.replace(/^\s+|\s+$/g, ''); // trim
    value = value.toLowerCase();

    // remove accents, swap ñ for n, etc
    const from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;əüşçöüığ";
    const to   = "aaaaaeeeeeiiiiooooouuuunc------euscouig";
    for (var i=0, l=from.length ; i<l ; i++) {
        value = value.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    value = value.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
        .replace(/\s+/g, '-') // collapse whitespace and replace by -
        .replace(/-+/g, '-'); // collapse dashes

    return value;
}
export function truncate(fullStr, strLen, separator) {
    if (fullStr.length <= strLen) return fullStr;

    separator = separator || '...';

    var sepLen = separator.length,
        charsToShow = strLen - sepLen,
        frontChars = Math.ceil(charsToShow/2),
        backChars = Math.floor(charsToShow/2);

    return fullStr.substr(0, frontChars) +
        separator +
        fullStr.substr(fullStr.length - backChars);
};

export function b64(file, callback) {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function () {
        return callback(reader.result);
    };
    reader.onerror = function (error) {
        return callback('Error');
    };
}
export function sizeOfFile({size}){
    let s = size / (1024 * 1024);
    return  `${Math.floor(s * 100) / 100} MB`;
}

export function statusclass(status) {
    switch(status) {
        case 1: // Yeni Geldi
            return {icon: 'icon-certificate', title: 'Yeni mesaj'};
            break;
        case 2: // Operator qebul eledi
            return {icon: 'icon-mail-envelope-open', title: 'Operator qəbul edib'};
            break;
        case 3: // Operator cavab yazdi
            return {icon: 'icon-inbox-upload2', title: 'Operator cavab yazdi'};
            break;
        case 4: // Musderi cavab yazdi
            return {icon: 'icon-inbox-download2', title: 'Müştəri cavab yazdı'};
            break;
        case 5: // Mesaj sonlandi
            return {icon: 'icon-mail-checked2', title: 'Mesajlaşma sona çatdı'};
            break;
    }
}
export function checkurl(url) {
    const regex = /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;
    return regex.test(String(url).toLowerCase());
}
export function extractHostname(url) {
    var hostname;
    //find & remove protocol (http, ftp, etc.) and get hostname

    if (url.indexOf("//") > -1) {
        hostname = url.split('/')[2];
    }
    else {
        hostname = url.split('/')[0];
    }

    //find & remove port number
    hostname = hostname.split(':')[0];
    //find & remove "?"
    hostname = hostname.split('?')[0];

    return hostname;
}
export function extractdomain(url) {
        var domain = extractHostname(url),
            splitArr = domain.split('.'),
            arrLen = splitArr.length;

        //extracting the root domain here
        //if there is a subdomain
        domain = '';
        for (let i = 0; i < arrLen; i++ ) {
            if (i !== 0) {
                domain += splitArr[i]
                if (i < arrLen - 1) {
                    domain += '.';
                }
            }
        }
        return domain;
}
export function urlchextractor(url) {
    if(checkurl(url)) {
        console.log('here');
        return extractdomain(url);
    } else {
        return false;
    }
}

export function PrintElem(elem)
{
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');

    mywindow.document.write('</head><body >');
    mywindow.document.write(document.getElementById(elem).innerHTML);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    mywindow.close();

    return true;
}



