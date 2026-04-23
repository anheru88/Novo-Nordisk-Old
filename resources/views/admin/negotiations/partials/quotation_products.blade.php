<div class="col-xs-12 col-sm-6 no-padding">
    <div class="container-fixed left-products" v-if="showProducts">
        <div class="row quot-add-product">
            <div class="col-xs-12">
                <div class="box-title"><i class="ion ion-compose"></i> Productos cotizados</div>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div class="box-subtitle">
                    Productos vigentes
                </div>
                <div class="left-col">
                    <input type="checkbox" name="select-all" id="select-all"  v-on:click="addAllProductsAsistida('products')"> Todos
                    <div class="left-product-name animated "
                        :class="{highlight:selected == index}"
                        v-for="(input, index) in productsArray" :id="'prod'+input.id_product"
                        v-on:click="previewProductQuota(index)" @click="selected = index">
                        <input type="checkbox" name="products" :id="'products-'+index" :value="index"
                        v-on:click="addSingleProductsAsistida()"> @{{ input.productname }}
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 no-padding-left">
                <div class="box-subtitle">
                    Descripcion de producto
                </div>
                <div class="right-col">
                    <div class="right-subtitle">Cotización</div>
                    <div class="right-cotnumber"><a :href="'../cotizaciones/'+c_idCot" target="_blank">@{{ c_consecutive }}</a></div>
                    <div class="right-subtitle">Vigencia</div>
                    <div class="right-description bold">
                        <i class="fas fa-calendar-alt"></i> Desde: <strong>@{{ c_vigencia_ini }}</strong> <br>
                        <i class="fas fa-calendar-alt"></i> Hasta: <strong>@{{ c_vigencia_end }}</strong>
                    </div>
                    <div class="right-subtitle">Producto</div>
                    <div class="right-description bold">@{{ c_prod }}</div>
                    <div class="right-subtitle">Cantidad Cotizada</div>
                    <div class="right-description">@{{ c_quantity }}</div>
                    <div class="right-subtitle">Precio unidad mínima</div>
                    <div class="right-description">$@{{ formatPrice(c_unitPrice) }}</div>
                    <div class="right-subtitle">Precio presentación comercial</div>
                    <div class="right-description">$@{{ formatPrice(c_comPrice) }}</div>
                    <div class="right-subtitle">Precio total cotizado</div>
                    <div class="right-description">$@{{ formatPrice(c_totalQuota) }}</div>
                    <div class="right-subtitle">Ajustes al precio</div>
                    <div class="right-description">@{{ c_priceDesc }}%</div>
                    <div class="right-subtitle">Condiciones financieras</div>
                    <div class="right-description">@{{ c_payMethod }}</div>
                    <div class="right-subtitle">Escala</div>
                    <div class="right-description"> @{{ c_scale }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
