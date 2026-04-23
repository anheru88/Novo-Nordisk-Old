new Vue({
    el: "#app",
    created: function() {
        //this.showLevel();
    },
    data: {
        levelAuth: false,
        levelCheck: "",
        answer: "",
        res: "",
        approved: "",
        // Vector de respuesta a toda consulta
        resultArray: [],
        // Variables del modal para el envio de la cotizacion
        idQuotation: "",
        quotaNumber: "",
        client: "",
        channel: "",
        start: "",
        end: "",
        email: ""
    },
    methods: {
        sendEmail: function(id) {
            console.log(id);
        },

        sendQuotaEmail: function(idQuota) {
            var urlProduct = "../getQuotationData";
            axios
                .post(urlProduct, {
                    idQuota: idQuota
                })
                .then(response => {
                    this.resultArray = response.data;
                    this.idQuotation = idQuota;
                    this.quotaNumber = this.resultArray["quota_consecutive"];
                    this.client = this.resultArray["cliente"].client_name;
                    this.channel = this.resultArray["channel"].channel_name;
                    this.start = this.resultArray["quota_date_ini"];
                    this.end = this.resultArray["quota_date_end"];
                    this.email = this.resultArray["cliente"].client_email;
                    console.log(this.resultArray);
                })
                .catch(function(error) {
                    this.test = error;
                });
        }
    }
});
