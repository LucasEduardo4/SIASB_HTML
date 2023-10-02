var xhr = new XMLHttpRequest();
var currentPath = window.location.pathname;
if (currentPath == 'blank') {
    basePath = '/siasb_html/flowSite/verificaPermissao.php'
} else {
    var pathArray = currentPath.split('/');
    var basePath = '/' + pathArray[1] + '/flowSite/verificaPermissao.php'
}
xhr.open("POST", basePath, true);
xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
        console.log(xhr.responseText);
        if (xhr.responseText == "sem permissao") {
            window.location.href = `/${pathArray[1]}/flowsite/permissaonegada.html`;
        } else
            if (xhr.responseText == 'desabilitado') {
                window.location.href = `/${pathArray[1]}/flowsite/usuarioinativo.html`;
            }

    }
}
xhr.send("verificaPermissao=" + encodeURIComponent(1));

