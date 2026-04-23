new Vue({
    el: '#app',
    created: function() {
        //this.showLevel();
    },
    data: {
        // Variables del modal para el envio de las fechas de los precios
        idPrices: "",
        startPrice: "",
        endPrice: "",
    },
    methods: {
        getPriceData: function(idPrices) {
            var urlProduct = '../getPriceData';
            axios.post(urlProduct, {
                    idPrices: idPrices,
                }).then(response => {
                    this.resultArray = response.data;
                    this.idPrices = idPrices;
                    this.startPrice = this.resultArray['prod_valid_date_ini'];
                    this.endPrice = this.resultArray['prod_valid_date_end'];
                    console.log(this.resultArray);
                })
                .catch(function(error) {
                    this.test = error;
                })
        },
    }
});