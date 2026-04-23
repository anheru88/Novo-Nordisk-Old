new Vue({
    el: '#quotation',
    created: function(){
        this.quotaEdit();
    },
    data: {
        quotaID:"",
        listProducts:[],
        inputProducts:[],
        allProducts:[],
        products:[],
        productPrice:"",
        comDiscount:"",
        percentDiscount:0,
        clientChannel:"",
        productid:"",
        clientID:"",
        levelAuth:"",
        quantity:"",
        timeDiscount:"",
        vTotal:0,
        alert:"",
        totalQuota:0,
        quantCheck:false,
        productPriceOld:"",
        productPriceUnits:"",
        /// Agregar Productos
        priceDiscount:0,
        comDiscount:0,
        payPercent:"",
        payMethod:"",
        levelAuth:"",
        levelAuthQuota:0,
        levelDescIDQuota:0,
        levelDescID:"",
        appNum:"",
        alert:1,
        textDescription:"Descuento",
        hastaProd:"",
        // Autorizadores
        authorizer:false,
        usersList:[],
        userAuth:undefined,
    },
    methods:{

        /* Obtiene todos los productos de la cotizacion actual*/ 
        quotaEdit:function(){
            this.quotaID = document.getElementById("quotaID").value;
            this.clientChannel = document.getElementById("idChannel").value;
            this.clientID = document.getElementById("idClient").value;
            this.totalQuota = document.getElementById("quota_val").value;
            this.levelAuthQuota = document.getElementById("level").value;
             alert(this.quotaID);
            var urlUsers = '../../getEditProducts';
            axios.post(urlUsers,{
                idQuota: this.quotaID,
            }).then(response => {
                this.products = response.data;
                for (index = 0; index < this.products.length; index++) {
                    this.listProducts.push(this.products[index].id_product);
                    this.inputProducts.push({
                        productId:      this.products[index].id_product,
                        productname:    this.products[index].prod_name,
                        quantity:       this.products[index].quantity,
                        uMinima:        this.products[index].v_commercial_price,
                        vComercial:     this.products[index].v_commercial_price,
                        vTotal:         this.products[index].totalValue,
                        dtoPrecio:      this.products[index].time_discount,
                        dtoComercial:   this.products[index].pay_discount,
                        fPago:          this.products[index].payterm_name,
                        fPagoID:        this.products[index].id_payterm
                    });
                }
                this.errors = [];
                console.log(this.products);
            })
            .catch(function(error){
                this.test = error;
            })
        },

        /* Obtiene los  productos de la cotizacion actual en el formulario */
        getPreviousProduct:function(){
            if(this.client != ""){
                var urlProduct = '../../getPreviousProduct';
                this.desdeProd = "";
                this.hastaProd = "";
                this.productid = event.target.value;
                this.productName = event.target.options[event.target.options.selectedIndex].text;
                this.quantity = 1;
                this.comDiscount = 0;
                this.priceDiscount = 0;
                $( "#unidades" ).prop( "checked", false );
                this.quantCheck = false;
                //this.comDiscount = "";
                //alert(this.clientID);
                axios.post(urlProduct,{
                    idClient: this.clientID,
                    idProduct: this.productid,
                    channel: this.clientChannel,
                }).then(response => {
                    this.resultArray = response.data;
                    console.log(this.resultArray);
                    this.productPrice = this.resultArray.precio;
                    this.productPriceOld = this.resultArray.precio;
                    this.tempPrice = this.resultArray.precio;
                    this.productPriceUnits = this.resultArray.unidades;
                    this.comDiscount = this.resultArray.commercial_discount;
                    this.payMethodSelect = this.resultArray.id_payterm;
                    this.payPercent = this.resultArray.pay_discount;
                    this.percentDiscount = this.resultArray.time_discount;
                   // alert(this.timeDiscount);
                    $("#id_payterm").val(this.resultArray.id_payterm);
                   // $('#id_payterm option').eq(this.resultArray.id_payterm).prop('selected', true);
                    this.payMethod = $("#id_payterm option:selected").text();
                    //alert(this.payMethod);
                    this.timeDiscount = this.resultArray.time_discount;
                    this.levelDescID = this.resultArray.id_prod_auth_level;
                    this.levelAuth = this.resultArray.authlevel;
                    this.desdeProd = this.resultArray.desde[0];
                    this.hastaProd = this.resultArray.hasta[0];
                    this.errors = [];
                   // console.log(this.comDiscount);
                    if(this.comDiscount != undefined){
                        //this.calcProductQuota();
                    }
                    
                })
                .catch(function(error){
                    this.test = error;
                })
            }
        }, 


        /* Agrega producto a la cotizacion */ 
        addApp:function() {
            if(this.quantity == ""){
                Swal.fire(
                    'Alerta',
                    'Debe ingresar una cantidad de productos',
                    'warning',
                )
            }
            else if(this.payMethodSelect == ""){
                Swal.fire(
                    'Alerta',
                    'Debe seleccionar un metodo de pago',
                    'warning',
                )
            }            
            else{
                if(this.alert == 0){
                    this.verifyQuotaHistoryProduct();
                }
            }
        },

        /* Verifica si el producto esta en el listado actual de la cotizacion*/ 

        verifyQuotaHistoryProduct:function(){
            var urlProduct = '../../getHistoryProduct';
                axios.post(urlProduct,{
                    idProduct: this.productid,
                    idClient: this.client,
                }).then(response => {
                    this.resultArray = response.data;
                    //console.log(this.resultArray);
                    if(this.resultArray.found){
                        //alert("Ha superado el maximo de descuento permitido, por favor verifique");
                        Swal.fire({
                            title: 'Confirmación',
                            text: "El cliente tiene una cotización vigente para este producto, dentro del prediodo de vigencia de esta cotización, si continua el proceso, la cotización anterior del producto quedará sin vigencia",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, reemplazar',
                            cancelButtonText: 'No, cancelar',
                          }).then((result) => {
                            if (result.value) {
                                this.verifiyQuotaProduct();
                            }
                          })
                    }else{
                        this.verifiyQuotaProduct();
                    }
                    //console.log(this.resultArray);
                    this.errors = [];
                })
                .catch(function(error){
                    this.test = error;
                })            

            
        },


        ///////////////////////////////////////////////////////////////////////////

        /* Verifica si el producto esta en el listado actual de la cotizacion*/ 

        verifiyQuotaProduct:function(){
            found = $.inArray(Number(this.productid), this.listProducts);
            elem = this.listProducts.indexOf(Number(this.productid));
            //console.log(elem);
            if(found >= 0){
                Swal.fire({
                    title: 'Confirmación',
                    text: "¿El producto ya fue agregado a la lista desea reemplazarlo?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, reemplazar',
                    cancelButtonText: 'No, cancelar',
                  }).then((result) => {
                    if (result.value) {
                        $("#prod"+this.productid).addClass("pulse");
                        found = 0;
                        total = this.productPrice * this.quantity;
                        percent = (Number(this.timeDiscount) + Number(this.comDiscount))/100;
                        //total = total - (total * percent);
                        //console.log(total);
                        this.inputProducts.splice(elem,1,{
                            productId:          this.productid,
                            productname:        this.productName,
                            quantity:           this.quantity,
                            uMinima:            Number(this.productPrice / this.productPriceUnits),
                            vComercial:         this.productPriceOld,
                            vTotal:             total,
                            dtoPrecio:          this.priceDiscount,
                            dtoComercial:       this.comDiscount,
                            fPago:              this.payMethod,
                            fPagoID:            this.payMethodSelect,
                            productAuthLevel:   this.levelDescID,
                            productLevel:       this.levelAuth
                        });
                        setTimeout(function () { this.removeClass(this.productid) }.bind(this), 2000)
                        this.totalQuota = 0;
                        for (let index = 0; index < this.inputProducts.length; index++) {
                            this.totalQuota += Number(this.inputProducts[index].vTotal);
                        }
                        $('#productos').val('');
                        $('#id_payterm').val('');
                        $('#quantity').val('');
                        $('#precio').val('');
                        if(this.levelAuth > this.levelAuthQuota && this.levelAuth > 1){
                            //alert(this.levelDescID);*/
                            this.levelDescIDQuota = this.levelDescID;
                            this.levelAuthQuota = this.levelAuth;
                        }
                        this.levelAuth = "-";
                        this.percentDiscount = 0;
                        this.priceDiscount = 0;
                        this.productPrice = "";
                        this.quantity = "";
                    }
                  })

            }else{
                this.listProducts.push(Number(this.productid));
                total = this.productPrice * this.quantity;
                percent = (Number(this.timeDiscount) + Number(this.comDiscount))/100;
                //total = total - (total * percent);
               // console.log(total);
                this.inputProducts.push({
                    productId:          this.productid,
                    productname:        this.productName,
                    quantity:           this.quantity,
                    uMinima:            Number(this.productPrice / this.productPriceUnits),
                    vComercial:         this.productPriceOld,
                    vTotal:             total,
                    dtoPrecio:          this.priceDiscount,
                    dtoComercial:       this.comDiscount,
                    fPago:              this.payMethod,
                    fPagoID:            this.payMethodSelect,
                    productAuthLevel:   this.levelDescID,
                    productLevel:       this.levelAuth
                });
                this.totalQuota = 0;
                    for (let index = 0; index < this.inputProducts.length; index++) {
                        this.totalQuota += Number(this.inputProducts[index].vTotal);
                    }
                    $('#productos').val('');
                    $('#id_payterm').val('');
                    $('#quantity').val('');
                    $('#precio').val('');
                    if(this.levelAuth > this.levelAuthQuota && this.levelAuth > 1){
                        //alert(this.levelDescID);*/
                        this.levelDescIDQuota = this.levelDescID;
                        this.levelAuthQuota = this.levelAuth;
                    }
                    this.levelAuth = "-";
                    this.percentDiscount = 0;
                    this.priceDiscount = 0;
                    this.productPrice = "";
                    this.quantity = "";
            }  
        
        //console.log(this.levelAuth);
        if(this.levelAuth > this.levelAuthQuota){
            //alert(this.levelDescID);
            this.levelDescIDQuota = this.levelDescID;
            this.levelAuthQuota = this.levelAuth;
        }
        
        this.setAuthorizers();
        },

        
        /* Muestra los metodos de pago en el select */
        getPayForm:function(){
            if(this.client != "" && this.productPrice>0){
            this.payPercent = event.target.value;
            this.payMethodSelect = event.target.value;
            this.payMethod = event.target.options[event.target.options.selectedIndex].text;
            var urlProduct = '../../getPayForm';
            axios.post(urlProduct,{
                idPercent: this.payPercent,
            }).then(response => {
                this.resultArray = response.data;
                this.timeDiscount = this.resultArray[0];
                this.calcProductQuota();
                this.errors = [];
            })
            .catch(function(error){
                this.test = error;
            })
            }
        },

         // Modifica el precio en unidades de presentacion
        modifyPrice:function(){
            if(this.quantCheck == false){
                this.productPrice = (this.tempPrice / this.productPriceUnits ) * this.quantity;
               this.quantCheck = true;
            }else{
                this.productPrice = this.tempPrice;
                this.quantCheck = false;
            }
        },

        /* Calcula el porcentaje de producto */
        calcProductQuota:function(insert){
            //console.log(this.listProducts.length);
                if(this.quantity > 1 || this.listProducts.length >= 1){
                    this.showTotalCot = true;
                }else{
                    this.showTotalCot = false;
                }
                if(this.client != "" && this.productPrice > 0){
                    this.clientChannelID = $('#idChannel').val()
                    //alert($('#idChannel').val());
                    //console.log(this.payMethodSelect,this.productid, this.clientChannelID, this.clientChannel, this.comDiscount, this.productPrice);
                    var urlProduct = '../../calcProductQuota';
                    axios.post(urlProduct,{
                        payPercent: this.payMethodSelect,
                        idProduct: this.productid,
                        channelID: this.clientChannelID,
                        channel:  this.clientChannel,
                        comDisc: this.comDiscount,
                        quotaPrice: this.productPrice
                    }).then(response => {
                        this.resultArray = response.data;
                        console.log(this.resultArray);
                        if(this.resultArray.permitido == 0){
                            this.alert = 1;
                            //alert("Ha superado el maximo de descuento permitido, por favor verifique");
                            Swal.fire(
                                'Alerta',
                                'Ha superado el maximo de descuento permitido, por favor verifique',
                                'warning',
                            )
                            this.comDiscount = 0;
                            this.percentDiscount = this.resultArray.percent;
                            this.levelAuth = "";
                            this.levelDescID = "";
                            this.priceDiscount = 0;
                            this.productPrice = this.tempPrice;
                        }else{
                            this.alert = 0;
                            this.priceDiscount = this.resultArray.descPrecio;
                            this.levelAuth = this.resultArray.level;
                            this.percentDiscount = this.resultArray.percent;
                            this.textDescription = this.resultArray.text;
                            this.descSymbol = this.resultArray.descSymbol;
                           // console.log(this.descSymbol);
                            if (this.resultArray.level == 0) {
                                this.levelAuth = "-";
                                this.levelDescID = "";
                            } else {
                                this.levelDescID = this.resultArray.idLevel;
                                this.levelAuth = this.resultArray.level;
                            }
                            if(insert == true && this.alert == 0){
                                // alert(this.payMethodSelect);
                                 if(this.quantity != "" && this.productPrice != "" && this.productPrice > 0 && this.quantity > 0 && this.payMethodSelect != "" && this.payMethodSelect != null){
                                     this.addApp();
                                 }else{
                                     Swal.fire(
                                         'Alerta',
                                         'Por favor verifique los datos del producto que desea agregar',
                                         'warning',
                                     )
                                 }
                             }else if(insert == true){
                                 Swal.fire(
                                     'Alerta',
                                     'Por favor verifique los datos del producto que desea agregar',
                                     'warning',
                                 )
                             }
                        }
                        //console.log(this.resultArray);
                        this.errors = [];
                    })
                    .catch(function(error){
                        this.test = error;
                    })               
                }
        },

        setAuthorizers:function(){
            var urlCities = '../../getAuthorizers';
            //alert(this.levelAuth);
            if(this.levelAuth != "-"){
                axios.post(urlCities,{
                    level: this.levelAuth,
                }).then(response => {
                    this.usersList = response.data;
                    //console.log(this.levelDescIDQuota);
                    if(this.levelDescIDQuota > 0 || this.levelAuth > 1){
                        this.userAuth = true;
                    }
                    this.errors = [];
                })
                .catch(function(error){
                    this.test = error;
                })
            }
           
        },


        /* Reemplezar producto */
        addProductsBlock:function(idProduct, pos){
           /* alert(pos);*/
            //console.log(this.listProducts);
            productId = Number(idProduct);
            found = $.inArray(productId, this.listProducts);
            elem = this.listProducts.indexOf(productId);
            //console.log(elem);
            if(found >= 0){
                var conf = confirm("El producto ya existe, ¿desea reemplazarlo?");
                if(conf == true){
                    $("#prod"+productId).addClass("pulse");
                    found = 0;
                    //console.log(this.quantity);
                    this.inputProducts.splice(elem,1,{
                        productId:this.allProducts[pos].productId,
                        productname:this.allProducts[pos].productname,
                        quantity:this.allProducts[pos].quantity,
                        uMinima:this.allProducts[pos].uMinima,
                        vComercial: this.allProducts[pos].vComercial,
                        vTotal: this.allProducts[pos].vTotal,
                        dtoPrecio: this.allProducts[pos].dtoPrecio,
                        dtoComercial: this.allProducts[pos].dtoComercial,
                        fPago:this.allProducts[pos].fPago,
                        fPagoID: this.allProducts[pos].fPagoID
                    });
                    setTimeout(function () { this.removeClass(this.productid) }.bind(this), 2000)
                }
            } else{
                this.listProducts.push(idProduct);
                this.inputProducts.push({
                    productId: this.productid,
                    productname:this.productName,
                    quantity:this.quantity,
                    uMinima:this.productPrice,
                    vComercial: this.productPrice,
                    vTotal: this.vTotal,
                    dtoPrecio: this.payPercent,
                    dtoComercial: this.comDiscount,
                    fPago:this.payMethod,
                    fPagoID: this.payMethodSelect
                });
            }  
            //console.log(this.inputProducts);
            this.totalQuota = 0;
            for (let index = 0; index < this.inputProducts.length; index++) {
                this.totalQuota += Number(this.inputProducts[index].vTotal);
            }
        },

        addAllProducts:function(){
            // console.log(this.prodSelectedBlock);
             replace = false;
             add = true;
             found = 0;
             for (let index = 0; index < this.listSelectedProducts.length; index++) {
                 //const element = array[index];
                 found = $.inArray(this.prodSelectedBlock[index], this.listProducts);
                 if(found >= 0){
                     replace = true;
                 }                
             }
 
             if(replace){
                 replace = false;
                 Swal.fire({
                     title: 'Confirmación',
                     text: "¿Algunos productos ya han sido agregados desea reemplazarlos?",
                     type: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Si, reemplazar',
                     cancelButtonText: 'No, cancelar',
                   }).then((result) => {
                       resultado = result["dismiss"];
                       //console.log(resultado);
                         if(resultado == "cancel"){
                             add = false;
                             return;
                         }else{
                             for (let index = 0; index < this.listSelectedProducts.length; index++) {
                                 this.addProductsBlock(this.listSelectedProducts[index], this.prodSelectedBlock[index]);
                             }
                         }
                 });
             }else{
                 if(add){
                     //console.log(add);
                     for (let index = 0; index < this.listSelectedProducts.length; index++) {
                         this.addProductsBlock(this.listSelectedProducts[index], this.prodSelectedBlock[index]);
                     }
                 }
             }
         },

         selectAllProducts:function(){
            this.listSelectedProducts = [];
            this.prodSelectedBlock = [];
            checkboxes = document.getElementsByName('prods[]');
            //console.log(checkboxes);
            if(this.allSelected == false){
                for(i = 0; i < checkboxes.length; i++){
                    checkboxes[i].checked = true;
                    this.listSelectedProducts.push(Number(checkboxes[i].value));
                    this.prodSelectedBlock.push(Number(checkboxes[i].id));
                }
                this.allSelected = true;
            }else{
                for(var i in checkboxes){
                    checkboxes[i].checked = false;
                }
                this.listSelectedProducts = [];
                this.allSelected = false;
            }
            //console.log(this.listSelectedProducts);
            //console.log(this.allSelected);
            //console.log(this.prodSelectedBlock);
        },
 


        formatPrice:function(value) {
            let val = (value/1).toFixed(0).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },

        dateFormat:function(date) {
            return moment(date, 'YYYY-MM-DD').format('DD/MM/YYYY');
        },

        removeProduct:function(rnum) {
            //console.log(this.inputProducts[rnum].vTotal);  
            this.totalQuota -= Number(this.inputProducts[rnum].vTotal);
            this.listProducts.splice(rnum,1);
            this.inputProducts.splice(rnum,1);      
        },

        removeClass:function(divID){
            $("#prod"+divID).removeClass("pulse");
        },

        select:function(){
            this.allSelected = false;
        }

    }
});



        