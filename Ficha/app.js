$(document).ready(function() {
    $(".botao-cadastrar").on("click", function(){
        localStorage.setItem("exercicios", JSON.stringify([]));
    });

    $(".botao-cadastro-ficha-lista-exercicios").on("click", function(){
        let exercicio_id = $("#formulario-cadastrar-ficha select[name='exercicio_id']").val();
        let num_series = $("#formulario-cadastrar-ficha input[name='num_series']").val();
        let num_repeticoes = $("#formulario-cadastrar-ficha input[name='num_repeticoes']").val();
        let tempo_descanso = $("#formulario-cadastrar-ficha input[name='tempo_descanso']").val();

        if(!exercicio_id){ alert("Informe o exercício"); return; }
        if(!num_series){ alert("Informe o Nº de série"); return; }
        if(!num_repeticoes){ alert("Informe o Nº de repetição"); return; }
        if(!tempo_descanso){ alert("Informe o tempo de descanso"); return; }

        let exercicio_nome = $("#formulario-cadastrar-ficha select[name='exercicio_id']").find(":selected").attr("data-nome");

        let obj = {
            exercicio_id,
            exercicio_nome,
            num_series,
            num_repeticoes,
            tempo_descanso
        };

        let exercicios = JSON.parse(localStorage.getItem("exercicios") || "[]");
        exercicios.push(obj);
        localStorage.setItem("exercicios", JSON.stringify(exercicios));

        renderExerciciosTabela(exercicios, "#cadastro-ficha-lista-exercicios");
    });

    $("#formulario-cadastrar-ficha").on("submit", function(e){
        e.preventDefault();
        let exercicios = JSON.parse(localStorage.getItem("exercicios") || "[]");
        let data = $(this).serialize() + "&" + $.param({exercicios});

        $.ajax({
            url: "cadastrar.php",
            method: "POST",
            data,
            success: () => location.reload()
        });
    });

    let ultimoExcluido = null;

    function renderExerciciosTabela(exercicios, tabelaId) {
        let html = "";

        for (let exercicio of exercicios) {
            html += `<tr>`;
            html += `<td>${exercicio.exercicio_nome}</td>`;
            html += `<td>${exercicio.num_series}</td>`;
            html += `<td>${exercicio.num_repeticoes}</td>`;
            html += `<td>${exercicio.tempo_descanso}</td>`;
            html += `<td><button type="button" class="btn btn-danger btn-sm excluir-exercicio" data-id="${exercicio.id ?? exercicio.exercicio_id}">Excluir</button></td>`;
            html += `</tr>`;
        }

        $(tabelaId).html(html);
    }

    $(".botao-editar-ficha-lista-exercicios").on("click", function(){
        let exercicio_id = $("#formulario-editar-ficha select[name='exercicio_id']").val();
        let num_series = $("#formulario-editar-ficha input[name='num_series']").val();
        let num_repeticoes = $("#formulario-editar-ficha input[name='num_repeticoes']").val();
        let tempo_descanso = $("#formulario-editar-ficha input[name='tempo_descanso']").val();

        if(!exercicio_id){ alert("Informe o exercício"); return; }
        if(!num_series){ alert("Informe o Nº de série"); return; }
        if(!num_repeticoes){ alert("Informe o Nº de repetição"); return; }
        if(!tempo_descanso){ alert("Informe o tempo de descanso"); return; }

        let exercicio_nome = $("#formulario-editar-ficha select[name='exercicio_id']").find(":selected").attr("data-nome");

        let obj = {
            exercicio_id,
            exercicio_nome,
            num_series,
            num_repeticoes,
            tempo_descanso
        };

        let exercicios = JSON.parse(localStorage.getItem("exercicios") || "[]");
        exercicios.push(obj);
        localStorage.setItem("exercicios", JSON.stringify(exercicios));

        renderExerciciosTabela(exercicios, "#editar-ficha-lista-exercicios");
    });

    $(".botao-selecionar-ficha").on("click", function () {
        let { id, nome, dia_treino } = $(this).data();

        $("#editar").modal("show");
        localStorage.setItem("exercicios", JSON.stringify([]));

        $("#formulario-editar-ficha input[name='id']").val(id);
        $("#formulario-editar-ficha input[name='nome']").val(nome);
        $("#formulario-editar-ficha select[name='dia_treino']").val(dia_treino);

        $.ajax({
            url: "exercicios.php",
            type: "POST",
            data: { id },
            dataType: "json",
            success: response => {
                let { exercicios } = response;

                localStorage.setItem("exercicios", JSON.stringify(exercicios));
                renderExerciciosTabela(exercicios, "#editar-ficha-lista-exercicios");
            }
        });
    });

    $(document).on("click", ".excluir-exercicio", function () {
        let id = $(this).data("id");
        let exercicios = JSON.parse(localStorage.getItem("exercicios") || "[]");

        let index = exercicios.findIndex(e => (e.id ?? e.exercicio_id) == id);
        if (index !== -1) {
            ultimoExcluido = exercicios[index];
            exercicios.splice(index, 1);
        }

        if (exercicios.length === 0) {
            if (confirm("Você deseja excluir toda a ficha?")) {
                let fichaId = $("#formulario-editar-ficha input[name='id']").val();
                $.ajax({
                    url: "excluirFicha.php",
                    type: "POST",
                    data: { id: fichaId },
                    success: () => location.reload()
                });
            } else {
                exercicios.push(ultimoExcluido); // restaura o último
            }
        }

        localStorage.setItem("exercicios", JSON.stringify(exercicios));
        renderExerciciosTabela(exercicios, "#editar-ficha-lista-exercicios");
    });

    $("#formulario-editar-ficha").on("submit", function(e){
        e.preventDefault();
        let exercicios = JSON.parse(localStorage.getItem("exercicios") || "[]");
        let data = $(this).serialize() + "&" + $.param({exercicios});

        $.ajax({
            url: "editar.php",
            method: "POST",
            data,
            success: () => location.reload()
        });
    });
});

