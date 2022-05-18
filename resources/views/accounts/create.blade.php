@extends("layout")
@section("title", "Cuentas - Crear")

@section("content")
    <div class="card">
        <div class="card-body text-center">
            <h3 class="card-title">
                <i class="fa fa-mobile"></i>
                Agendar nuevo teléfono
            </h3>
            <div id="loading">
                <span>Cargando QR...</span>
                <div class="spinner-border spinner-border-sm" role="status">
                </div>
            </div>
            <div class="text-center" id="loaded" style="display: none">
                <canvas height="264" height="264" id="qr"></canvas>
                <p>Por favor escanee el QR con Whatsapp</p>
            </div>
            <div class="qr-body">
            </div>
        </div>
    </div>

    <script>

        $(function(){
            drawQR();
        });
        function drawQR() {
            $.ajax({
                type: "GET",
                url: "/api/accounts/qr",
                success: (data) => {
                    $("div#loading").hide();
                    $("div#loaded").show();
                    const qrElement = $("canvas#qr").get(0);
                    const ctx = qrElement.getContext("2d");
                    const image = new Image();
                    image.onload = () => {
                        ctx.drawImage(image, 0, 0);
                    }
                    image.src= "data:image/png;base64" + data.image;
                    checkIfSign(data);
                },
                error: (data) => {
                    console.log(data);
                    kendo.alert(data.xhr);
                }
            })
        }

        async function checkIfSign(data) {
            while (true) {
                try {
                    await $.ajax({
                        type: "GET",
                        data: {"sessionId": data.sessionId, "foldername": data.foldername },
                        url: "/api/accounts/isLogged"
                    });
                    window.location = "/accounts";
                    break;
                }
                catch {
                    await sleep(5000);
                }

            }
        }

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
    </script>
@endsection
