$(document).ready(function() {
    var Body = $("body");
    Body.addClass("preloader-site");
});

$(window).on("load", function() {
    $(".preloader-wrapper").fadeOut();
    $("body").removeClass("preloader-site");
});

$(document).ready(function() {
    $("#quota_date_ini")
        .datepicker({
            dateFormat: "yy-mm-dd",
            onSelect: function(dateText) {
                $(this).change();
            }
        })
        .on("change", function() {
            $("#quota_date_end").val("");
            $("#days").val("");
            //alert("asdas");
            var fechaini = new Date(String($("#quota_date_ini").val()));
            var fechafin = new Date(String($("#quota_date_end").val()));
            var year = fechafin.getFullYear();
            //console.log(year);
            if (fechaini != "" && fechafin != "") {
                var diasdif = fechafin.getTime() - fechaini.getTime();
                var contdias = Math.round(diasdif / (1000 * 60 * 60 * 24));
                if (year > 2000) {
                    if (contdias < 0) {
                        Swal.fire(
                            "Alerta",
                            "La fecha de inicio no puede ser mayor a la de fin",
                            "warning"
                        );
                        $("#quota_date_ini").val("");
                        $("#quota_date_end").val("");
                        $("#days").val("");
                    } else {
                        $("#days").val(contdias);
                        vueF.getDays();
                    }
                }
            }
        });

    $("#quota_date_end")
        .datepicker({
            dateFormat: "yy-mm-dd",
            minDate: 0,
            onSelect: function(dateText) {
                $(this).change();
            }
        })
        .on("change", function() {
            //alert("asdas");
            var fechaini = new Date(String($("#quota_date_ini").val()));
            var fechafin = new Date(String($("#quota_date_end").val()));
            var year = fechafin.getFullYear();
            //console.log(year);
            if (fechaini != "" && fechafin != "") {
                var diasdif = fechafin.getTime() - fechaini.getTime();
                var contdias = Math.round(diasdif / (1000 * 60 * 60 * 24));
                //console.log(contdias);
                $("#days").val(contdias);
                vueF.getDays();
                if (year > 2000) {
                    if (contdias < 0) {
                        $("#quota_date_end").val("");
                        $("#days").val("");
                    }
                }
            }

        });

    // datepicker Products

    $("#product_date_ini1")
        .datepicker({
            dateFormat: "yy-mm-dd",
            minDate: 0,
            onSelect: function(dateText) {
                $(this).change();
            }
        })
        .on("change", function() {
            //alert("asdas");
            var fechaini = new Date(String($("#product_date_ini1").val()));
            var fechafin = new Date(String($("#product_date_end1").val()));
            console.log(fechafin);
            if (fechaini != "" && fechafin != "") {
                var diasdif = fechafin.getTime() - fechaini.getTime();
                var contdias = Math.round(diasdif / (1000 * 60 * 60 * 24));
                console.log(contdias);
                if (contdias < 0) {
                    Swal.fire(
                        "Alerta",
                        "Por favor verifique los datos del producto que desea agregar",
                        "warning"
                    );
                    $("#product_date_ini1").val("");
                    $("#product_date_end1").val("");
                }
            }
        });

    $("#product_date_end1")
        .datepicker({
            dateFormat: "yy-mm-dd",
            minDate: 0,
            onSelect: function(dateText) {
                $(this).change();
            }
        })
        .on("change", function() {
            //alert("asdas");
            var fechaini = new Date(String($("#product_date_ini1").val()));
            var fechafin = new Date(String($("#product_date_end1").val()));
            console.log(fechafin);
            if (fechaini != "" && fechafin != "") {
                var diasdif = fechafin.getTime() - fechaini.getTime();
                var contdias = Math.round(diasdif / (1000 * 60 * 60 * 24));
                if (contdias < 0) {
                    Swal.fire(
                        "Alerta",
                        "Por favor verifique las fechas de vigencia del producto",
                        "warning"
                    );
                    $("#product_date_ini1").val("");
                    $("#product_date_end1").val("");
                }
            }
        });

    $("input").tooltip();
    $("#extension_time").datepicker({
        dateFormat: "yy-mm-dd",
        minDate: 0,
        value: ""
    });
});

$(function() {
    $("#datatable_full").DataTable({
        language: {
            url: "{{ asset('lang/es/datatable.es.lang') }}"
        }
    });
    $("#datatable_filter").DataTable({
        paging: true,
        lengthChange: false,
        searching: false,
        ordering: true,
        info: true,
        autoWidth: false,
        language: {
            url: " ../lang/es/datatable.es.lang"
        }
    });
});
