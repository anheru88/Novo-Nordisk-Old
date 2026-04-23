new Vue({
    el: "#app",
    created: function() {
       // alert("asd")
    },
    data: {
        dateOK: false,
        idProduct: "",
        nameProduct: "",

        // Scales data
        idScale: "",
        idChannel: "",
        scales: [],
        scalesModal: [],
        scalesModalEdit: [],
        scaleName: "",
        scaleNameOld: "",

        // Measure Units
        prodUnits: [], // Array to receive data from Measure Units
        prodUnitText: "", // Product measure Unit Name
        prodUnitID: "", // Product measure Unit ID

        porcentaje: 0,
        piso: 0,
        techo: "MÁS",
        prevTecho: "",
        noScalesMsg: true,
        showScales: false,
        statusModal: 0,
        // Answer var
        answer: ""
    },
    methods: {
        // text Toact message
        mensaje: function() {
            toastr.options = {
                closeButton: false,
                progressBar: true,
                newestOnTop: false
            };
            toastr.success("Escala creada exitosamente").css("width", "auto");
        },

        // Get the scale when the user select a product
        getScales: function() {
            var urlProduct      = "../getScales";
            this.idProduct      = event.target.value;
            this.nameProduct    = event.target.options[event.target.options.selectedIndex].text;
            // console.log(this.idProduct);
            if (this.idProduct != "") {
                this.showScales = true;
                axios
                    .post(urlProduct, {
                        idProduct: this.idProduct
                    })
                    .then(response => {
                        this.scales = response.data;
                        this.prodUnitText = this.scales[0].prod_unit_text;
                        this.prodUnitID = this.scales[0].prod_unit_id;
                        $('#id_channel').val(this.scales[0].id_channel)
                        if (this.scales.length > 0) {
                            this.noScalesMsg = false;
                        }
                        this.errors = [];
                    })
                    .catch(function(error) {
                        this.test = error;
                    });
            }
        },

        // Open the scale modal to create a new scale a get the Measure Unit from product
        addScale: function() {
            this.scaleName = "";
            this.porcentaje = 0;
            this.piso = 0;
            this.scalesModal = [];
            var urlProduct = "../getProductUnit";
            axios
                .post(urlProduct, {
                    idProduct: this.idProduct
                })
                .then(response => {
                    $('#id_channelN option:first-child').attr("selected", "selected");
                    this.prodUnits = response.data;
                    //console.log(this.prodUnits);
                    this.errors = [];
                })
                .catch(function(error) {
                    this.test = error;
                });
        },

        // Save lebel temp in the scale creation or edition
        addNivel: function() {
            var size = this.scalesModal.length;

            var percent = false;
            var floor = false;
            var ceil = false;

            this.prevTecho = this.piso;

            if (this.porcentaje <= 0) {
                toastr
                    .warning("El porcentaje debe ser mayor a 0")
                    .css("width", "auto");
            } else {
                if (size > 0) {
                    if (
                        this.porcentaje <= this.scalesModal[size - 1].porcentaje
                    ) {
                        toastr
                            .warning(
                                "El porcentaje debe ser mayor a del nivel anterior"
                            )
                            .css("width", "auto");
                        this.porcentaje = 0;
                    } else {
                        percent = true;
                    }

                    if (
                        parseInt(this.piso - 1) <=
                        parseInt(this.scalesModal[size - 1].piso)
                    ) {
                        toastr
                            .warning(
                                "El piso debe ser mayor al piso y al techo del nivel anterior"
                            )
                            .css("width", "auto");
                        this.piso = 0;
                    } else {
                        floor = true;
                    }

                    if (percent == true && floor == true /*&& ceil == true*/) {
                        this.scalesModal.splice(size - 1, 1, {
                            porcentaje: this.scalesModal[size - 1].porcentaje,
                            piso: this.scalesModal[size - 1].piso,
                            techo: this.piso - 1,
                            units: this.prodUnitID
                        });

                        this.scalesModal.push({
                            porcentaje: this.porcentaje,
                            piso: this.piso,
                            techo: this.techo,
                            units: this.prodUnitID
                        });
                    }
                } else {
                    this.scalesModal.push({
                        porcentaje: this.porcentaje,
                        piso: this.piso,
                        techo: this.techo,
                        units: this.prodUnitID
                    });
                }
            }
        },

        // Delete level from scale in the modal
        removeProduct: function(rnum) {
            Swal.fire({
                title: "Confirmación",
                text: "¿Esta seguro que desea eliminar este niveñ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar",
                cancelButtonText: "No, cancelar"
            }).then(result => {
                if (result.value) {
                    this.scalesModal.splice(rnum, 1);
                }
            });
        },

        // Call the scale data to edit.
        editScales: function(idScale) {
            this.statusModal = 1;
            var urlProduct = "../editScales";
            this.idScale = idScale;
            //console.log(this.idScale);
            if (this.idScale != "") {
                axios
                    .post(urlProduct, {
                        idProduct: this.idScale
                    })
                    .then(response => {
                        this.scalesModalEdit = response.data;
                        //console.log(this.scalesModalEdit.id_channel);
                        this.scaleName = this.scalesModalEdit.scale_number;
                        this.scaleNameOld = this.scalesModalEdit.scale_number;
                        $('#id_channel').val(this.scalesModalEdit.id_channel)
                        this.scalesModal = [];
                        for (
                            let index = 0;
                            index < this.scalesModalEdit.scalelvl.length;
                            index++
                        ) {
                            this.scalesModal.push({
                                porcentaje: this.scalesModalEdit.scalelvl[index]
                                    .scale_discount,
                                piso: this.scalesModalEdit.scalelvl[index]
                                    .scale_min,
                                techo: this.scalesModalEdit.scalelvl[index]
                                    .scale_max,
                                units: this.prodUnitID
                            });
                        }
                       // console.log(response.data);
                        var urlProduct = "../getProductUnit";
                        axios
                            .post(urlProduct, {
                                idProduct: this.idProduct
                            })
                            .then(response => {
                                this.prodUnits = response.data;
                                this.prodUnitText = this.prodUnits["unit_name"];
                                this.prodUnitID = this.prodUnits["id_unit"];

                                //console.log(this.prodUnits);
                                this.errors = [];
                            })
                            .catch(function(error) {
                                this.test = error;
                            });
                        this.errors = [];
                    })
                    .catch(function(error) {
                        this.test = error;
                    });
            }
        },

        // Store the scale -  Valid to edit a save
        saveScales: function() {
            this.idChannel = $('#id_channelN option:selected').val()
            if(this.idChannel != ""){
                var urlProduct = "../saveScales";
                if (this.scaleName != "") {
                    axios.post(urlProduct, {
                            idScale: this.idScale,
                            idProduct: this.idProduct,
                            scaleName: this.scaleName,
                            scales: this.scalesModal,
                            idChannel: this.idChannel
                        })
                        .then(response => {
                            this.scales = response.data;
                            console.log(this.scales);
                            if (this.scales == "error") {
                                toastr
                                    .warning(
                                        "Exisio un problema al guardar la escala"
                                    )
                                    .css("width", "auto");
                            }else if(this.scales == "exist"){
                                toastr
                                .warning(
                                    "Ya existe una escala con este canal asignado"
                                )
                                .css("width", "auto");
                            } else {
                                toastr
                                    .success("El nivel se guardo exitosamente")
                                    .css("width", "auto");
                                this.scalesModal = [];
                                this.scaleName = "";
                                this.porcentaje = 0;
                                this.piso = 0;
                                if (this.statusModal == 0) {
                                    //$("#modal-escala").modal("toggle");
                                    this.statusModal = 0;
                                } else {

                                    this.statusModal = 0;
                                }
                            }
                            this.errors = [];
                        })
                        .catch(function(error) {
                            this.test = error;
                        });
                } else {
                    toastr
                        .warning("La escala debe tener un nombre")
                        .css("width", "auto");
                }
            }else{
                toastr.warning("Debe seleccionar un canal para la escala").css("width", "auto");
            }
        },

        // Store the scale -  Valid to edit a save
        updateScales: function() {
            this.idChannel = $('#id_channel option:selected').val()
            if(this.idChannel != ""){
                var urlProduct = "../updateModalScales";
                if (this.scaleName != "") {
                    axios.post(urlProduct, {
                            idScale: this.idScale,
                            idProduct: this.idProduct,
                            scaleName: this.scaleName,
                            scales: this.scalesModal,
                            idChannel: this.idChannel
                        })
                        .then(response => {
                            this.scales = response.data;
                            console.log(this.scales);
                            if (this.scales == "error") {
                                toastr
                                    .warning(
                                        "Exisio un problema al guardar los niveles"
                                    )
                                    .css("width", "auto");
                            } else {
                                toastr
                                    .success("El nivel se guardo existosamente")
                                    .css("width", "auto");
                                this.scalesModal = [];
                                this.scaleName = "";
                                this.porcentaje = 0;
                                this.piso = 0;
                                if (this.statusModal == 0) {
                                    this.statusModal = 0;
                                } else {
                                    this.statusModal = 0;
                                }
                            }
                            this.errors = [];
                        })
                        .catch(function(error) {
                            this.test = error;
                        });
                } else {
                    toastr
                        .warning("La escala debe tener un nombre")
                        .css("width", "auto");
                }
            }else{
                toastr.warning("Debe seleccionar un canal para la escala").css("width", "auto");
            }
        },

        // Delete a Scale by id
        removeScale: function(idScale) {
            //console.log(this.idProduct);
            Swal.fire({
                title: "Confirmación",
                text: "¿Esta seguro que desea eliminar esta escala?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar",
                cancelButtonText: "No, cancelar"
            }).then(result => {
                var urlProduct = "../destroyScales";
                axios
                    .post(urlProduct, {
                        idScale: idScale,
                        idProduct: this.idProduct
                    })
                    .then(response => {
                        this.scales = response.data;
                        if(this.scales == "error") {
                            toastr
                            .warning("Exisio un problema al eliminar los niveles")
                            .css("width", "auto");
                        }else if(this.scales[0] == "exist"){
                            this.scales = response.data[1]
                            toastr
                            .warning("La escala esta asignada a una negociación, no puede ser eliminada")
                            .css("width", "auto");
                        } else {
                            toastr
                            .success("La escala se elimino existosamente")
                            .css("width", "auto");
                        }
                        this.errors = [];
                    })
                    .catch(function(error) {
                        this.test = error;
                    });
            });
        },

        // Add level row in creation
        addRow: function() {
            var row = $(".empty-row.screen-reader-text").clone(true);
            row.removeClass("empty-row screen-reader-text");
            row.insertBefore("#repeatable-fieldset-one tbody>tr:last");
            return false;
        },

        // Remove level row in creation
        removeRow: function() {
            var row = $(".empty-row.screen-reader-text").clone(true);
            row.removeClass("empty-row screen-reader-text");
            row.insertBefore("#repeatable-fieldset-one tbody>tr:last");
            return false;
        }
    }
});
