var xhr = new XMLHttpRequest();

var currentPath = window.location.pathname;
var pathArray = currentPath.split('/');

var basePath = '/'+pathArray[1]+ '/flowSite/verificaSessao.php'

// pathArray.push('flowSite', 'verificaSessao.php');
// var basePath = pathArray.join('/') + '/';

console.log(basePath);
xhr.open("POST", basePath, true);
// xhr.open("POST", "../flowSite/verificaSessao.php", true)
xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhr.onreadystatechange = function(){
    if (xhr.readyState === 4) {
        if (xhr.status === 200) {
            // tableSetor.innerHTML += xhr.responseText;
            if(xhr.responseText == "false"){
                window.location.href=`/${pathArray[1]}/Login.html?error=true`;
            }
        }
    }
};
xhr.send("verify=" + encodeURIComponent(1));
