<script>
    $(document).ready(function() {
        $('#btn-buscar-cep').on('click', function() {
            // Limpo o CEP novamente para melhor segurança, redundancia.
            const cep = $('#cep').val().replace(/[^0-9]/g, '');
            const btn = $(this);
            const errorDiv = $('#cep-error');

            if (cep.length !== 8) {
                errorDiv.text('CEP deve ter 8 dígitos.').show();
                return;
            }

            // Retorno visual para melhorar usabilidade do usuário.
            btn.prop('disabled', true).text('Buscando...');
            errorDiv.hide();

            // Chamada AJAX para nossa API (que chama o ViaCEP).
            $.ajax({
                url: `/api/cep/${cep}`, // Rota da API
                type: 'GET',
                success: function(data) {
                    $('#logradouro').val(data.logradouro);
                    $('#bairro').val(data.bairro);
                    $('#cidade').val(data.cidade);
                    $('#uf').val(data.uf);

                    // Foca no campo "número" após o sucesso
                    $('#numero').focus();
                },
                error: function(xhr) {
                    // Erro vem do nosso CepController (422)
                    let errorMsg = 'Falha ao buscar CEP.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    errorDiv.text(errorMsg).show();
                },
                complete: function() {
                    // Restaura o botão
                    btn.prop('disabled', false).text('Buscar CEP');
                }
            });
        });
    });
</script>