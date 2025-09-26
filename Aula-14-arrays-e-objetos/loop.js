//for i in range (1, 10, 1);
//print(i)

for (let i = 0; i <= 10; 1++) { // (let (início); (fim); (incremento))
    console.log(i);
}

//Percorrer array for
let listaProdutos = ["Computador", "Notebook", "Teclado", "Mouse", "Fone"]
for (let i = 0; i < listaProdutos.length; i++) {
    console.log(listaProdutos[i]);
}

//Pecorrer array - forEach
listaProdutos.forEach((produto) => {
    console.log(produto);
})