// ------------------------------------------------- DATAS ---------------------------------------------------------- \\
function YYYYMMDDtoDDMMYYYY(date, separator) {
    if (date == null) {
        return "";
    }
    if (separator == null) {
        separator = " ";
    }

    const [datePart, timePart] = date.split(" ");
    const [year, month, day] = datePart.split("-");
    const [hour, minute, second] = timePart.split(":");

    return day + separator + month + separator + year + " às " + hour + ":" + minute + ":" + second;
}

function DDMMYYYYtoYYYYMMDD(date, separator) {
    if (date == null) {
        return "";
    }
    if (separator == null) {
        separator = "-";
    }
    return date.split("-").reverse().join(separator);
}
function DateToYYYYMMDD(date) {
    const year = date.getFullYear();
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Adiciona 1 ao mês, pois janeiro é 0

    return `${year}-${month}-${day}`;
}
function DatetoDDMMYYY(date) {
    const year = date.getFullYear();
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Adiciona 1 ao mês, pois janeiro é 0

    return `${day}/${month}/${year}`;
}

// ------------------------------------------------------------------------------------------------------------------ \\

// ------------------------------------------------- FORMATAÇÃO DE NÚMEROS ------------------------------------------ \\

function convertePonto(valor) {
    var valorOriginal = valor;
    
    if (typeof valor !== 'string') {
        valor = valor.toString();
    }

    if (valor.indexOf('.') === -1 && valor.indexOf(',') === -1) {
        console.log("transformei " + valorOriginal + " em " + valor + ',00');
        return valor + ',00';
    }
    // Verifica se o número tem um dígito após o ponto decimal
    if (valor.indexOf('.') !== -1) {
        const partes = valor.split('.');
        if (partes[1].length === 1) {
            // Adiciona um zero para representar os centavos
            partes[1] += '0';
            console.log("transformei " + valorOriginal + " em " + partes.join(','));
            return partes.join(',');
        }else if(partes[1].length === 2){
            console.log("transformei " + valorOriginal + " em " + valor.replace('.', ','));
            return valor.replace('.', ',');
        }
    }

    // Verifica se o número tem vírgula em vez de ponto e corrige
    if (valor.includes(',')) {
        console.log("transformei " + valorOriginal + " em " + valor.replace(',', '.'));
        return valor.replace(',', '.');
    }

    // Se nenhum ajuste for necessário, retorna o valor original
    console.log("transformei " + valorOriginal + " em " + valor);
    return valor;
}