
  document.addEventListener("DOMContentLoaded", function () {
    const passwordIcons = document.querySelectorAll('.password-icon');
    const inputCpfCnpj = document.getElementById("last_name");
    const labelCpfCnpj = document.querySelector('label[for="last_name"]');
    const atletaRadio = document.getElementById("atleta");
    const clubeRadio = document.getElementById("clube");

    // Alternar exibição da senha
    passwordIcons.forEach(icon => {
      icon.addEventListener('click', function () {
        const input = this.parentElement.querySelector('.form-control');
        input.type = input.type === 'password' ? 'text' : 'password';
        this.classList.toggle('fa-eye');
      });
    });

    // Função para aplicar e limitar a máscara
    function applyMaskAndLimit(value, type) {
      let numbers = value.replace(/\D/g, '');
      if (type === "CPF") {
        numbers = numbers.slice(0, 11);
        return numbers
          .replace(/(\d{3})(\d)/, "$1.$2")
          .replace(/(\d{3})(\d)/, "$1.$2")
          .replace(/(\d{3})(\d{1,2})$/, "$1-$2");
      } else {
        numbers = numbers.slice(0, 14);
        return numbers
          .replace(/^(\d{2})(\d)/, "$1.$2")
          .replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3")
          .replace(/\.(\d{3})(\d)/, ".$1/$2")
          .replace(/(\d{4})(\d{0,2})$/, "$1-$2");
      }
    }

    function atualizarDocumento(tipo) {
      if (tipo === "atleta") {
        labelCpfCnpj.textContent = "CPF";
        inputCpfCnpj.placeholder = "999.999.999-99";
        inputCpfCnpj.maxLength = 14; // com pontuação
      } else {
        labelCpfCnpj.textContent = "CNPJ";
        inputCpfCnpj.placeholder = "00.000.000/0001-00";
        inputCpfCnpj.maxLength = 18; // com pontuação
      }
      inputCpfCnpj.value = applyMaskAndLimit(inputCpfCnpj.value, tipo === "clube" ? "CNPJ" : "CPF");
    }

    // Atualiza a máscara a cada digitação
    inputCpfCnpj.addEventListener("input", () => {
      const tipoSelecionado = document.querySelector('input[name="gender"]:checked').value;
      inputCpfCnpj.value = applyMaskAndLimit(inputCpfCnpj.value, tipoSelecionado === "clube" ? "CNPJ" : "CPF");
    });

    // Quando troca entre Atleta e Clube
    [atletaRadio, clubeRadio].forEach(radio => {
      radio.addEventListener("change", function () {
        atualizarDocumento(this.value);
      });
    });

    // Executa no carregamento da página
    atualizarDocumento(document.querySelector('input[name="gender"]:checked').value);
  });




/*document.addEventListener("DOMContentLoaded", function () {
      const passwordIcons = document.querySelectorAll('.password-icon');
      const inputCpfCnpj = document.getElementById("last_name");
      const labelCpfCnpj = document.querySelector('label[for="last_name"]');
      const atletaRadio = document.getElementById("atleta");
      const clubeRadio = document.getElementById("clube");

      // Alternar exibição da senha
      passwordIcons.forEach(icon => {
        icon.addEventListener('click', function () {
          const input = this.parentElement.querySelector('.form-control');
          input.type = input.type === 'password' ? 'text' : 'password';
          this.classList.toggle('fa-eye');
        });
      });

      // Máscara dinâmica CPF/CNPJ
      function applyMask(value, type) {
        value = value.replace(/\D/g, '');
        if (type === "CPF") {
          value = value.slice(0, 11);
          value = value.replace(/(\d{3})(\d)/, "$1.$2");
          value = value.replace(/(\d{3})(\d)/, "$1.$2");
          value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
        } else {
          value = value.slice(0, 14);
          value = value.replace(/^(\d{2})(\d)/, "$1.$2");
          value = value.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
          value = value.replace(/\.(\d{3})(\d)/, ".$1/$2");
          value = value.replace(/(\d{4})(\d{0,2})$/, "$1-$2");
        }
        return value;
      }

      function atualizarDocumento(tipo) {
        if (tipo === "atleta") {
          labelCpfCnpj.textContent = "CPF";
          inputCpfCnpj.placeholder = "999.999.999-99";
        } else {
          labelCpfCnpj.textContent = "CNPJ";
          inputCpfCnpj.placeholder = "00.000.000/0001-00";
        }
        inputCpfCnpj.value = applyMask(inputCpfCnpj.value, tipo === "clube" ? "CNPJ" : "CPF");
      }

      inputCpfCnpj.addEventListener("input", () => {
        const tipoSelecionado = document.querySelector('input[name="gender"]:checked').value;
        inputCpfCnpj.value = applyMask(inputCpfCnpj.value, tipoSelecionado === "clube" ? "CNPJ" : "CPF");
      });

      [atletaRadio, clubeRadio].forEach(radio => {
        radio.addEventListener("change", function () {
          atualizarDocumento(this.value);
        });
      });

      atualizarDocumento(document.querySelector('input[name="gender"]:checked').value);
    });*/