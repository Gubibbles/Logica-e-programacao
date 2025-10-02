let alunos = [];

function cadastrarAluno() {
    const nome = document.getElementById('nome').value;
    const nota = document.getElementById('nota').value;
    
    if (nome === '' || nota === '') {
        alert('Preencha nome e nota!');
        return;
    }
    
    alunos.push({
        nome: nome,
        nota: Number(nota)
    });
    
    document.getElementById('nome').value = '';
    document.getElementById('nota').value = '';
    
    mostrarTodos();
}

function mostrarTodos() {
    const lista = document.getElementById('listaAlunos');
    lista.innerHTML = '';
    
    alunos.forEach(aluno => {
        const div = document.createElement('div');
        div.className = aluno.nota >= 7 ? 'aluno aprovado' : 'aluno reprovado';
        div.textContent = `${aluno.nome} - Nota: ${aluno.nota}`;
        lista.appendChild(div);
    });
}

function mostrarAprovados() {
    const lista = document.getElementById('listaAlunos');
    lista.innerHTML = '';
    
    const aprovados = alunos.filter(aluno => aluno.nota >= 7);
    
    aprovados.forEach(aluno => {
        const div = document.createElement('div');
        div.className = 'aluno aprovado';
        div.textContent = `${aluno.nome} - Nota: ${aluno.nota}`;
        lista.appendChild(div);
    });
}
