function calcularPagamento() {
    let valorGasto = parseFloat(document.getElementById("valorCompra").value)
    let formaPagamento = document.getElementById("formaPagamento").value
    let resultado = document.querySelector("#resultado")
    let valorFinal
    
    switch (formaPagamento) {
        case "pix":
            valorFinal = valorGasto * 0.9
            resultado.innerHTML = "O valor a ser pago é de " + valorFinal.toFixed(2)
            break

        case "debito":
            valorFinal = valorGasto * 0.95
            resultado.innerHTML = "O valor a ser pago é de " + valorFinal.toFixed(2)
            break
        
        case "credito":
            resultado.innerHTML = "O valor a ser pago é de " + valorFinal.toFixed(2)

        default:
            resultado.innerHTML = "Valor inválido."
    }
}