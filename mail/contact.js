$(function () {
    $("form[name='sentMessage']").submit(function (event) {
        event.preventDefault(); // Evitar el envío normal del formulario

        var form = $(this);
        form.addClass('was-validated');

        var formData = new FormData(this); // Crear objeto FormData con los datos del formulario

        if (form[0].checkValidity() === true) {
            $.ajax({
                url: "mail/contact.php",
                type: "POST",
                data: formData, // Enviar FormData en lugar de los datos planos
                contentType: false, // Importante: no configurar contentType a 'application/x-www-form-urlencoded'
                processData: false, // Importante: no procesar los datos
                cache: false,
                success: function (response) {
                    if (response.trim() === 'success') {
                        $('#message-container').html("<div class='alert alert-success'>");
                        $('#message-container > .alert-success').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                            .append("</button>");
                        $('#message-container > .alert-success')
                            .append("<strong>Your message has been sent. </strong>");
                        $('#message-container > .alert-success')
                            .append('</div>');
                        form.trigger("reset");
                    } else {
                        $('#message-container').html("<div class='alert alert-danger'>");
                        $('#message-container > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                            .append("</button>");
                        $('#message-container > .alert-danger').append($("<strong>").text("Sorry " + name + ", it seems that our mail server is not responding. Please try again later!"));
                        $('#message-container > .alert-danger').append('</div>');
                    }
                },
                error: function () {
                    $('#message-container').html("<div class='alert alert-danger'>");
                    $('#message-container > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                        .append("</button>");
                    $('#message-container > .alert-danger').append($("<strong>").text("Sorry " + name + ", it seems that our mail server is not responding. Please try again later!"));
                    $('#message-container > .alert-danger').append('</div>');
                }
            });
        }
    });

    $("a[data-toggle='tab']").click(function (e) {
        e.preventDefault();
        $(this).tab("show");
    });
});
