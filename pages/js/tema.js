(function () {
    var CHAVE = 'consultor-tema';
    var html  = document.documentElement;

    function lua() {
        return '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>';
    }

    function sol() {
        return '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>';
    }

    function atualizarBotao() {
        var btn  = document.getElementById('toggle-tema');
        var tema = html.getAttribute('data-theme') || 'light';
        if (!btn) return;
        btn.innerHTML = tema === 'dark' ? sol() : lua();
        btn.title     = tema === 'dark' ? 'Modo claro' : 'Modo escuro';
    }

    document.addEventListener('DOMContentLoaded', function () {
        atualizarBotao();

        var btn = document.getElementById('toggle-tema');
        if (!btn) return;

        btn.addEventListener('click', function () {
            var atual = html.getAttribute('data-theme') || 'light';
            var novo  = atual === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', novo);
            localStorage.setItem(CHAVE, novo);
            atualizarBotao();
        });
    });
}());
