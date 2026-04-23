new Vue({
    el: '#app',
    created: function() {
        this.getType();
    },
    data: {
        sendCot: "true",
        user: "",
        res: "",
        clients: [],
        enable: false,
        selectTypeClient: false,
        selectTypeCotOrNeg: false,
        // select type 
        selectType: "",
        //////////////
        department: "",
        cities: [],
        city: undefined,
        selectedCity: undefined,
    },
    methods: {
        onChange(event) {
            this.res = event.target.value;
            if (this.res == 'cli') {
                this.selectTypeClient = true
                this.selectTypeCotOrNeg = false
            } else
            if (this.res == 'cot') {
                this.selectTypeCotOrNeg = true
                this.selectTypeClient = false
            } else if (this.res == 'neg') {
                this.selectTypeCotOrNeg = true
                this.selectTypeClient = false
            }
            // console.log($('#type').val());
        },
        getType: function() {
            this.res = $('#type1').val();
            console.log($('#type1').val());
            if (this.res == 'cli') {
                this.selectTypeClient = true
                this.selectTypeCotOrNeg = false
            } else
            if (this.res == 'cot') {
                this.selectTypeCotOrNeg = true
                this.selectTypeClient = false
            } else if (this.res == 'neg') {
                this.selectTypeCotOrNeg = true
                this.selectTypeClient = false
            }
        },
        getClients: function() {
            this.clients = [];
            this.user = $('#usuario').val();
            var urlProduct = "../getClientReport";
            axios.post(urlProduct, {
                    idUser: this.user
                })
                .then(response => {
                    this.clients = response.data;
                    if (this.clients.length > 0) {
                        this.enable = true
                    }
                    this.errors = [];
                })
                .catch(function(error) {
                    this.test = error;
                });
        },
        selectDepartment: function() {
            //alert(this.department);
            this.city = undefined;
            var urlCities = '../getCities';
            axios.post(urlCities, {
                    department: this.department,
                }).then(response => {
                    this.cities = response.data;
                    //console.log(this.cities);
                    this.errors = [];
                })
                .catch(function(error) {
                    this.test = error;
                })
        },
        changeCity: function() {
            //alert(event.target.value);
            this.department = event.target.value;
            var urlCities = '../../getCities';
            axios.post(urlCities, {
                    department: this.department,
                }).then(response => {
                    this.cities = response.data;
                    //console.log(this.cities);
                    this.errors = [];
                })
                .catch(function(error) {
                    this.test = error;
                })
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
    }
});