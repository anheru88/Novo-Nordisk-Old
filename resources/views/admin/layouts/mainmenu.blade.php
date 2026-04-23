<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">OPERACIONES</li>
            <li class="{{ setActive('home') }}">
                <a href="{{  route('home') }}">
                    <i class="fas fa-home"></i><span> Inicio</span>
                </a>
            </li>
            @can('cotizaciones.index')
            <li class="{{ setActive('cotizaciones') }}">
                <a href="{{ route('cotizaciones.index') }}">
                    <i class="fas fa-file-alt"></i><span> Cotizaciones</span>
                </a>
            </li>
            @endcan
            @can('negociaciones.index')
            <li class="{{ setActive('negociaciones') }}">
                <a href="{{ route('negociaciones.index') }}">
                    <i class="fas fa-briefcase"></i><span> Negociaciones</span>
                </a>
            </li>
            <li class="{{ setActive('simulatorArp') }}">
                <a href="{{ route('simulatorArp.index') }}">
                    <i class="fas fa-calculator"></i><span> Simulador ARP</span>
                </a>
            </li>
            @endcan
            @if(Gate::check('clients.index') || Gate::check('products.index') || Gate::check('precios.index') ||
            Gate::check('levels.index') || Gate::check('scales.index'))
            <li class="header">CONFIGURACIÓN</li>
            @can('clients.index')
            <li class="{{ setActive('clients') }}">
                <a href="{{  route('clients.index') }}"><i class="fas fa-building"></i><span> Clientes</span></a>
            </li>
            @endcan
            @can('products.index')
            <li class="{{ setActive('products') }}">
                <a href="{{  route('products.index') }}"><i class="fas fa-syringe"></i><span> Productos</span></a>
            </li>
            @endcan
            @can('precios.index')
            <li class="{{ setActive('prices') }}"><a href="{{  route('prices.index') }}"><i
                        class="fas fa-dollar-sign"></i> <span> Precios</span> </a></li>
            @endcan
            @can('scales.index')
            <li class="{{ setActive('scales') }} "><a href="{{  route('escalas.index') }}"><i
                        class="fas fa-chart-bar"></i> <span> Escalas</span></a></li>
            @endcan
            @can('docs.edit')
            <li class="{{ setActive('formats') }} "><a href="{{  route('formats.index') }}"><i
                        class="fas fa-file-alt"></i> <span> Formatos de documentos</span></a></li>
            @endcan
            <li class="{{ setActive('arp') }} "><a href="{{  route('arp.index') }}"><i class="fa fa-cube" aria-hidden="true"></i><span> ARP </span></a></li>
            @endif

            @if(Gate::check('clientstype.index') || Gate::check('paymethods.index') || Gate::check('productlines.index')
            || Gate::check('productunits.index') || Gate::check('productuses.index'))
            <li class="header">DATOS DEL SISTEMA</li>
            @can('clientstype.index')
            <li {{ setActive('clientstype') }}><a href="{{  route('clientstype.index') }}"><i
                        class="fas fa-chess"></i><span> Tipos de cliente</span></a></li>
            @endcan
            @can('paymethods.index')
            <li {{ setActive('paymethods') }}><a href="{{  route('paymethods.index') }}"><i
                        class="fas fa-cash-register"></i><span> Metodos de pago</span></a></li>
            @endcan
            @can('productlines.index')
            <li {{ setActive('productlines') }}><a href="{{  route('productlines.index') }}"><i
                        class="fas fa-vials"></i><span> Lineas de producto</span></a></li>
            @endcan
            @can('productunits.index')
            <li {{ setActive('productunits') }}><a href="{{  route('productunits.index') }}"><i
                        class="fas fa-prescription-bottle"></i><span> Unidades de venta</span></a></li>
            @endcan
            @can('productuses.index')
            <li {{ setActive('productuses') }}><a href="{{  route('productuses.index') }}"><i
                        class="fas fa-link"></i><span> Usos adicionales</span></a></li>
            @endcan
            @can('clientstype.index')
            <li {{ setActive('brands') }}><a href="{{  route('brands.index') }}"><i class="fas fa-umbrella"></i><span> Marcas</span></a></li>
            @endcan
            @can('concept.index')
            <li {{ setActive('concepts') }}><a href="{{ route('concepts.index') }}"><i
                        class="fas fa-paperclip"></i><span> Conceptos de negociación</span></a></li>
            @endcan
            @endif
            @if(Gate::check('users.index') || Gate::check('roles.index') )
            <li class="header">USUARIOS</li>
            @can('users.index')
            <li class=" {{ setActive('users') }}">
                <a href="{{  route('users.index') }}"><i class="fas fa-users"></i><span> Usuarios</span></a>
            </li>
            @endcan
            @can('roles.index')
            <li class=" {{ setActive('roles') }}">
                <a href="{{  route('roles.index') }}"><i class="fas fa-sitemap"></i> <span> Roles</span></a>
            </li>
            @endcan
            @endif
            @can('reportes.index')
            <li class="header">INFORMES</li>
            <li class="{{ setActive('reports') }}">
                <a href="{{  route('reportes.index') }}"><i class="fas fa-chart-pie"></i> <span>Reportes</span></a>
            </li>
            <li class="{{ setActive('notas') }}">
                <a href="{{  route('notas') }}"><i class="fas fa-sticky-note"></i> <span>Notas</span></a>
            </li>
            <li class="{{ setActive('sapnotes') }}">
                <a href="{{  route('sapnotes') }}"><i class="fas fa-sticky-note"></i> <span>SAP</span></a>
            </li>
            @endcan
            <li class="header">DOCUMENTOS</li>
            <li class="{{ setActive('documentos') }}"><a href="{{  route('documentos.index') }}"><i
                        class="fas fa-book"></i><span> Repositorio de Documentoss</span></a></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
