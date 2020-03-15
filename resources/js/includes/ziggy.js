    var Ziggy = {
        namedRoutes: {"api.user":{"uri":"api\/user","methods":["GET","HEAD"],"domain":null},"auth.register":{"uri":"auth\/register","methods":["POST"],"domain":null},"auth.login":{"uri":"auth\/login","methods":["POST"],"domain":null},"auth.logout":{"uri":"auth\/logout","methods":["POST"],"domain":null}},
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
