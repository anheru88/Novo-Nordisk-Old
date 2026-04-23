new Vue({
    el: "#quotation",
    created: function() {
        this.quotaEdit();
    },
    data: {
        quotaID: "",
        listProducts: [],
        inputProducts: [],
        allProducts: [],
        products: [],
        productPrice: "",
        comDiscount: "",
        percentDiscount: 0,
        clientChannel: "",
        productid: "",
        client: "",
        levelAuth: "",
        quantity: "",
        timeDiscount: "",
        vTotal: 0,
        alert: "",
        totalQuota: 0,
        quantCheck: false,
        productPriceOld: "",
        productPriceUnits: "",
        /// Agregar Productos
        priceDiscount: 0,
        comDiscount: 0,
        payPercent: "",
        payMethod: "",
        levelAuth: "",
        levelAuthQuota: 0,
        levelDescIDQuota: 0,
        levelDescID: "",
        appNum: "",
        alert: 1,
        textDescription: "Descuento",
        hastaProd: "",
        // Autorizadores
        authorizer: false,
        usersList: [],
        userAuth: undefined,

        ///Carag de productos en Bloque
        listProducts: [],
        listSelectedProducts: [],
        allSelected: false,
        listAuthLevels: [] // Guarda los niveles de autorizacion de la cotización
    },
    methods: {
        ///////////////////////////////////////////////////////////////////////////
        /* Obtiene todos los productos de la cotizacion actual para imprimirlos en la lista de productos cotizados*/
        quotaEdit: function() {
            this.quotaID = document.getElementById("quotaID").value;
            this.clientChannel = document.getElementById(
                "id_client_channel"
            ).value;
            this.clientChannelID = document.getElementById(
                "id_client_channel_id"
            ).value;
            this.client = document.getElementById("idClient").value;
            this.totalQuota = document.getElementById("quota_val").value;
            this.levelAuthQuota = document.getElementById("level").value;
            //alert(this.levelAuthQuota);
            var urlUsers = "../../getEditProducts";
            axios
                .post(urlUsers, {
                    idQuota: this.quotaID
                })
                .then(response => {
                    this.products = response.data;
                    //console.log(this.products[0].product.prod_name);
                    for (index = 0; index < this.products.length; index++) {
                        this.listProducts.push(this.products[index].id_product);
                        this.listAuthLevels.push(
                            this.products[index].authlevel
                        );
                        this.inputProducts.push({
                            productId: this.products[index].id_product,
                            productname: this.products[index].product.prod_name,
                            quantity: this.products[index].quantity,
                            uMinima: this.products[index].price_uminima,
                            vComercial: this.products[index].prod_cost,
                            vTotal: this.products[index].totalValue,
                            dtoPrecio: this.products[index].pay_discount,
                            dtoComercial: this.products[index].pay_discount,
                            symbol: "",
                            fPago: this.products[index].payterm.payterm_name,
                            fPagoID: this.products[index].id_payterm,
                            productAuthLevel: this.products[index]
                                .id_prod_auth_level,
                            productLevel: this.products[index].authlevel,
                            isValid: this.products[index].is_valid
                        });
                    }
                    this.errors = [];
                    //console.log(this.listAuthLevels);
                })
                .catch(function(error) {
                    this.test = error;
                });
        },

        ///////////////////////////////////////////////////////////////////////////
        /* Obtiene los  productos de la cotizacion actual en el formulario */
        getPreviousProduct: function() {
            if (this.client != "") {
                var urlProduct = "../../getPreviousProduct";
                this.desdeProd = "";
                this.hastaProd = "";
                this.productid = event.target.value;
                this.productName =
                    event.target.options[
                        event.target.options.selectedIndex
                    ].text;
                this.quantity = 1;
                this.comDiscount = 0;
                this.priceDiscount = 0;
                $("#unidades").prop("checked", false);
                this.quantCheck = false;
                //this.comDiscount = "";
                //alert(this.client);
                axios
                    .post(urlProduct, {
                        idClient: this.client,
                        idProduct: this.productid,
                        channel: this.clientChannel
                    })
                    .then(response => {
                        this.resultArray = response.data;
                        console.log(response.data);
                        this.productPrice = this.resultArray.precio;
                        this.productPriceOld = this.resultArray.precio_old;
                        this.productPriceCom = this.resultArray.precio_com;
                        this.tempPrice = this.resultArray.precio;
                        this.productPriceUnits = this.resultArray.unidades;
                        this.comDiscount = this.resultArray.commercial_discount;
                        this.payMethodSelect = this.resultArray.id_formaPago;
                        this.payPercent = this.resultArray.pay_discount;
                        this.percentDiscount = this.resultArray.time_discount;
                        // alert(this.timeDiscount);
                        //$("#id_payterm").val(this.resultArray.id_payterm);
                        // $('#id_payterm option').eq(this.resultArray.id_payterm).prop('selected', true);
                        //this.payMethod = $("#id_payterm option:selected").text();
                        this.payMethod = $("#id_payterm_name").val();
                        this.timeDiscount = this.resultArray.time_discount;
                        this.levelDescID = this.resultArray.id_prod_auth_level;
                        this.levelAuth = "-";
                        this.desdeProd = this.resultArray.desde[0];
                        this.hastaProd = this.resultArray.hasta[0];
                        this.errors = [];
                        // console.log(this.comDiscount);
                        if (this.comDiscount != undefined) {
                            //this.calcProductQuota();
                        }
                    })
                    .catch(function(error) {
                        this.test = error;
                    });
            }
        },

        ///////////////////////////////////////////////////////////////////////////
        /* Calcula las condiciones del producto para ver sicumple las condiciones financieras de producto */
        calcProductQuota: function(insert) {
            var dateIni = $("#quota_date_ini").val();
            var dateEnd = $("#quota_date_end").val();
            this.dateIni = dateIni;
            this.dateEnd = dateEnd;
            //console.log(this.listProducts.length);
            if (
                this.productPrice != "" &&
                this.productPrice > 0 &&
                this.quantity > 0
            ) {
                if (this.quantity > 1 || this.listProducts.length >= 1) {
                    this.showTotalCot = true;
                } else {
                    this.showTotalCot = false;
                }
                if (this.client != "" && this.productPrice > 0) {
                    //alert("call");
                    var urlProduct = "../../calcProductQuota";
                    axios
                        .post(urlProduct, {
                            payPercent: this.payMethodSelect,
                            idProduct: this.productid,
                            channelID: this.clientChannelID,
                            channel: this.clientChannel,
                            comDisc: this.comDiscount,
                            quotaPrice: this.productPrice,
                            dateIni: this.dateIni,
                            dateEnd: this.dateEnd
                        })
                        .then(response => {
                            this.resultArray = response.data;
                            console.log(this.resultArray);
                            if (this.resultArray["permitido"] == 0) {
                                this.alert = 1;
                                //alert("Ha superado el maximo de descuento permitido, por favor verifique");
                                Swal.fire(
                                    "Alerta",
                                    this.resultArray.msg,
                                    "warning"
                                );
                                this.comDiscount = 0;
                                this.percentDiscount = this.resultArray[
                                    "percent"
                                ];
                                this.levelAuth = 0;
                                this.levelDescID = "";
                                this.priceDiscount = 0;
                                this.productPrice = this.tempPrice;
                            } else {
                                this.alert = 0;
                                this.priceDiscount = this.resultArray[
                                    "descPrecio"
                                ];
                                this.levelAuth = this.resultArray["level"];
                                this.percentDiscount = this.resultArray[
                                    "percent"
                                ];
                                this.textDescription = this.resultArray["text"];
                                this.descSymbol = this.resultArray[
                                    "descSymbol"
                                ];
                                //console.log(this.priceDiscount);
                                if (this.resultArray["level"] == 0) {
                                    this.levelAuth = 1;
                                    this.levelDescID = "";
                                } else {
                                    this.levelDescID = this.resultArray[
                                        "idLevel"
                                    ];
                                    this.levelAuth = this.resultArray["level"];
                                }
                                //console.log("level Auth " + this.levelAuth);
                                if (insert == true && this.alert == 0) {
                                    // alert(this.payMethodSelect);
                                    if (
                                        this.quantity != "" &&
                                        this.productPrice != "" &&
                                        this.productPrice > 0 &&
                                        this.quantity > 0 &&
                                        this.payMethodSelect != "" &&
                                        this.payMethodSelect != null
                                    ) {
                                        this.addApp();
                                    } else {
                                        Swal.fire(
                                            "Alerta",
                                            "Por favor verifique los datos del producto que desea agregar",
                                            "warning"
                                        );
                                    }
                                } else if (insert == true) {
                                    Swal.fire(
                                        "Alerta",
                                        "Por favor verifique los datos del producto que desea agregar",
                                        "warning"
                                    );
                                }
                            }
                            //console.log(this.resultArray);
                            this.errors = [];
                        })
                        .catch(function(error) {
                            this.test = error;
                        });
                }
            } else {
                if (insert == true) {
                    Swal.fire(
                        "Alerta",
                        "Por favor verifique los datos del producto que desea agregar",
                        "warning"
                    );
                }
            }
        },

        ///////////////////////////////////////////////////////////////////////////
        // Agrega el producto y llama a las funciones de verificacion de autorizador e historico de productos cotizados a este cliente
        addApp: function() {
            if (this.quantity == "") {
                Swal.fire(
                    "Alerta",
                    "Por favor verifique los datos del producto que desea agregar",
                    "warning"
                );
            } else {
                //alert(this.timeDiscount);
                if (this.alert == 0) {
                    // this.setAuthorizers();
                    this.verifyQuotaHistoryProduct();
                }
            }
        },

        ///////////////////////////////////////////////////////////////////////////
        /* Verifica si el producto esta vigente dentro de alguna cotizacion actual*/
        verifyQuotaHistoryProduct: function() {
            var urlProduct = "../../getHistoryProduct";
            axios
                .post(urlProduct, {
                    idProduct: this.productid,
                    idClient: this.client
                })
                .then(response => {
                    this.resultArray = response.data;
                    //console.log(this.resultArray);
                    if (this.resultArray.found) {
                        //alert("Ha superado el maximo de descuento permitido, por favor verifique");
                        Swal.fire({
                            title: "Confirmación",
                            text:
                                "El cliente tiene una cotización vigente para este producto, dentro del prediodo de vigencia de esta cotización, si continua el proceso, la cotización anterior del producto quedará sin vigencia",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Si, reemplazar",
                            cancelButtonText: "No, cancelar"
                        }).then(result => {
                            if (result.value) {
                                this.verifiyQuotaProduct();
                            }
                        });
                    } else {
                        this.verifiyQuotaProduct();
                    }
                    //console.log(this.resultArray);
                    this.errors = [];
                })
                .catch(function(error) {
                    this.test = error;
                });
        },

        ///////////////////////////////////////////////////////////////////////////
        /* Verifica si el producto esta en el listado actual de la cotizacion*/
        verifiyQuotaProduct: function() {
            found = $.inArray(Number(this.productid), this.listProducts);
            elem = this.listProducts.indexOf(Number(this.productid));
            //console.log(this.payMethod);
            if (found >= 0) {
                Swal.fire({
                    title: "Confirmación",
                    text:
                        "¿El producto ya fue agregado a la lista desea reemplazarlo?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, reemplazar",
                    cancelButtonText: "No, cancelar"
                }).then(result => {
                    //console.log("Paymethod: "+this.payMethodSelect);
                    if (result.value) {
                        $("#prod" + this.productid).addClass("pulse");
                        found = 0;
                        total = this.productPrice * this.quantity;
                        percent =
                            (Number(this.timeDiscount) +
                                Number(this.comDiscount)) /
                            100;
                        this.total = total;
                        //total = total - (total * percent);
                        //console.log(total);
                        if (this.priceDiscount == 0) {
                            this.descSymbol = "";
                        }
                        this.listAuthLevels[elem] = this.levelAuth;
                        this.inputProducts.splice(elem, 1, {
                            productId: this.productid,
                            productname: this.productName,
                            quantity: this.quantity,
                            uMinima: Number(
                                this.productPrice / this.productPriceUnits
                            ),
                            vComercial: this.productPriceCom,
                            vTotal: total,
                            symbol: this.descSymbol,
                            dtoPrecio: this.priceDiscount,
                            dtoComercial: this.comDiscount,
                            fPago: this.payMethod,
                            fPagoID: this.payMethodSelect,
                            productAuthLevel: this.levelDescID,
                            productLevel: this.levelAuth,
                            percent: this.percentDiscount
                        });
                        setTimeout(
                            function() {
                                this.removeClass(this.productid);
                            }.bind(this),
                            2000
                        );
                        this.totalQuota = 0;
                        for (
                            let index = 0;
                            index < this.inputProducts.length;
                            index++
                        ) {
                            this.totalQuota += Number(
                                this.inputProducts[index].vTotal
                            );
                        }
                        $("#productos").val("");
                        $("#id_payterm").val("");
                        $("#quantity").val("");
                        $("#precio").val("");

                        //this.levelAuth = "-";
                        this.percentDiscount = 0;
                        this.priceDiscount = 0;
                        this.productPrice = "";
                        this.quantity = "";
                        this.setAuthorizers();
                    }
                });
            } else {
                this.listProducts.push(Number(this.productid));
                total = this.productPrice * this.quantity;
                percent = Number(this.timeDiscount) / 100;
                this.total = total; //- (total * percent);
                //console.log("priceDiscount " + this.priceDiscount);
                //alert(total);
                if (this.priceDiscount == 0) {
                    this.descSymbol = "";
                }
                this.inputProducts.push({
                    productId: this.productid,
                    productname: this.productName,
                    quantity: this.quantity,
                    uMinima: Number(this.productPrice / this.productPriceUnits),
                    vComercial: this.productPriceCom,
                    vTotal: this.total,
                    symbol: this.descSymbol,
                    dtoPrecio: this.priceDiscount,
                    dtoComercial: this.comDiscount,
                    fPago: this.payMethod,
                    fPagoID: this.payMethodSelect,
                    productAuthLevel: this.levelDescID,
                    productLevel: this.levelAuth,
                    percent: this.percentDiscount
                });
                this.listAuthLevels.push(this.levelAuth);
                this.totalQuota = 0;
                for (
                    let index = 0;
                    index < this.inputProducts.length;
                    index++
                ) {
                    this.totalQuota += Number(this.inputProducts[index].vTotal);
                }
                $("#productos").val("");
                $("#id_payterm").val("");
                $("#quantity").val("");
                $("#precio").val("");

                // this.levelAuth = "-";
                this.percentDiscount = 0;
                this.priceDiscount = 0;
                this.productPrice = "";
                this.quantity = "";
            }

            this.setAuthorizers();
        },

        ///////////////////////////////////////////////////////////////////////////
        /* Calcula el nivel de autorizacion del producto y lo asigna a la cotizacion*/
        setAuthorizers: function() {
            this.levelAuthQuota = this.levelAuth;
            //console.log(this.levelAuthQuota);
            if (this.listAuthLevels.length > 0) {
                for (
                    let index = 0;
                    index < this.listAuthLevels.length;
                    index++
                ) {
                    if (this.levelAuthQuota < this.listAuthLevels[index]) {
                        this.levelAuthQuota = this.listAuthLevels[index];
                    }
                }
            }
        },

        ///////////////////////////////////////////////////////////////////////////
        /* Remueve los productos de la cotizacion actual */
        removeProduct: function(rnum) {
            this.levelAuth = "-";
            Swal.fire({
                title: "Confirmación",
                text:
                    "¿Esta seguro que desea eliminar este producto de la cotización?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar",
                cancelButtonText: "No, cancelar"
            }).then(result => {
                if (result.value) {
                    this.totalQuota -= Number(this.inputProducts[rnum].vTotal);
                    this.listProducts.splice(rnum, 1);
                    this.inputProducts.splice(rnum, 1);
                    this.listAuthLevels.splice(rnum, 1);
                    this.setAuthorizers();
                }
            });
        },

        ///////////////////////////////////////////////////////////////////////////
        /* Cancela la cotización */
        cancelQuota: function(e) {
            e.preventDefault();
            Swal.fire({
                title: "Confirmación",
                text: "¿Esta seguro que desea anular la cotización?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, anular",
                cancelButtonText: "No, cancelar"
            }).then(result => {
                if (result.value) {
                    $( "#cancelquota").delay(500).submit();
                }
            });
        },

        // UTILITIES

        ///////////////////////////////////////////////////////////////////////////
        // Modifica el precio en unidades de presentacion
        modifyPrice: function() {
            if (this.quantCheck == false) {
                this.productPrice =
                    (this.tempPrice / this.productPriceUnits) * this.quantity;
                this.quantCheck = true;
            } else {
                this.productPrice = this.tempPrice;
                this.quantCheck = false;
            }
        },

        formatPrice: function(value) {
            let val = (value / 1).toFixed(0).replace(".", ",");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },

        dateFormat: function(date) {
            return moment(date, "YYYY-MM-DD").format("DD/MM/YYYY");
        },

        removeClass: function(divID) {
            $("#prod" + divID).removeClass("pulse");
        }
    }
});
