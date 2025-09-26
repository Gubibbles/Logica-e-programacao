//Criando um objeto
let produto = {
    nome: "Computador",
    marca: "Positivo",
    preco: 2000,
    processador: "I3-2100"
}

//Acessando um item dentro do objeto
console.log(produto.nome);
//Ou (quando a chave é dinâmica ou tem caracteres especiais)
console.log(produto["nome"]);

//Adicionar um item no objeto
produto.armazenamento = "246 gb"
//Ou
produto["memoria ram"] = "8 gb"

//Remover um elemento
delete produto.armazenamento
delete produto["memoria ram"]
console.log(produto);
