let listaTimes = ["Santos", "São Paulo", "Palmeiras", "Corinthians"]

//Acessar um item do array
console.log(listaTimes[0]);
console.log(listaTimes[1]);
console.log(listaTimes[2]);
console.log(listaTimes[3]);

//Adicionar itens em um array
listaTimes.push("Ponte Preta")
listaTimes.push("Flamengo")
console.log(listaTimes);

//Verificar o tamanho da lista
console.log(listaTimes.length);

//Adicionar um item na primeira posição
listaTimes.unshift("Fluminense");
listaTimes.unshift("Vasco");
console.log(listaTimes);

//Remover o primeiro elemento do array
listaTimes.shift();
console.log(listaTimes);

//Remover o último elemento do array
listaTimes.pop();
console.log(listaTimes);

//Encontrar a posição de um elemento
console.log(listaTimes.indexOf("Santos"));



let listaNomes = ["Marcos", "Diego", "Camila", "Matheus"]

//Removendo itens
listaNomes.splice(3,1); //Matheus foi removido
console.log(listaNomes);

//Alterando itens
listaNomes.splice(1,2, "Robson", "Edvan");
console.log(listaNomes);

//Adicionando itens
listaNomes.splice(2,0,"Gabriel", "Diogo");
console.log(listaNomes);