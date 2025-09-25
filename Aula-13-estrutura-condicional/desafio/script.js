let numero1 = window.document.getElementById("num1")
let numero2 = window.document.getElementById("num2")
let operacao = window.document.getElementById("operacao")
let resultado = window.document.getElementById("resultado")

function somar() {
    resultado.innerHTML = parseFloat(numero1.value) + parseFloat(numero2.value)
}

function subtrair() {
    resultado.innerHTML = parseFloat(numero1.value) - parseFloat(numero2.value)
}

function multiplicar() {
    resultado.innerHTML = parseFloat(numero1.value) * parseFloat(numero2.value)
}

function dividir() {
    resultado.innerHTML = parseFloat(numero1.value) / parseFloat(numero2.value)
}