    var Ziggy = {
        namedRoutes: {"generated::oUEb9PCtSgZhmX0R":{"uri":"airlock\/csrf-cookie","methods":["GET","HEAD"],"domain":null},"auth.register":{"uri":"api\/auth\/register","methods":["POST"],"domain":null},"auth.login":{"uri":"api\/auth\/login","methods":["POST"],"domain":null},"auth.logout":{"uri":"api\/auth\/logout","methods":["POST"],"domain":null},"api.user":{"uri":"api\/user","methods":["GET","HEAD"],"domain":null},"exercises.index":{"uri":"api\/exercises","methods":["GET","HEAD"],"domain":null},"exercises.show":{"uri":"api\/exercises\/{exercise}","methods":["GET","HEAD"],"domain":null},"exercises.store":{"uri":"api\/exercises","methods":["POST"],"domain":null},"exercises.update":{"uri":"api\/exercises\/{exercise}","methods":["PUT"],"domain":null},"exercises.destroy":{"uri":"api\/exercises\/{exercise}","methods":["DELETE"],"domain":null},"activity.index":{"uri":"api\/activities","methods":["GET","HEAD"],"domain":null},"activity.show":{"uri":"api\/activities\/{activity}","methods":["GET","HEAD"],"domain":null},"activity.store":{"uri":"api\/activities","methods":["POST"],"domain":null},"activity.update":{"uri":"api\/activities\/{activity}","methods":["PUT"],"domain":null},"activity.destroy":{"uri":"api\/activities\/{activity}","methods":["DELETE"],"domain":null},"generated::8QhwpCNqFgApRp8B":{"uri":"{any}","methods":["GET","HEAD"],"domain":null}},
        baseUrl: 'http://reminder.local/',
        baseProtocol: 'http',
        baseDomain: 'reminder.local',
        basePort: false,
        defaultParameters: []
    };

    if (typeof window.Ziggy !== 'undefined') {
        for (var name in window.Ziggy.namedRoutes) {
            Ziggy.namedRoutes[name] = window.Ziggy.namedRoutes[name];
        }
    }

    export {
        Ziggy
    }
