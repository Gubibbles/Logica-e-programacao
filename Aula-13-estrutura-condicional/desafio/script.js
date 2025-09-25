let numero1 = window.document.getElementById("num1")
let numero2 = window.document.getElementById("num2")
let operacao = window.document.getElementById("operacao")
let resultado = window.document.getElementById("resultado")

function calcular() {
    let num1 = parseFloat(numero1.value);
    let num2 = parseFloat(numero2.value);
    let op = operacao.value;

    if (isNaN(num1) || isNaN(num2)) {
        resultado.innerHTML = "Digite números válidos.";
        return;
    }

    let resultadoCalculo;
    
    if (op === "+") {
        resultadoCalculo = num1 + num2;
    } else if (op === "-") {
        resultadoCalculo = num1 - num2;
    } else if (op === "*") {
        resultadoCalculo = num1 * num2;
    } else if (op === "/") {
        if (num2 === 0) {
            resultado.innerHTML = "Não é possível realizar uma divisão por zero(0)."
            return;
        }
        resultadoCalculo = num1 / num2;
    } else {
        resultado.innerHTML = "Operação inválida.";
        return;
    }
    resultado.innerHTML = `Resultado: ${resultadoCalculo}`;
}