<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Attribute columns using the legacy prefixed naming that must be
     * normalized to drop the redundant table-name prefix.
     *
     * Each entry: [table, oldColumn, newColumn].
     *
     * @var array<int, array{0:string,1:string,2:string}>
     */
    private array $renames = [
        ['additional_terms',      'use_name',                'name'],
        ['arp_clients',           'client_name',             'name'],
        ['arp_clients',           'client_sap_code',         'sap_code'],
        ['arp_discounts',         'discount_name',           'name'],
        ['arp_goals',             'goal_name',               'name'],
        ['arp_goals',             'goal_quantity',           'quantity'],
        ['arp_goals',             'goal_value',              'value'],
        ['arp_goals',             'goal_quantity_com',       'quantity_com'],
        ['arp_goals',             'goal_value_com',          'value_com'],
        ['arp_goals',             'goal_quantity_ins',       'quantity_ins'],
        ['arp_goals',             'goal_value_ins',          'value_ins'],
        ['arp_projections',       'projection_name',         'name'],
        ['arp_projections',       'projection_description',  'description'],
        ['arp_projections',       'projection_file',         'file'],
        ['arp_simulations',       'simulation_name',         'name'],
        ['brands',                'brand_name',              'name'],
        ['client_files',          'file_name',               'name'],
        ['client_files',          'file_folder',             'folder'],
        ['client_types',          'type_name',               'name'],
        ['clients',               'client_name',             'name'],
        ['clients',               'client_quote_name',       'quote_name'],
        ['clients',               'client_nit',              'nit'],
        ['clients',               'client_sap_name',         'sap_name'],
        ['clients',               'client_sap_code',         'sap_code'],
        ['clients',               'client_contact',          'contact'],
        ['clients',               'client_phone',            'phone'],
        ['clients',               'client_email',            'email'],
        ['clients',               'client_credit',           'credit'],
        ['clients',               'client_address',          'address'],
        ['clients',               'client_position',         'position'],
        ['clients',               'client_area_code',        'area_code'],
        ['clients',               'client_email_secondary',  'email_secondary'],
        ['discount_levels',       'disc_level_name',         'name'],
        ['dist_channels',         'channel_name',            'name'],
        ['doc_format_types',      'format_name',             'name'],
        ['doc_repository',        'doc_name',                'name'],
        ['doc_status',            'status_name',             'name'],
        ['doc_status',            'status_color',            'color'],
        ['folder_repository',     'folder_name',             'name'],
        ['folder_repository',     'folder_url',              'url'],
        ['locations',             'loc_name',                'name'],
        ['locations',             'loc_codecity',            'codecity'],
        ['locations',             'loc_codedep',             'codedep'],
        ['negotiation_concepts',  'name_concept',            'name'],
        ['negotiation_docs',      'doc_name',                'name'],
        ['negotiation_docs',      'file_folder',             'folder'],
        ['negotiation_errors',    'negotiation_error',       'error'],
        ['negotiations',          'negotiation_consecutive', 'consecutive'],
        ['negotiations',          'negotiation_date_end',    'date_end'],
        ['negotiations',          'negotiation_date_ini',    'date_ini'],
        ['negotiations',          'negotiation_number',      'number'],
        ['payment_terms',         'payterm_name',            'name'],
        ['payment_terms',         'payterm_code',            'code'],
        ['payment_terms',         'payterm_percent',         'percent'],
        ['price_lists',           'list_name',               'name'],
        ['price_lists',           'list_version',            'version'],
        ['product_histories',     'prod_valid_date_end',     'valid_date_end'],
        ['product_histories',     'prod_valid_date_ini',     'valid_date_ini'],
        ['product_lines',         'line_name',               'name'],
        ['product_measure_units', 'unit_name',               'name'],
        ['product_prices',        'prod_increment_max',      'increment_max'],
        ['product_prices',        'prod_valid_date_end',     'valid_date_end'],
        ['product_prices',        'prod_valid_date_ini',     'valid_date_ini'],
        ['products',              'prod_name',               'name'],
        ['products',              'prod_sap_code',           'sap_code'],
        ['products',              'prod_commercial_name',    'commercial_name'],
        ['products',              'prod_commercial_unit',    'commercial_unit'],
        ['products',              'prod_concentration',      'concentration'],
        ['products',              'prod_cum',                'cum'],
        ['products',              'prod_generic_name',       'generic_name'],
        ['products',              'prod_increment_max',      'increment_max'],
        ['products',              'prod_insumo',             'insumo'],
        ['products',              'prod_invima_reg',         'invima_reg'],
        ['products',              'prod_obesidad',           'obesidad'],
        ['products',              'prod_package',            'package'],
        ['products',              'prod_package_unit',       'package_unit'],
        ['products',              'prod_valid_date_end',     'valid_date_end'],
        ['products',              'prod_valid_date_ini',     'valid_date_ini'],
        ['products',              'is_prod_regulated',       'is_regulated'],
        ['quotation_docs',        'doc_name',                'name'],
        ['quotation_docs',        'file_folder',             'folder'],
        ['quotations',            'quota_consecutive',       'consecutive'],
        ['quotations',            'quota_date_end',          'date_end'],
        ['quotations',            'quota_date_ini',          'date_ini'],
        ['quotations',            'quota_number',            'number'],
        ['quotations',            'quota_value',             'value'],
        ['repo_files',            'file_folder',             'folder'],
        ['repo_files',            'file_name',               'name'],
        ['sales',                 'doc_name',                'name'],
        ['status',                'status_name',             'name'],
        ['status',                'status_color',            'color'],
        ['status',                'status_symbol',           'symbol'],
    ];

    /**
     * Unique indexes that reference renamed columns. Dropped before the
     * rename and recreated afterwards with their legacy constraint name
     * (which keeps references to `information_schema` stable).
     *
     * Each entry: [table, constraintName, oldColumn, newColumn].
     *
     * @var array<int, array{0:string,1:string,2:string,3:string}>
     */
    private array $uniques = [
        ['clients',  'nvn_clients_client_sap_code_unique',  'client_sap_code', 'sap_code'],
        ['clients',  'nvn_clients_client_sap_name_unique',  'client_sap_name', 'sap_name'],
        ['products', 'nvn_products_prod_sap_code_unique',   'prod_sap_code',   'sap_code'],
    ];

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            foreach ($this->uniques as [$table, $name]) {
                $this->dropUniqueIfExists($table, $name);
            }

            foreach ($this->renames as [$table, $old, $new]) {
                $this->renameColumnIfExists($table, $old, $new);
            }

            foreach ($this->uniques as [$table, $name, , $new]) {
                $this->addUniqueIfMissing($table, $new, $name);
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            foreach ($this->uniques as [$table, $name]) {
                $this->dropUniqueIfExists($table, $name);
            }

            foreach ($this->renames as [$table, $old, $new]) {
                $this->renameColumnIfExists($table, $new, $old);
            }

            foreach ($this->uniques as [$table, $name, $old]) {
                $this->addUniqueIfMissing($table, $old, $name);
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    private function renameColumnIfExists(string $table, string $from, string $to): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $from) || Schema::hasColumn($table, $to)) {
            return;
        }

        Schema::table($table, function (Blueprint $t) use ($from, $to): void {
            $t->renameColumn($from, $to);
        });
    }

    private function dropUniqueIfExists(string $table, string $name): void
    {
        if (! $this->indexExists($table, $name)) {
            return;
        }

        Schema::table($table, function (Blueprint $t) use ($name): void {
            $t->dropUnique($name);
        });
    }

    private function addUniqueIfMissing(string $table, string $column, string $name): void
    {
        if (! Schema::hasColumn($table, $column) || $this->indexExists($table, $name)) {
            return;
        }

        Schema::table($table, function (Blueprint $t) use ($column, $name): void {
            $t->unique([$column], $name);
        });
    }

    private function indexExists(string $table, string $name): bool
    {
        return (bool) DB::selectOne(
            'SELECT 1 FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = ?
               AND INDEX_NAME = ?',
            [$table, $name]
        );
    }
};
