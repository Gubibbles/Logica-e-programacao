//Array com objetos dentro e com itens dentro desses objetos
let listaProdutos = [
    {nome: "Computador", fabricante: "Dell", valor: 5000},
    {nome: "Notebook", fabricante: "Acer", valor: 3000},
    {nome: "Monitor", fabricante: "LG", valor: 900},
    {nome: "Teclado", fabricante: "Redragon", valor: 200},
    {nome: "Mouse", fabricante: "Logitech", valor: 200}
]

//Acessando os itens pela posição dentro do objeto
console.log(listaProdutos[0]);
console.log(listaProdutos[1]);

//Percorrendo a lista
listaProdutos.forEach((produto) => {
    console.log(produto);
})

//Percorrendo a lista como o format do python (print(f"abcde {asdsf} asdasd.))
listaProdutos.forEach((produto) => {
    console.log(`O ${produto.nome} da ${produto.fabricante} custa R$${produto.valor}`);
})

//Filtrando produtos (nesse caso, acima de 1000 reais)
let listaProdutosCaros = listaProdutos.filter(produto => produto.valor > 1000)
console.log(listaProdutosCaros);