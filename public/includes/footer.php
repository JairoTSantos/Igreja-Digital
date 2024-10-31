<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $(".alert").fadeOut("slow");
        }, 2000);

        $('button[name="btn_salvar"]').on('click', function(event) {
            const confirmacao = confirm("Tem certeza que inserir?");
            if (!confirmacao) {
                event.preventDefault();
            }
        });

        $('button[name="btn_apagar"]').on('click', function(event) {
            const confirmacao = confirm("Tem certeza que deseja apagar?");
            if (!confirmacao) {
                event.preventDefault();
            }
        });

        $('button[name="btn_atualizar"]').on('click', function(event) {
            const confirmacao = confirm("Tem certeza que deseja atualizar?");
            if (!confirmacao) {
                event.preventDefault();
            }
        });
    });
</script>