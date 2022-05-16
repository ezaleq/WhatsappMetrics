@extends("layout")
@section("title", "Cuentas - Crear")

@section("content")
    <div class="card text-center">
        <div class="card-body">
            <h3 class="card-title">
                <i class="bi bi-phone"></i>
                Agendar nuevo teléfono
            </h3>
            <div id="loading">
                <span>Cargando QR...</span>
                <div class="spinner-border spinner-border-sm" role="status">
                </div>
            </div>
            <canvas id="qr"></canvas>
            <div class="qr-body">
            </div>
        </div>
    </div>

    <script>
        $(function(){
            $.ajax({
                type: "GET",
                url: "/api/accounts/qr",
                success: (imageData) => {
                    $("div#loading").hide();
                    const qrElement = $("canvas#qr");
                    const ctx = qrElement.getContext("2d");
                    const image = new Image();
                    image.onload = () => {
                        ctx.drawImage(image, 0, 0);
                    }
                    image.src= imageData;
                }
            })
        });
    </script>
@endsection
