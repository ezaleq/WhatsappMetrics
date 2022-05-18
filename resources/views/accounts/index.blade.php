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
    <script type="text/javascript">
        $(function () {
            loadGrid();
        });

        function loadGrid() {
            $("#grid").kendoGrid({
                dataSource: {
                    transport: {
                        read: {
                            url: "/api/accounts",
                            type: "GET",
                        },
                        destroy: {
                            url: "/api/accounts",
                            type: "DELETE",
                        },
                        parameterMap: function (data, type) {
                            if (type !== "read") {
                                data._token = "{{csrf_token()}}";
                            }
                            return data;
                        }
                    },
                    schema: {
                        model: {
                            id: "id",
                            fields: {
                                username: {type: "string"},
                            }
                        }
                    }
                },
                columns: [
                    {field: "username", title: "Nombre de usuario"},
                    {command: ["destroy"]}
                ],
                editable: "inline",
                toolbar: [{name: "Create", text: "Crear", iconClass: "k-icon k-i-plus-outline"}],
                sortable: true,
                filterable: true
            });

            $(".k-grid-Create").on("click", (e) => {
                e.preventDefault();
                window.location = "/accounts/create"
            })
        }
    </script>
@endsection
