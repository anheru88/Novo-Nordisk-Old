<div class="col-xs-12 no-padding-left">
    <div class="box-title"><i class="ion ion-compose"></i> Agregar descuentos negociados</div>
</div>
<div class="discountsTabs">
    <div class="selectorTabs">
        <div class="optionTab btn btn-sm" v-bind:class="[asistida ? 'btn-info' : 'btn-default']" v-on:click="showTab('asistida')">Asistida</div>
        <div class="optionTab btn btn-sm" v-bind:class="[individual ? 'btn-info' : 'btn-default']" v-on:click="showTab('individual')">Individual</div>
    </div>
    @include('admin.negotiations.partials.add_discountgroup')
    @include('admin.negotiations.partials.add_discountsingle')
</div>
