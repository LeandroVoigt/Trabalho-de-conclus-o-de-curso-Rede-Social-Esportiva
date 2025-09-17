document.querySelector('.botao-adicionar-liga').addEventListener('click', function() {
    document.querySelector('.tabela-ligas').style.display = 'block';
});

document.querySelector('#finalizar-liga').addEventListener('click', function() {
    alert('Liga adicionada com sucesso!');
});