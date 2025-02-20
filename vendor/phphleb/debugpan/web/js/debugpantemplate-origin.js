if (typeof hlDPan === 'undefined') hlDPan = {};
if (typeof hlDPan.template === 'undefined') hlDPan.template = {
    default: 'info',
    icon: null,
    autoloadStat: 0,
    databaseStat: 0,
    contentStat: 0,
    debugStat: 0,
    sendAjax: false,
    register: function () {
        document.querySelector('[src$="/js/debugpantemplate"]').outerHTML = '';
    },
    createMenu: function () {
        var menu = document.createElement('div');
        menu.id = 'hl-DEBUGPAN-menu';
        menu.innerHTML = this.menuContent();
        menu.classList.add('notranslate');
        document.body.appendChild(menu);
        document.getElementById('hl-DEBUGPAN-menu-close').onclick = function () {
            document.getElementById('hl-DEBUGPAN-menu').style.display = 'none';
        };
        this.setCount();
        this.createAction('info');
        this.createAction('route');
        this.createAction('autoload');
        this.createAction('content');
        this.createAction('database');
        this.createAction('terminal');
        this.createAction('debug');
        this.openMenu(this.default, false);
        this.sendAjaxRequest('/state/get', [], 'GET', 'text/plain', 'openPanel');
        hlDPan.terminal.setActions();
        return menu;
    },
    menuContent: function () {
        this.icon = '<img src="/' + hlDPan.script.scriptData.tag + '/debugpan/' + hlDPan.script.scriptData.version + '/svg/info" width="16" height="16">';
        return '<div id="hl-DEBUGPAN-menu-over-content">' +
            '<div id="hl-DEBUGPAN-menu-close" title="Close">X</div>' +
            '<div id="hl-DEBUGPAN-menu-title">Debug panel for Hleb v2</div>' +
            this.getButton('INFO', 'info') +
            this.getButton('ROUTE', 'route') +
            this.getButton('AUTOLOAD', 'autoload') +
            this.getButton('CONTENT', 'content') +
            this.getButton('DATABASE', 'database') +
            this.getButton('TERMINAL', 'terminal') +
            this.getButton('DEBUG', 'debug') +
            '<br>' +
            this.createPanelInfo() +
            this.createPanelRoute() +
            this.createPanelAutoload() +
            this.createPanelContent() +
            this.createPanelDatabase() +
            this.createPanelTerminal() +
            this.createPanelDebug() +
            '</div>';
    },
    setCount: function () {
        this.updateButton('autoload', this.autoloadStat);
        this.updateButton('content', this.contentStat);
        this.updateButton('database', this.databaseStat);
        this.updateButton('debug', this.debugStat);
    },
    updateButton: function (num, count) {
        document.getElementById('hl-DEBUGPAN-menu-btn-' + num).innerHTML += ' (' + count + ')';
    },
    getButton: function (name, num) {
        return "<span id='hl-DEBUGPAN-menu-btn-" + num + "' class='hl-debugpan-menu-btn'>" + name + "</span>"
    },
    createAction: function (num) {
        var th = this;
        document.getElementById('hl-DEBUGPAN-menu-btn-' + num).onclick = function () {
            document.querySelectorAll('.hl-debugpan-menu-panel').forEach(
                function (b) {
                    th.hideBlock([b]);
                }
            );
            th.openMenu(num, true);
        };
    },
    hideBlock: function (els) {
        els.forEach(
            function (b) {
                b.classList.add('hl-debugpan-display-none');
                b.classList.remove('hl-debugpan-display-block');
            }
        );
    },
    showBlock: function (els) {
        els.forEach(
            function (b) {
                b.classList.add('hl-debugpan-display-block');
                b.classList.remove('hl-debugpan-display-none');
            }
        );
    },
    openMenu: function (num, save) {
        var th = this;
        document.querySelectorAll('.hl-debugpan-menu-panel').forEach(
            function (b) {
                th.hideBlock([b]);
            }
        );
        this.showBlock([document.getElementById('hl-DEBUGPAN-menu-panel-' + num)]);
        this.selectBtn(num);
        if (save) {
            this.sendAjaxRequest('/state/set?name=' + num, [], 'GET', 'text/plain');
        }
    },
    selectBtn: function (num) {
        document.querySelectorAll('.hl-debugpan-menu-btn').forEach(
            function (b) {
                b.classList.remove('hl-debugpan-selected');
            }
        );
        document.getElementById('hl-DEBUGPAN-menu-btn-' + num).classList.add('hl-debugpan-selected');
    },
    createPanelInfo: function () {
        var data = hlDPan.script.scriptData;
        var info = this.getInfo('Section with general information about the request.') +
            '<br>HTTP status code: <b>' + data.system.code.status + '</b><br>';
        info += '<br><b>Speed (sec):</b><br>';
        info += '<span class="hl-debugpan-nowrap">Load framework: ' + data.system.load.core + '</span><br>';
        var time = data.system.load.core;
        for (var m in data.system.load.middleware) {
            var item = data.system.load.middleware[m];
            var sec = item.sec;
            var rtm = (sec - time).toFixed(5);
            info += '<span class="hl-debugpan-nowrap">' + item.name + ': ' + sec + ' (+' + (rtm != 0 ? rtm : '0') + ')</span><br>';
            time = sec;
        }
        var end = (data.system.time - time).toFixed(5);
        info += '<span class="hl-debugpan-nowrap">Project completion : ' + data.system.time + ' (+' + (end != 0 ? end : '0') + ')</span><br>';
        info += '<br><b>Memory:</b><br>';
        info += 'Spent: ' + data.system.memory + ' MB<br>';
        info += 'Real: ' + data.system.storage + ' MB<br>';
        info += '<br><b>Log:</b><br>';
        info += '<span class="hl-debugpan-nowrap">Status: ' + (data.system.logs.enabled ? 'enabled' : 'disabled') + '</span><br>';
        info += '<span class="hl-debugpan-nowrap">Max level: ' + data.system.logs.level + '</span><br>';
        info += '<br><b>PHP:</b><br>';
        info += '<span class="hl-debugpan-nowrap">Version: ' + data.system.php.version + '</span><br>';
        info += '<span class="hl-debugpan-nowrap">OPcache: ' + (data.system.php.opcache ? 'enabled' : 'disabled') + '</span><br>';
        info += '<span class="hl-debugpan-nowrap">JIT: ' + (data.system.php.jit ? 'enabled' : 'disabled') + '</span><br>';
        if (data.system.async) {
            info += '<br><b>Async mode:</b> on<br>';
        }
        info += '<br><span class="hl-debugpan-info">Request ' +
            'ID: <span class="hl-debugpan-nowrap"> ' + hlDPan.script.scriptData.request.id + '</span><br>';
        return "<span id='hl-DEBUGPAN-menu-panel-info' class='hl-debugpan-menu-panel'>" + info + "</span></span>";
    },
    createPanelRoute: function () {
        var route = this.getInfo('Display details of the current route.');
        var data = hlDPan.script.scriptData.route;
        if (data) {
            route += '<br>Name: ' + (data.name ? data.name : '-');
            route += '<br>Address: ' + data.address;
            route += '<br>Route method: ' + data.method;
            if (data.controller) {
                route += '<br>Controller: ' + this.convert((data.controller.type !== 'controller' ? '(' + data.controller.type + ')' : '') + ' ' + data.controller.class);
                route += '<br>Controller method: ' + this.convert(data.controller.method);
                route += '<br>Initiator: ' + data.controller.initiator;
            }
            route += '<br><br>Cache update time: ' + data.time;
        } else {
            route += '<br>No data found for the current route type.<br>';
        }

        return "<span id='hl-DEBUGPAN-menu-panel-route' class='hl-debugpan-menu-panel'>" + route + "</span>";
    },
    convert: function (str) {
        return str.replace(/"/g, '&quot;')
            .replace(/>/g, '&gt;')
            .replace(/</g, '&lt;');
    },
    createPanelAutoload: function () {
        var rows = this.getInfo('Information about class loading by autoloaders. The labels indicate the type of autoloader.') +
            '<br>Load framework classes +<br><br>';
        var a = hlDPan.script.scriptData.autoload.previous;
        var t = hlDPan.script.scriptData.autoload.process;
        for (var r in a) {
            rows += this.getLoadedClassInfo(r, a[r], 'async');
            this.autoloadStat++;
        }
        for (var e in t) {
            if (typeof a[e] === 'undefined') {
                rows += this.getLoadedClassInfo(e, t[e]);
            }
            this.autoloadStat++;
        }
        return "<span id='hl-DEBUGPAN-menu-panel-autoload' class='hl-debugpan-menu-panel'>" + rows + "</span>";
    },
    createPanelContent: function () {
        var content = this.getInfo('Information about using framework templates.');
        var template = hlDPan.script.scriptData.template;
        if (template && template.length) {
            for (var c in template) {
                var t = template[c];
                content += '<br>' + (parseInt(c) + 1) + '. ' + t['path'] + ' [' + t['name'] + '] Load: ' + t['ms'].toFixed(3) + ' ms';
            }
            this.contentStat = template.length;
        } else {
            content += '<br>There are no content templates.';
        }

        return "<span id='hl-DEBUGPAN-menu-panel-content' class='hl-debugpan-menu-panel'>" + content + "</span>";
    },
    createPanelDatabase: function () {
        var dbPrefix = this.getInfo('List of queries to the database.');
        var db = '';
        this.databaseStat = 0;
        var sumTime = 0;
        var data = hlDPan.script.scriptData.database;
        if (data && data.length) {
            for (var c in data) {
                this.databaseStat++;
                var q = data[c];
                sumTime += Number(q.time);
                var cl= q.stat === 'prepare' ? 'hl-debugpan-db-prepare' : 'hl-debugpan-db-content';
                db += '<br><div>' + this.databaseStat + '. ' + (!q.previously ? q.time + ' sec' : '-') + ' [' + q.dbname + '] ';
                db += q.type + ' ' + q.stat + ' <br><span class="' + cl + '">' + this.convertChar(q.sql) + '</span></div>';
            }
            if (sumTime) {
                db = '<br><div>Total: ' + sumTime.toFixed(5) + ' sec.</div>' + db;
            }
        }
        else {
            db += '<br>No database queries found.';
        }
        return "<span id='hl-DEBUGPAN-menu-panel-database' class='hl-debugpan-menu-panel'>" + dbPrefix + db + "</span>";
    },
    createPanelTerminal: function () {
        var terminal = this.getInfo('Using console commands of the framework.');
        terminal += hlDPan.terminal.getPanel();
        return "<span id='hl-DEBUGPAN-menu-panel-terminal' class='hl-debugpan-menu-panel'>" + terminal + "</span>";
    },
    createPanelDebug: function () {
        var debug = this.getInfo('Custom debug data.');
        this.debugStat = 0;
        var data = hlDPan.script.scriptData.debug;
        if (data && data.length) {
            for (var block in data) {
                for (var c in data[block]) {
                    var t = data[block][c];
                    debug += (c != 0 ? '<br><b>' + c + ':</b>' : '') + '<br><pre>' + t + '</pre><br>';
                    this.debugStat++;
                }
            }
        } else {
            debug += '<br>Debugging data is missing. Use the print_r2() function to print debug output.';
        }
        return "<span id='hl-DEBUGPAN-menu-panel-debug' class='hl-debugpan-menu-panel'>" + debug + "</span>";
    },
    getInfo: function (text) {
        return '<span class="hl-debugpan-info">' + this.icon + ' ' + text + '</span><br>';
    },
    convertChar: function (str) {
        str = str.replace(/&/g, "&amp;");
        str = str.replace(/>/g, "&gt;");
        str = str.replace(/</g, "&lt;");
        str = str.replace(/"/g, "&quot;");
        str = str.replace(/'/g, "&#039;");
        return str;
    },
    getLoadedClassInfo: function (name, status, type) {
        var tag = '<span title="Loaded by another autoloader" class="hl-debugpan-default-tag">&diams;</span>';
        if (status) {
            tag = '<span title="Loaded by framework autoloader" class="hl-debugpan-tag">&raquo;</span>';
        }
        if (typeof type !== 'undefined' && type === 'async') {
            name = '<span class="hl-debugpan-info">' + name + '</span>';
        } else if (name.startsWith('Hleb\\') || name.startsWith('Phphleb\\')) {
            name = '<span class="hl-debugpan-default-content">' + name + '</span>';
        }
        return '<i class="hl-debugpan-nowrap">' + tag + '&emsp;' + name + '</i><br>';
    },
    createAjaxRequest: function () {
        if (typeof XMLHttpRequest === "undefined") {
            xhr = function () {
                try {
                    return new window.ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                }
            };
        } else {
            var xhr = new XMLHttpRequest();
        }
        return xhr;
    },
    sendAjaxRequest: function (url, params, methodType, contentType, action) {
        if (!this.sendAjax) {
            this.sendAjax = true;
            var th = this;
            var xhr = th.createAjaxRequest();
            if (xhr) {
                var port = window.location.port !== '' ? ':' + window.location.port : '';
                var debugPath = '/' + hlDPan.script.scriptData.tag + '/debugpan/controller' + url;
                xhr.open(methodType, window.location.protocol + "//" + window.location.hostname + port + debugPath, true);
                xhr.setRequestHeader("Content-Type", contentType);
                xhr.onreadystatechange = function () {
                    if (this.readyState != 4) return;
                    th.sendAjax = false;
                    if (this.status == 200) {
                        if (action === 'openPanel') {
                            if (this.response) {
                                var data = JSON.parse(this.response);
                                if (data && data.content && data.content.name) {
                                    th.openMenu(data.content.name, false);
                                }
                            }
                        } else if (action === 'terminalCommand') {
                            if (this.response) {
                                var data = JSON.parse(this.response);
                                if (data && data.content && data.content) {
                                    hlDPan.terminal.afterRequest(data.content.data);
                                }
                            }
                        }
                    } else {
                        console.log('Data not send. Status: ' + this.status);
                    }
                }
                xhr.upload.onerror = function () {
                    var error = '[' + xhr.status + '] No Internet/API connection';
                    if (action === 'terminalCommand') {
                        hlDPan.terminal.afterRequest(error);
                    }
                    console.error(error);
                };
                xhr.send(params);
            }
        }
    },
}
hlDPan.template.register();
// console.log(hlDPan.script.scriptData);
