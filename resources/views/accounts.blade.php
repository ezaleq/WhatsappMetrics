@extends("layout")
@section("title", "Cuentas")

@section("content")
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Lista de cuentas asociadas</h5>
        <p class="card-text">
            Detalle de todas las cuentas asociadas
        <div id="grid"></div>
        </p>
    </div>
</div>

<style>
    .k-grid {
        min-height: 300px;
    }
</style>

<script>
    $(function() {
        $("#grid").kendoGrid({
            dataSource: {
                schema: {
                    model: {
                        id: "id",
                        fields: {
                            name: { type: "string" },
                            phone: { type: "string" },
                            account_type: { type: "string" },
                        }
                    }
                }
            },
            columns: [
                { field: "name", title: "Nombre"},
                { field: "phone", title: "Teléfono" },
                { field: "account_type", title: "Tipo de cuenta" }
            ],
            toolbar: [{name: "Create", text: "Crear", iconClass: "k-icon k-i-plus-outline"}, {name:"edit"}],
            sortable: true,
            filterable: true
        });

        $(".k-grid-Create").on("click", (e) => {
            e.preventDefault();
            $.ajax({
                url: "/accounts",
                data: {_token: "{{ csrf_token() }}"},
                type: "POST",
            });
        })
    });
</script>
@endsection
