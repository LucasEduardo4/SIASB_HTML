var xhr = new XMLHttpRequest();
// console.log("verificando sessao...");
var currentPath = window.location.pathname;
if (currentPath == 'blank') {
    basePath = '/siasb_html/flowSite/verificaSessao.php'
} else {
    var pathArray = currentPath.split('/');
    var basePath = '/' + pathArray[1] + '/flowSite/verificaSessao.php'
}

xhr.open("POST", basePath, true);
xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
        // console.log("Verificando sessao, retorno -> "+xhr.responseText);
        if (xhr.responseText == "false") {
            window.location.href = `/${pathArray[1]}/Login.html?error=true`;
        }
    }
};
xhr.send("verify=" + encodeURIComponent(1));



