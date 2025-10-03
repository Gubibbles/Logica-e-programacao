// https://viacep.com.br/ws/COLOCAR CEP AQUI/json

const cep = document.getElementById("cep")

cep.addEventListener("change" , (evento) => {
    let cepUsuario = evento.target
    buscaCEP(cepUsuario.value);
})

async function buscaCEP(cepUsuario){

    let erroCep = document.getElementById("erro")
    erroCep.innerHTML = ""

    try {
        let consultaCEP = await fetch(`https://viacep.com.br/ws/${cepUsuario}/json`)

        let consultaCEPJSON = await consultaCEP.json()
        
        if (consultaCEPJSON.erro){
            throw Error ("CEP inexistente.")
        }

        preencheCampos(consultaCEPJSON)

    }
    catch {
        erroCep.innerHTML = "CEP inválido. Digite um CEP válido."
        apagarCampos()
    }
}

// Capturando os elementos para que preencha automaticamente nos labels
function preencheCampos(cepJson){
    let rua = document.getElementById("rua")
    let cidade = document.getElementById("cidade")
    let estado = document.getElementById("estado")
    let bairro = document.getElementById("bairro")

    rua.value = cepJson.logradouro
    bairro.value = cepJson.bairro
    cidade.value = cepJson.localidade
    estado.value = cepJson.uf
}

// Apagando elementos dos labels depois de digitar um cep inválido
function apagarCampos(){
    let rua = document.getElementById("rua")
    let cidade = document.getElementById("cidade")
    let estado = document.getElementById("estado")
    let bairro = document.getElementById("bairro")

    rua.value = ""
    bairro.value = ""
    cidade.value = ""
    estado.value = ""
}