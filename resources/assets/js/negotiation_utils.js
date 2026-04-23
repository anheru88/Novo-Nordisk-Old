new Vue({
    el: "#app",
    created: function() {
        //this.showLevel();
    },
    data: {
        // Vector de respuesta a toda consulta
        resultArray: [],
        // Variables del modal para el envío de la negociación
        idNegotiation: "",
        negotiationNumber: "",
        client: "",
        channel: "",
        start: "",
        end: "",
        email: "",
    },
    methods: {
        /*Edición de fecha*/
        getNegoData: function(idNegotiation){
            var urlProduct = "../getNegotiData";
            axios
                .post(urlProduct, {
                    idNegoti: idNegotiation
                })
                .then(response => {
                    console.log(response.data);
                    this.resultArray        = response.data;
                    this.idNegotiation      = idNegotiation;
                    this.negotiationNumber  = this.resultArray["negotiation_consecutive"];
                    this.client             = this.resultArray["cliente"].client_name;
                    this.channel            = this.resultArray["channel"].channel_name;
                    this.start              = this.resultArray["negotiation_date_ini"];
                    this.end                = this.resultArray["negotiation_date_end"];;
                   // console.log(this.resultArray);
                })
                .catch(function(error) {
                    this.test = error;
                });
        },
        /*Envío de Email de Negociación*/
        sendNegoEmail: function(idNegotiation) {
            var urlProduct = "../getNegotiData";
            axios
                .post(urlProduct, {
                    idNego: idNegotiation
                })
                .then(response => {
                    this.resultArray = response.data;
                    this.idNegotiation = idNegotiation;
                    this.negoNumber = this.resultArray["negotiation_consecutive"];
                    this.client = this.resultArray["cliente"].client_name;
                    this.channel = this.resultArray["channel"].channel_name;
                    this.start = this.resultArray["negotiation_date_ini"];
                    this.end = this.resultArray["negotiation_date_end"];
                    this.email = this.resultArray["cliente"].client_email;
                    console.log(this.resultArray);
                })
                .catch(function(error) {
                    this.test = error;
                });
        }
    }
});