$(document).on("click", ".gerar-pdf-aluno", function () {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  let y = 10;

  const alunoSelecionado = $(this).data("aluno");

  doc.setFont("helvetica", "bold");
  doc.setFontSize(16);
  doc.text(`Ficha de Treino - ${alunoSelecionado}`, 10, y);
  y += 12;

  const tableRows = document.querySelectorAll("table tbody tr");

  tableRows.forEach((row) => {
    const aluno = row.children[0]?.innerText.trim() || "";

    if (aluno !== alunoSelecionado) return; // Pula se não for o aluno desejado

    const dia = row.children[1]?.innerText.trim() || "";
    const listaExercicios = row.children[2]?.querySelectorAll("li") || [];

    const startY = y;

    doc.setFontSize(12);
    doc.setFont("helvetica", "bold");
    doc.text(`Aluno: ${aluno}`, 15, y); y += 7;
    doc.text(`Dia de Treino: ${dia}`, 15, y); y += 7;
    doc.text(`Exercícios`, 15, y); y += 6;

    doc.setFont("helvetica", "normal");
    listaExercicios.forEach((li) => {
      const textoCompleto = li.innerText.trim();
      
      // Extrair nome do exercício e detalhes (séries, repetições, descanso)
      const match = textoCompleto.match(/^(.+?)\s*\(Séries:\s*(\d+),\s*Repetições:\s*(\d+),\s*Descanso:\s*(\d+)\)$/);
      
      if (match) {
        const [, nomeExercicio, series, repeticoes, descanso] = match;
        doc.text(`- ${nomeExercicio}`, 20, y);
        y += 5;
        doc.text(`  Séries: ${series} | Repetições: ${repeticoes} | Descanso: ${descanso}`, 25, y);
        y += 7;
      } else {
        // Fallback caso o formato não seja reconhecido
        doc.text(`- ${textoCompleto}`, 20, y);
        y += 6;
      }
    });

    const endY = y + 2;
    doc.setDrawColor(0);
    doc.setLineWidth(0.3);
    doc.rect(10, startY - 10, 190, endY - startY + 15, "S");
    y = endY + 10;
  });

  const date = new Date();
      const formattedDate = date.toLocaleString("pt-BR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    });

      const pageCount = doc.internal.getNumberOfPages();
      for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        doc.setFontSize(10);
        doc.text(formattedDate, 10, doc.internal.pageSize.height - 10);
        const pageText = `Página ${i} de ${pageCount}`;
        const pageTextWidth = doc.getTextWidth(pageText);
        doc.text(
          pageText,
          doc.internal.pageSize.width - pageTextWidth - 10,
          doc.internal.pageSize.height - 10
        );
      }

  doc.save(`ficha_${alunoSelecionado}.pdf`);
});



$('#cadastrar').on('shown.bs.modal', function () {
    localStorage.setItem("exercicios", JSON.stringify([]));
    $("#formulario-cadastrar-ficha")[0].reset();
    $("#cadastro-ficha-lista-exercicios").empty();
});

