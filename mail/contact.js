$(function () {
    $("#contactForm input, #contactForm textarea").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function ($form, event, errors) {
            // Manejo de errores
        },
        submitSuccess: function ($form, event) {
            event.preventDefault(); // Evita el envío estándar del formulario
            var name = $("input#name").val();
            var email = $("input#email").val();
            var subject = $("input#subject").val();
            var message = $("textarea#message").val();

            var $this = $("#sendMessageButton");
            $this.prop("disabled", true);

            // Realiza la llamada AJAX al servidor PHP
            $.ajax({
                url: "mail/contact.php", // Ruta al archivo PHP
                type: "POST",
                dataType: "json", // Esperamos respuesta en formato JSON
                data: {
                    name: name,
                    email: email,
                    subject: subject,
                    message: message,
                },
                success: function (response) {
                    if (response.status === "success") {
                        $('#success').html("<div class='alert alert-success'>");
                        $('#success > .alert-success')
                            .html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>")
                            .append("<strong>" + response.message + "</strong>")
                            .append('</div>');
                        $('#contactForm').trigger("reset");
                    } else {
                        $('#success').html("<div class='alert alert-danger'>");
                        $('#success > .alert-danger')
                            .html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>")
                            .append("<strong>" + response.message + "</strong>")
                            .append('</div>');
                    }
                },
                error: function () {
                    $('#success').html("<div class='alert alert-danger'>");
                    $('#success > .alert-danger')
                        .html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>")
                        .append("<strong>Hubo un error al intentar enviar el mensaje. Por favor intenta nuevamente.</strong>")
                        .append('</div>');
                },
                complete: function () {
                    $this.prop("disabled", false);
                    console.log('aqui lelgo....');
                },
            });
        },
        filter: function () {
            return $(this).is(":visible");
        },
    });

    $("a[data-toggle=\"tab\"]").click(function (e) {
        e.preventDefault();
        $(this).tab("show");
    });
});

$('#name').focus(function () {
    $('#success').html('');
});
