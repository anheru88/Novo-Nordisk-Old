var vueF = new Vue({
    el: "#app",
    created: function() {
        // this.showAlert();
    },
    data: {
        resultArray: [],
        dateOK: false,
        quantCheck: false,
        days: 0,
        ////////////// Client info
        department: "",
        cities: [],
        city: " ----- ",
        selectedCity: undefined,
        payterm_name: "",
        payterm_id: "",
        //////////////
        productid: "",
        products: [],
        productName: "",
        productList: "",
        productPrice: "",
        productPriceOld: "",
        productPriceCom: "",
        productPriceUnits: "",
        tempPrice: "",
        desdeProd: "",
        hastaProd: "",
        level4: false,
        ////////////// Dates
        dateIni: "",
        dateEnd: "",
        /////////////////
        clients: [],
        client: "",
        clientCode: " ------ ",
        clientChannel: " ------ ",
        clientChannelID: "",
        //////////////
        authorizer: false,
        //////////////
        listProducts: [],
        listSelectedProducts: [],
        listAuthLevels: [], // Guarda los niveles de autorizacion de la cotización
        allSelected: false,
        //////////////
        showTotalCot: false,
        showDaysWarning: false, // mensaje de advertencia de mas de un año
        quantity: 1,
        total: "",
        timeDiscount: "",
        priceDiscount: 0,
        comDiscount: 0,
        payPercent: "",
        payMethod: "",
        levelAuth: 0,
        levelAuthQuota: 0, // Nivel de autorizacion de la cotizacion
        levelDescID: "",
        appNum: "",
        alert: 1,
        textDescription: "Descuento",
        descSymbol: "-",
        quotaValue: 0,
        percentDiscount: 0,
        payMethodSelect: "",
        totalQuota: 0,
        inputProducts: [],
        quotaID: "",
        allProducts: [],
        usersList: [],
        userAuth: false,
        prodSelectedBlock: [],
        /// Autorizadores
        selectAuth: undefined
    },
    methods: {

        // Get all products by selected client.
        getAllProducts: function() {
            var dateIni = $("#quota_date_ini").val();
            var dateEnd = $("#quota_date_end").val();
            this.dateIni = dateIni;
            this.dateEnd = dateEnd;
            this.allProducts = [];
            var urlUsers = "../../getProductsClient";
            axios
                .post(urlUsers, {
                    idClient: this.client,
                    dateIni: dateIni,
                    dateEnd: dateEnd
                })
                .then(response => {
                    this.resultArray = response.data;
                    //console.log(this.resultArray);
                    for (index = 0; index < this.resultArray.length; index++) {
                        this.allProducts.push({
                            productId: this.resultArray[index].id_product,
                            productname: this.resultArray[index].prod_name,
                            quantity: this.resultArray[index].quantity,
                            uMinima: parseInt(
                                this.resultArray[index].v_commercial_price /
                                    this.resultArray[index].prod_package_unit
                            ),
                            vComercial: this.resultArray[index]
                                .v_commercial_price,
                            vTotal: this.resultArray[index].totalValue,
                            preciolvl3: this.resultArray[index].preciolvl3,
                            precioCotAct: this.resultArray[index]
                                .preciocotActual,
                            symbol: "",
                            dtoPrecio: this.resultArray[index].time_discount,
                            dtoComercial: this.resultArray[index].pay_discount,
                            fPago: this.resultArray[index].payterm_name,
                            fPagoID: this.resultArray[index].id_payterm,
                            productAuthLevel: this.resultArray[index]
                                .id_prod_auth_level,
                            productLevel: this.resultArray[index].levelAuth
                        });
                    }
                    this.errors = [];
                    //console.log(this.allProducts);
                })
                .catch(function(error) {
                    this.test = error;
                });
        },

        // Get client data
        getClient: function() {
            this.levelAuth = "-";
            this.inputProducts = [];
            this.client = $("#id_client").val();
            var urlProduct = "../getClient";
            var dateIni = $("#quota_date_ini").val();
            var dateEnd = $("#quota_date_end").val();
            if (dateIni == "" || dateEnd == "") {
                Swal.fire(
                    "Alerta",
                    "Debe ingresar las fechas de vigencia",
                    "warning"
                );
                $(".clientes-select").select2("val", "");
                return false;
            }
            if (this.client != "") {
                this.productPrice = "";
                $("#productos").val("");
                $("#id_payterm").val("undefined");
                this.hastaProd = "";
                // this.levelAuth = "";
                this.getAllProducts();
                axios
                    .post(urlProduct, {
                        idClient: this.client
                    })
                    .then(response => {
                        this.clients = response.data;
                        console.log(this.clients);
                        this.department = response.data[0].id_department;
                        this.selectedCity =
                            response.data[0].city["id_locations"];
                        this.city = response.data[0].city["loc_name"];
                        this.clientCode = response.data[0].client_sap_code;
                        this.clientChannel =
                            response.data[0].channel["channel_name"];
                        this.clientChannelID =
                            response.data[0].channel["id_channel"];
                        this.payterm_id = response.data[0].id_payterm;
                        this.payterm_name =
                            response.data[0].payterm["payterm_name"];
                        this.timeDiscount =
                            response.data[0].payterm["payterm_percent"];
                        // this.selectDepartment();
                        this.errors = [];
                    })
                    .catch(function(error) {
                        this.test = error;
                    });
            } else {
                this.clientCode = "------";
                this.clientChannel = "------";
                this.clientChannelID = "";
                this.city = "------";
                this.selectedCity = "";
                this.department = "";
                this.cities = "";
                this.quantity = 1;
                this.productPrice = "";
                $("#productos").val("");
                $("#id_payterm").val("undefined");
                this.hastaProd = "";
                // this.levelAuth = "";
            }
        },

        // Revisa si existen productos previos en el listado de detalle de producto y trae los datos para la nueva cotizacion
        getPreviousProduct: function() {
            if (this.client != "") {
                var urlProduct = "../getPreviousProduct";
                this.desdeProd = "";
                this.hastaProd = "";
                this.productid = event.target.value;
                this.productName =
                    event.target.options[
                        event.target.options.selectedIndex
                    ].text;
                this.quantity = 1;
                this.comDiscount = 0;
                $("#unidades").prop("checked", false);
                this.quantCheck = false;
                var dateIni = $("#quota_date_ini").val();
                var dateEnd = $("#quota_date_end").val();
                if (dateIni == "" || dateEnd == "") {
                    Swal.fire(
                        "Alerta",
                        "Debe ingresar las fechas de vigencia",
                        "warning"
                    );
                    return false;
                }
                //alert(this.clientChannel);
                axios
                    .post(urlProduct, {
                        idClient: this.client,
                        idProduct: this.productid,
                        channel: this.clientChannel,
                        dateIni: dateIni,
                        dateEnd: dateEnd
                    })
                    .then(response => {
                        this.resultArray = response.data;
                        console.log(response.data);
                        this.productPrice = this.resultArray.precio;
                        this.productPriceCom = this.resultArray.precio_com;
                        this.productPriceOld = this.resultArray.precio_old;
                        this.tempPrice = this.resultArray.precio;
                        this.productPriceUnits = this.resultArray.unidades;
                        this.comDiscount = this.resultArray.commercial_discount;
                        this.payMethodSelect = this.resultArray.id_payterm;
                        this.payPercent = this.resultArray.pay_discount;
                        this.priceDiscount = 0;
                        this.percentDiscount = this.resultArray.time_discount;
                        // alert(this.timeDiscount);
                        $("#id_payterm").val(this.resultArray.id_payterm);
                        // $('#id_payterm option').eq(this.resultArray.id_payterm).prop('selected', true);
                        this.payMethod = $(
                            "#id_payterm option:selected"
                        ).text();
                        //alert(this.payMethod);
                        this.timeDiscount = this.resultArray.time_discount;
                        this.levelDescID = this.resultArray.id_prod_auth_level;
                        this.levelAuth = "-";
                        this.desdeProd = this.resultArray.desde[0];
                        this.hastaProd = this.resultArray.hasta[0];
                        this.errors = [];
                        //console.log(this.comDiscount);
                        /*if(this.comDiscount != undefined){
                        this.calcProductQuota();
                    }*/
                    })
                    .catch(function(error) {
                        this.test = error;
                    });
            } else {
                $("#productos").val("");
                Swal.fire("Alerta", "Debe seleccionar un cliente", "warning");
            }
        },

        // Set the autorizer list, if the level auth of quota is > 1
        setAuthorizers: function() {
            // var urlCities = '../getAuthorizers';
            this.levelAuthQuota = this.levelAuth;
            //this.levelAuth = "-";
            //console.log(this.listAuthLevels);
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

            if (this.levelAuthQuota == 1) {
                var diasdif = $("#days").val();
                if (diasdif > 365) {
                    this.showDaysWarning = true;
                    this.levelAuth = 2;
                    this.levelAuthQuota = this.levelAuth;
                }
            }
        },

        // Consult the payforms by selected product
        getPayForm: function() {
            if (this.client != "" && this.productPrice > 0) {
                this.payPercent = event.target.value;
                this.payMethod =
                    event.target.options[
                        event.target.options.selectedIndex
                    ].text;
                this.payMethodSelect = event.target.value;
                var urlProduct = "../getPayForm";
                axios
                    .post(urlProduct, {
                        idPercent: this.payPercent
                    })
                    .then(response => {
                        this.resultArray = response.data;
                        this.timeDiscount = this.resultArray[0];
                        this.calcProductQuota(false);
                        this.errors = [];
                    })
                    .catch(function(error) {
                        this.test = error;
                    });
            }
        },

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

        // Calcula el valor del producto en funcion de la cantidad y las reglas comerciales actuales
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
                    //alert(this.productPrice);
                    //console.log("channel " + this.clientChannel);
                    var urlProduct = "../calcProductQuota";
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
                            //console.log("calcQuota " + response.data);
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
                                // console.log(this.descSymbol);
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

        /* Verifica si el producto esta en una cotizacion previa realizada a este cliente y solicita verificacion para agregar y reemplazar el producto en el historico*/
        verifyQuotaHistoryProduct: function() {
            var dateIni = $("#quota_date_ini").val();
            var dateEnd = $("#quota_date_end").val();
            this.dateIni = dateIni;
            this.dateEnd = dateEnd;
            var urlProduct = "../getHistoryProduct";
            axios
                .post(urlProduct, {
                    idProduct: this.productid,
                    idClient: this.client,
                    dateIni: this.dateIni,
                    dateEnd: this.dateEnd
                })
                .then(response => {
                    this.resultArray = response.data;
                    //console.log(this.resultArray);
                    if (this.resultArray.found) {
                        //alert("Ha superado el maximo de descuento permitido, por favor verifique");
                        Swal.fire({
                            title: "Confirmación",
                            text:
                                "El cliente tiene una cotización vigente para este producto, dentro del período de vigencia de esta cotización, si continua el proceso, la cotización anterior del producto quedará sin vigencia",
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
            //console.log(elem);
            if (found >= 0) {
                Swal.fire({
                    title: "Confirmación",
                    text:"¿El producto ya fue agregado a la lista desea reemplazarlo?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, reemplazar",
                    cancelButtonText: "No, cancelar"
                }).then(result => {
                    if (result.value) {
                        $("#prod" + this.productid).addClass("pulse");
                        found = 0;
                        total = this.productPrice * this.quantity;
                        percent = Number(this.timeDiscount) / 100;
                        //console.log("productPriceUnits " + this.productPriceUnits);
                        this.total = total; //- (total * percent);
                        //console.log(this.productPrice);
                        //console.log(this.payMethodSelect,this.productid, this.clientChannelID, this.clientChannel, this.comDiscount, this.productPrice);
                        if (this.priceDiscount == 0) {
                            this.descSymbol = "";
                        }
                        this.payMethod = this.payterm_name;
                        //console.log(this.levelAuth);
                        this.listAuthLevels[elem] = this.levelAuth;
                        this.inputProducts.splice(elem, 1, {
                            productId: this.productid,
                            productname: this.productName,
                            quantity: this.quantity,
                            uMinima: Number(
                                this.productPrice / this.productPriceUnits
                            ),
                            vComercial: this.productPriceOld,
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
                        //console.log( this.inputProducts);
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
                        this.getDays();
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
                this.payMethod = this.payterm_name;
                this.inputProducts.push({
                    productId: this.productid,
                    productname: this.productName,
                    quantity: this.quantity,
                    uMinima: Number(this.productPrice / this.productPriceUnits),
                    vComercial: this.productPriceOld,
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
                // console.log( "auth levels " + this.listAuthLevels );
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
                //this.levelAuth = "-";
                this.percentDiscount = 0;
                this.priceDiscount = 0;
                this.productPrice = "";
                this.quantity = "";
            }

            this.setAuthorizers();
            //this.getDays();
        },

        /* AGREGAR PRODUCTOS EN BLOQUE */

        addAllProducts: function() {
            /* console.log("lestSelectedProducts " + this.listSelectedProducts);
            console.log("prodSelectedBlock " + this.prodSelectedBlock);*/
            replace = false;
            add = true;
            found = 0;
            for (
                let index = 0;
                index < this.prodSelectedBlock.length;
                index++
            ) {
                //const element = array[index];
                found = $.inArray(
                    this.prodSelectedBlock[index],
                    this.listProducts
                );
                if (found >= 0) {
                    replace = true;
                }
            }

            if (replace) {
                replace = false;
                Swal.fire({
                    title: "Confirmación",
                    text:
                        "¿Algunos productos ya han sido agregados desea reemplazarlos?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, reemplazar",
                    cancelButtonText: "No, cancelar"
                }).then(result => {
                    resultado = result["dismiss"];
                    //console.log(resultado);
                    if (resultado == "cancel") {
                        add = false;
                        return;
                    } else {
                        for (
                            let index = 0;
                            index < this.listSelectedProducts.length;
                            index++
                        ) {
                            this.addProductsBlock(
                                this.listSelectedProducts[index],
                                this.prodSelectedBlock[index]
                            );
                        }
                    }
                });
            } else {
                if (add) {
                    //console.log(add);
                    for (
                        let index = 0;
                        index < this.listSelectedProducts.length;
                        index++
                    ) {
                        this.addProductsBlock(
                            this.listSelectedProducts[index],
                            this.prodSelectedBlock[index]
                        );
                    }
                }
            }
        },

        addProductsBlock: function(idProduct, prodSelectedId) {
            productId = Number(idProduct);
            found = $.inArray(prodSelectedId, this.listProducts);
            elem = this.listProducts.indexOf(prodSelectedId);
            // console.log("posicion " + elem);
            if (found >= 0) {
                found = 0;
                //console.log(this.quantity);
                this.listAuthLevels.splice(rnum, this.levelAuth);
                this.inputProducts.splice(elem, 1, {
                    productId: this.allProducts[idProduct].productId,
                    productname: this.allProducts[idProduct].productname,
                    quantity: this.allProducts[idProduct].quantity,
                    uMinima: this.allProducts[idProduct].uMinima,
                    vComercial: this.allProducts[idProduct].vComercial,
                    vTotal: this.allProducts[idProduct].vTotal,
                    symbol: "",
                    dtoPrecio: this.allProducts[idProduct].dtoPrecio,
                    dtoComercial: this.allProducts[idProduct].dtoComercial,
                    fPago: this.allProducts[idProduct].fPago,
                    fPagoID: this.allProducts[idProduct].fPagoID,
                    productAuthLevel: this.allProducts[idProduct]
                        .productAuthLevel,
                    productLevel: this.allProducts[idProduct].productLevel
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
                    this.totalQuota += Number(this.inputProducts[index].vTotal);
                }
            } else {
                this.listProducts.push(Number(prodSelectedId));
                this.listAuthLevels.push(this.levelAuth);
                this.inputProducts.push({
                    productId: this.allProducts[productId].productId,
                    productname: this.allProducts[productId].productname,
                    quantity: this.allProducts[productId].quantity,
                    uMinima: this.allProducts[productId].uMinima,
                    vComercial: this.allProducts[productId].vComercial,
                    vTotal: this.allProducts[productId].vComercial,
                    symbol: "",
                    dtoPrecio: this.allProducts[productId].dtoPrecio,
                    dtoComercial: this.allProducts[productId].dtoComercial,
                    fPago: this.allProducts[productId].fPago,
                    fPagoID: this.allProducts[productId].fPagoID,
                    productAuthLevel: this.allProducts[productId]
                        .productAuthLevel,
                    productLevel: this.allProducts[productId].productLevel
                });
                this.totalQuota = 0;
                for (
                    let index = 0;
                    index < this.inputProducts.length;
                    index++
                ) {
                    this.totalQuota += Number(this.inputProducts[index].vTotal);
                }
            }
            //console.log(this.inputProducts);
            this.totalQuota = 0;
            for (let index = 0; index < this.inputProducts.length; index++) {
                this.totalQuota += Number(this.inputProducts[index].vTotal);
            }
            this.showTotalCot = this.inputProducts.length;
            // console.log("Total " + this.totalQuota);
            this.elem = 0;
        },

        selectAllProducts: function() {
            this.listSelectedProducts = [];
            this.prodSelectedBlock = [];
            checkboxes = document.getElementsByName("prods[]");
            //console.log(checkboxes);
            if (this.allSelected == false) {
                for (i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].checked = true;
                    this.listSelectedProducts.push(Number(checkboxes[i].value));
                    this.prodSelectedBlock.push(Number(checkboxes[i].id));
                }
                this.allSelected = true;
            } else {
                for (var i in checkboxes) {
                    checkboxes[i].checked = false;
                }
                this.listSelectedProducts = [];
                this.allSelected = false;
            }
            //console.log(this.listSelectedProducts);
            //console.log(this.allSelected);
            //console.log(this.prodSelectedBlock);
        },

        select: function(e) {
            //this.listSelectedProducts = [];
            // console.log(this.listSelectedProducts);
            this.allSelected = false;
            $("#select_all").prop("checked", false);
            prodSelectedId = Number(e.target.id);
            prodSelected = Number(e.target.value);
            found = $.inArray(prodSelected, this.listSelectedProducts);
            //console.log(found);
            if (found >= 0) {
                this.listSelectedProducts.splice(found, 1);
                this.prodSelectedBlock.splice(found, 1);
            } else {
                this.listSelectedProducts.push(prodSelected);
                this.prodSelectedBlock.push(prodSelectedId);
            }
        },

        // Elimina el producto del listado actual de la cotización
        removeProduct: function(rnum) {
            //console.log(this.inputProducts[rnum].vTotal);
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
                    this.getDays();
                    this.setAuthorizers();
                }
            });

            //
        },

        /* UTILITY FUNCTIONS | FUNCIONES GENERICAS DE LA COIZACION */

        // Verifica que la cotizacion tenga productos
        sendCotizacion: function(event) {
            if (this.inputProducts <= 0) {
                Swal.fire("Alerta", "Debe agregar un producto", "warning");
                event.preventDefault();
            }
        },

        // Da formato al precio en el modo vue.
        formatPrice: function(value) {
            let val = (value / 1).toFixed(0).replace(".", ",");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },

        // Da formato a la fecha en el modo vue.
        dateFormat: function(date) {
            return moment(date, "YYYY-MM-DD").format("DD/MM/YYYY");
        },

        // Elimina la clase que realiza la animacion al agregar un producto
        removeClass: function(divID) {
            $("#prod" + divID).removeClass("pulse");
        },

        // Transforma los dias en fecha al agregarlos a la cotizacion

        setDate: function() {
            if (this.dateOK == false) {
                $("#quota_date_ini")
                    .datepicker({ dateFormat: "y-m-d" })
                    .datepicker("setDate", new Date());
                this.dateOK = true;
            } else {
                $("#quota_date_ini").val("");
                this.dateOK = false;
            }
            this.getDays();
        },

        // Transforma la fecha en dias al agregarla a la cotizacion

        setDays: function() {
            var tt = $("#quota_date_ini").val();
            var days = Number($("#days").val());
            var date = new Date(tt);
            var newdate = new Date(date);

            newdate.setDate(date.getDate() + days + 1);

            var dd = newdate.getDate();
            var mm = newdate.getMonth() + 1;
            var y = newdate.getFullYear();

            var someFormattedDate = y + "-" + mm + "-" + dd;
            this.getDays();
            //alert(someFormattedDate);
            $("#quota_date_end")
                .datepicker({ dateFormat: "y-m-d" })
                .datepicker("setDate", someFormattedDate);
        },

        // Cuenta los dias en caso de que sean mayores a solicita un autorizador para la cotización

        getDays: function() {
            var fechaini = new Date(String($("#quota_date_ini").val()));
            var fechafin = new Date(String($("#quota_date_end").val()));
            if (fechaini != "" && fechafin != "") {
                var diasdif = $("#days").val();
                //console.log(diasdif);

                if (diasdif > 365) {
                    //this.showTotalCot = true;
                    this.authorizer = true;
                    this.showDaysWarning = true;
                    this.levelAuth = 2;
                    this.levelAuthQuota = this.levelAuth;
                    this.setAuthorizers();
                } else {
                    //this.showTotalCot = false;
                    this.authorizer = false;
                    this.showDaysWarning = false;
                    this.levelAuth = 1;
                    this.levelAuthQuota = this.levelAuth;
                    // console.log(this.levelAuthQuota);
                    this.setAuthorizers();
                }
            }
            if (this.client != "") {
                this.getAllProducts();
            }
        },

        // Funcion que elimina el select all del modal de productos

        uncheck: function() {
            $("#select_all").prop("checked", false);
            checkboxes = document.getElementsByName("prods[]");
            for (var i in checkboxes) {
                checkboxes[i].checked = false;
            }
            this.listSelectedProducts = [];
            this.prodSelectedBlock = [];
            this.allSelected = false;
        },

        // Activa o desactiva el nivel 4 en las cotizaciones solo es valido para el perfil de auditor o hefe de CAMS
        activelevel4() {
            this.level4 = !this.level4;
            //console.log(this.level4);
        },

        // Evita el uso del ENTER para el envio del formulario
        numbervalidator: function(evt) {
            evt = evt ? evt : window.event;
            var charCode = evt.which ? evt.which : evt.keyCode;
            //console.log(charCode);
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                evt.preventDefault();
            } else {
                return true;
            }
        },

        // reset all the quotation data
        resetForm: function (){

        }
    }
});
