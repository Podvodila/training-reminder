    var Ziggy = {
        namedRoutes: {"generated::p6MujEibrnSCqtQ6":{"uri":"airlock\/csrf-cookie","methods":["GET","HEAD"],"domain":null},"auth.register":{"uri":"api\/auth\/register","methods":["POST"],"domain":null},"auth.login":{"uri":"api\/auth\/login","methods":["POST"],"domain":null},"auth.logout":{"uri":"api\/auth\/logout","methods":["POST"],"domain":null},"api.user":{"uri":"api\/user","methods":["GET","HEAD"],"domain":null},"exercises.index":{"uri":"api\/exercises","methods":["GET","HEAD"],"domain":null},"exercises.get":{"uri":"api\/exercises\/get","methods":["GET","HEAD"],"domain":null},"exercises.show":{"uri":"api\/exercises\/{exercise}","methods":["GET","HEAD"],"domain":null},"exercises.store":{"uri":"api\/exercises","methods":["POST"],"domain":null},"exercises.update":{"uri":"api\/exercises\/{exercise}","methods":["PUT"],"domain":null},"exercises.destroy":{"uri":"api\/exercises\/{exercise}","methods":["DELETE"],"domain":null},"activities.index":{"uri":"api\/activities","methods":["GET","HEAD"],"domain":null},"activities.show":{"uri":"api\/activities\/{activity}","methods":["GET","HEAD"],"domain":null},"activities.store":{"uri":"api\/activities","methods":["POST"],"domain":null},"activities.toggle-status":{"uri":"api\/activities\/toggle-status\/{activity}","methods":["POST"],"domain":null},"activities.update":{"uri":"api\/activities\/{activity}","methods":["PUT"],"domain":null},"activities.destroy":{"uri":"api\/activities\/{activity}","methods":["DELETE"],"domain":null},"telegram-bot.hook":{"uri":"api\/telegram\/hook","methods":["POST"],"domain":null},"generated::HRYzUWiiycK1QVWb":{"uri":"{any}","methods":["GET","HEAD"],"domain":null}},
        baseUrl: 'https://reminder.local/',
        baseProtocol: 'https',
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
