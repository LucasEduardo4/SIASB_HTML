<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    /* CSS para a modal */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      z-index: 1000;
    }

    .modal-content {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100%;
      position: relative;
    }

    .modal-img {
      max-width: 90%;
      max-height: 90%;
      border: 2px solid white;
    }

    /* CSS para as setas */
    .modal-prev, .modal-next {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 30px;
      height: 30px;
      background-color: rgba(255, 255, 255, 0.7);
      border: 1px solid #ccc;
      text-align: center;
      cursor: pointer;
      z-index: 1001;
    }

    .modal-prev {
      left: 10px;
    }

    .modal-next {
      right: 10px;
    }
  </style>
</head>
<body>
  <div id="anexosContainer"></div>

  <script>
    // Função para exibir imagens com modal
    function exibeImagens(imagens) {
      const containerAnexos = document.getElementById("anexosContainer");

      imagens.forEach((imagemBase64) => {
        const imagemElement = document.createElement('img');
        imagemElement.className = 'imagensAnexo';
        imagemElement.src = `data:image/jpeg;base64, ${imagemBase64}`;
        imagemElement.alt = 'Imagem do chamado';

        // Adicionar evento de clique para abrir em um popup
        imagemElement.addEventListener('click', () => {
          abrirImagemPopup(imagemBase64, imagens);
        });

        const divRow = document.createElement('div');
        divRow.className = 'row';
        const divCol = document.createElement('div');
        divCol.className = 'col-md-4';

        divCol.appendChild(imagemElement);
        divRow.appendChild(divCol);
        containerAnexos.appendChild(divRow);
      });
    }

    // Função para abrir imagem em um popup com setas de navegação
    function abrirImagemPopup(imagemBase64, imagens) {
      // Crie a modal
      const modal = document.createElement('div');
      modal.className = 'modal';

      // Crie o conteúdo da modal
      const modalContent = document.createElement('div');
      modalContent.className = 'modal-content';

      // Crie uma seta para a esquerda
      const prevArrow = document.createElement('div');
      prevArrow.className = 'modal-prev';
      prevArrow.innerHTML = '<';
      modalContent.appendChild(prevArrow);

      // Crie uma seta para a direita
      const nextArrow = document.createElement('div');
      nextArrow.className = 'modal-next';
      nextArrow.innerHTML = '>';
      modalContent.appendChild(nextArrow);

      // Crie a imagem dentro da modal
      const imagemElement = document.createElement('img');
      imagemElement.className = 'modal-img';
      imagemElement.src = `data:image/jpeg;base64, ${imagemBase64}`;
      imagemElement.alt = 'Imagem do chamado';

      // Adicione a imagem ao conteúdo da modal
      modalContent.appendChild(imagemElement);

      // Adicione o conteúdo da modal à modal
      modal.appendChild(modalContent);

      // Exiba a modal
      modal.style.display = 'block';

      // Lógica para alternar entre as imagens
      let currentIndex = imagens.indexOf(imagemBase64);

      // Evento de clique para a seta da esquerda
      prevArrow.addEventListener('click', () => {
        if (currentIndex > 0) {
          currentIndex--;
          imagemElement.src = `data:image/jpeg;base64, ${imagens[currentIndex]}`;
        }
      });

      // Evento de clique para a seta da direita
      nextArrow.addEventListener('click', () => {
        if (currentIndex < imagens.length - 1) {
          currentIndex++;
          imagemElement.src = `data:image/jpeg;base64, ${imagens[currentIndex]}`;
        }
      });

      // Evento de clique para fechar a modal quando clicar em qualquer lugar fora da imagem
      modal.addEventListener('click', () => {
        modal.style.display = 'none';
        modal.remove();
      });

      // Adicione a modal ao corpo do documento
      document.body.appendChild(modal);
    }

    // Exemplo de uso
    const imagensExemplo = ['base64-imagem-1', 'base64-imagem-2', 'base64-imagem-3'];
    exibeImagens(imagensExemplo);
  </script>
</body>
</html>
