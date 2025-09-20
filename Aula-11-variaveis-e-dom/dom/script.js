document.body.style.backgroundColor = "lightgrey"

let paragrafo1 = document.getElementsByTagName("p")[0]
paragrafo1.innerHTML = "Usando o TAG NAME"
paragrafo1.style.backgroundColor = "red"

let paragrafo2 = document.getElementById("paragrafo2")
paragrafo2.innerHTML = "Usando o ID"
paragrafo2.style.backgroundColor = "grey"

let paragrafo3 = document.getElementsByClassName("paragrafo3")[0]
paragrafo3.innerHTML = "Usando a CLASSE"
paragrafo3.style.backgroundColor = "aqua"

let paragrafo4 = document.querySelector("#paragrafo4")
paragrafo4.innerHTML = "Usando o QUERY SELECTOR"
paragrafo4.style.backgroundColor = "pink"