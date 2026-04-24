<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Map of tables whose auto-increment primary key uses a non-standard name
     * and the legacy column name that must be renamed to `id`.
     *
     * @var array<string, string>
     */
    private array $pkRenames = [
        'additional_terms' => 'id_use',
        'arp_clients' => 'id_client',
        'arp_discounts' => 'id_discount',
        'arp_goals' => 'id_goal',
        'arp_products' => 'id_product',
        'arp_projections' => 'id_projection',
        'arp_sales' => 'id_sale',
        'arp_sales_list' => 'id_sales_list',
        'brands' => 'id_brand',
        'client_files' => 'id_files',
        'client_sap_codes' => 'id_sap_code',
        'client_types' => 'id_type',
        'clients' => 'id_client',
        'credit_note_client_bills' => 'id_credit_notes_clients_b',
        'credit_note_clients' => 'id_credit_notes_clients',
        'credit_note_detail_bills' => 'id_credit_notes_details_b',
        'credit_note_details' => 'id_credit_notes_details',
        'credit_notes' => 'id_credit_notes',
        'discount_levels' => 'id_disc_level',
        'dist_channels' => 'id_channel',
        'doc_format_types' => 'id_formattype',
        'doc_formats' => 'id_format',
        'doc_repository' => 'id_doc',
        'doc_status' => 'id_status',
        'folder_repository' => 'id_folder',
        'locations' => 'id_locations',
        'negotiation_comments' => 'id_negotiationxcomments',
        'negotiation_concepts' => 'id_negotiation_concepts',
        'negotiation_details' => 'id_negotiation_det',
        'negotiation_docs' => 'id_negotiationxdocs',
        'negotiation_errors' => 'id_negotiations_errors',
        'negotiations' => 'id_negotiation',
        'payment_terms' => 'id_payterms',
        'price_lists' => 'id_pricelists',
        'product_auth_levels' => 'id_level',
        'product_client_scales' => 'id_productxclient',
        'product_histories' => 'id_product',
        'product_lines' => 'id_line',
        'product_measure_units' => 'id_unit',
        'product_prices' => 'id_productxprices',
        'product_sap_codes' => 'id_product_sapcode',
        'product_scale_levels' => 'id_scale_level',
        'product_scales' => 'id_scale',
        'products' => 'id_product',
        'quotation_comments' => 'id_quotationxcomments',
        'quotation_details' => 'id_quota_det',
        'quotation_docs' => 'id_quotationxdoc',
        'quotations' => 'id_quotation',
        'sale_details' => 'id_sale_details',
        'sales' => 'id_sales',
        'status' => 'status_id',
    ];

    /**
     * Every FK that points at a renamed PK column. Each entry is:
     * [referencing_table, referencing_column, referenced_table, old_ref_column, on_delete, constraint_name].
     *
     * @var array<int, array{0:string,1:string,2:string,3:string,4:string,5:string}>
     */
    private array $fks = [
        ['arp_business_case',        'brand_id',                  'brands',                   'id_brand',                  'restrict', 'nvn_arp_business_case_brand_id_foreign'],
        ['arp_service_details',      'brand_id',                  'brands',                   'id_brand',                  'restrict', 'nvn_arp_service_brand_id_foreign'],
        ['arp_simulation_details',   'brand_id',                  'brands',                   'id_brand',                  'cascade',  'nvn_arp_simulations_details_brand_id_foreign'],
        ['arp_simulation_details',   'client_id',                 'clients',                  'id_client',                 'cascade',  'nvn_arp_simulations_details_client_id_foreign'],
        ['arp_simulation_details',   'product_id',                'products',                 'id_product',                'cascade',  'nvn_arp_simulations_details_product_id_foreign'],
        ['client_files',             'id_client',                 'clients',                  'id_client',                 'cascade',  'nvn_clients_files_id_client_foreign'],
        ['client_sap_codes',         'id_client',                 'clients',                  'id_client',                 'cascade',  'nvn_clients_sap_codes_id_client_foreign'],
        ['clients',                  'id_city',                   'locations',                'id_locations',              'restrict', 'nvn_clients_id_city_foreign'],
        ['clients',                  'id_client_channel',         'dist_channels',            'id_channel',                'restrict', 'nvn_clients_id_client_channel_foreign'],
        ['clients',                  'id_client_type',            'client_types',             'id_type',                   'cascade',  'nvn_clients_id_client_type_foreign'],
        ['clients',                  'id_department',             'locations',                'id_locations',              'restrict', 'nvn_clients_id_department_foreign'],
        ['clients',                  'id_payterm',                'payment_terms',            'id_payterms',               'restrict', 'nvn_clients_id_payterm_fkey'],
        ['credit_note_client_bills', 'id_credit_notes',           'credit_notes',             'id_credit_notes',           'cascade',  'nvn_credit_notes_clients_bills_id_credit_notes_foreign'],
        ['credit_note_clients',      'id_credit_notes',           'credit_notes',             'id_credit_notes',           'cascade',  'nvn_credit_notes_clients_id_credit_notes_foreign'],
        ['credit_note_detail_bills', 'id_credit_notes_clients_b', 'credit_note_client_bills', 'id_credit_notes_clients_b', 'cascade',  'nvn_credit_notes_details_b_id_credit_notes_clients_b_foreign'],
        ['credit_note_details',      'id_credit_notes_clients',   'credit_note_clients',      'id_credit_notes_clients',   'cascade',  'nvn_credit_notes_details_id_credit_notes_clients_foreign'],
        ['doc_formats',              'id_formattype',             'doc_format_types',         'id_formattype',             'restrict', 'nvn_doc_formats_id_formattype_foreign'],
        ['doc_repository',           'id_folder',                 'folder_repository',        'id_folder',                 'cascade',  'nvn_doc_repository_id_folder_foreign'],
        ['format_certificates',      'id_formattype',             'doc_format_types',         'id_formattype',             'cascade',  'nvn_format_certificates_id_formattype_foreign'],
        ['negotiation_approvers',    'negotiation_id',            'negotiations',             'id_negotiation',            'cascade',  'nvn_negotiationxapprovers_negotiation_id_foreign'],
        ['negotiation_comments',     'id_negotiation',            'negotiations',             'id_negotiation',            'cascade',  'nvn_negotiationxcomments_id_negotiation_foreign'],
        ['negotiation_details',      'id_client',                 'clients',                  'id_client',                 'cascade',  'nvn_negotiations_details_id_client_foreign'],
        ['negotiation_details',      'id_negotiation',            'negotiations',             'id_negotiation',            'cascade',  'nvn_negotiations_details_id_negotiation_foreign'],
        ['negotiation_details',      'id_prod_auth_level',        'product_auth_levels',      'id_level',                  'restrict', 'nvn_negotiations_details_id_prod_auth_level_foreign'],
        ['negotiation_details',      'id_product',                'products',                 'id_product',                'restrict', 'nvn_negotiations_details_id_product_foreign'],
        ['negotiation_docs',         'id_negotiation',            'negotiations',             'id_negotiation',            'cascade',  'nvn_negotiationxdocs_id_negotiation_foreign'],
        ['negotiation_errors',       'id_negotiation_det',        'negotiation_details',      'id_negotiation_det',        'cascade',  'nvn_negotiations_errors_id_negotiation_det_foreign'],
        ['negotiation_status_changes', 'negotiation_id',          'negotiations',             'id_negotiation',            'cascade',  'nvn_negotiationxstatus_negotiation_id_foreign'],
        ['negotiation_status_changes', 'status_id',               'status',                   'status_id',                 'cascade',  'nvn_negotiationxstatus_status_id_foreign'],
        ['negotiations',             'id_channel',                'dist_channels',            'id_channel',                'restrict', 'nvn_negotiations_id_channel_foreign'],
        ['negotiations',             'id_city',                   'locations',                'id_locations',              'restrict', 'nvn_negotiations_id_city_foreign'],
        ['negotiations',             'id_client',                 'clients',                  'id_client',                 'cascade',  'nvn_negotiations_id_client_foreign'],
        ['product_auth_levels',      'id_dist_channel',           'dist_channels',            'id_channel',                'restrict', 'nvn_product_auth_levels_id_dist_channel_foreign'],
        ['product_auth_levels',      'id_level_discount',         'discount_levels',          'id_disc_level',             'cascade',  'nvn_product_auth_levels_id_level_discount_foreign'],
        ['product_auth_levels',      'id_pricelists',             'price_lists',              'id_pricelists',             'cascade',  'nvn_product_auth_levels_id_pricelists_fkey'],
        ['product_auth_levels',      'id_product',                'products',                 'id_product',                'cascade',  'nvn_product_auth_levels_id_product_foreign'],
        ['product_client_scales',    'id_client',                 'clients',                  'id_client',                 'cascade',  'nvn_productxclientxscales_id_client_foreign'],
        ['product_client_scales',    'id_product',                'products',                 'id_product',                'cascade',  'nvn_productxclientxscales_id_product_foreign'],
        ['product_client_scales',    'id_scale',                  'product_scales',           'id_scale',                  'cascade',  'nvn_productxclientxscales_id_scale_foreign'],
        ['product_histories',        'id_product_h',              'products',                 'id_product',                'cascade',  'nvn_products_h_id_product_h_foreign'],
        ['product_prices',           'id_pricelists',             'price_lists',              'id_pricelists',             'cascade',  'nvn_productxprices_id_pricelists_foreign'],
        ['product_prices',           'id_product',                'products',                 'id_product',                'cascade',  'nvn_productxprices_id_product_foreign'],
        ['product_sap_codes',        'id_product',                'products',                 'id_product',                'restrict', 'nvn_product_sapcodes_id_product_foreign'],
        ['product_scale_levels',     'id_measure_unit',           'product_measure_units',    'id_unit',                   'restrict', 'nvn_product_scales_level_id_measure_unit_fkey'],
        ['product_scale_levels',     'id_scale',                  'product_scales',           'id_scale',                  'cascade',  'nvn_product_scales_level_id_scale_foreign'],
        ['product_scales',           'id_product',                'products',                 'id_product',                'cascade',  'nvn_product_scales_id_product_foreign'],
        ['products',                 'id_measure_unit',           'product_measure_units',    'id_unit',                   'restrict', 'nvn_products_id_measure_unit_foreign'],
        ['products',                 'id_prod_line',              'product_lines',            'id_line',                   'cascade',  'nvn_products_id_prod_line_foreign'],
        ['quotation_approvers',      'quotation_id',              'quotations',               'id_quotation',              'cascade',  'nvn_quotation_approvers_quotation_id_foreign'],
        ['quotation_comments',       'id_quotation',              'quotations',               'id_quotation',              'cascade',  'nvn_quotationxcomments_id_quotation_foreign'],
        ['quotation_details',        'id_client',                 'clients',                  'id_client',                 'cascade',  'nvn_quotations_details_id_client_foreign'],
        ['quotation_details',        'id_payterm',                'payment_terms',            'id_payterms',               'restrict', 'nvn_quotations_details_id_payterm_foreign'],
        ['quotation_details',        'id_prod_auth_level',        'product_auth_levels',      'id_level',                  'restrict', 'nvn_quotations_details_id_prod_auth_level_foreign'],
        ['quotation_details',        'id_product',                'products',                 'id_product',                'restrict', 'nvn_quotations_details_id_product_foreign'],
        ['quotation_details',        'id_quotation',              'quotations',               'id_quotation',              'cascade',  'nvn_quotations_details_id_quotation_foreign'],
        ['quotation_docs',           'id_quotation',              'quotations',               'id_quotation',              'cascade',  'nvn_quotationxdocs_id_quotation_foreign'],
        ['quotation_status_changes', 'quotation_id',              'quotations',               'id_quotation',              'restrict', 'nvn_quotationxstatus_quotation_id_foreign'],
        ['quotation_status_changes', 'status_id',                 'status',                   'status_id',                 'restrict', 'nvn_quotationxstatus_status_id_foreign'],
        ['quotations',               'id_channel',                'dist_channels',            'id_channel',                'restrict', 'nvn_quotations_id_channel_foreign'],
        ['quotations',               'id_city',                   'locations',                'id_locations',              'restrict', 'nvn_quotations_id_city_foreign'],
        ['quotations',               'id_client',                 'clients',                  'id_client',                 'cascade',  'nvn_quotations_id_client_foreign'],
        ['sale_details',             'id_sales',                  'sales',                    'id_sales',                  'cascade',  'nvn_sales_details_id_sales_foreign'],
    ];

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            foreach ($this->fks as [$table, , , , , $name]) {
                $this->dropForeignIfExists($table, $name);
            }

            foreach ($this->pkRenames as $table => $oldColumn) {
                $this->renameColumnIfExists($table, $oldColumn, 'id');
            }

            foreach ($this->fks as [$table, $column, $refTable, , $onDelete, $name]) {
                $this->addForeignIfMissing($table, $column, $refTable, 'id', $onDelete, $name);
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            foreach ($this->fks as [$table, , , , , $name]) {
                $this->dropForeignIfExists($table, $name);
            }

            foreach ($this->pkRenames as $table => $oldColumn) {
                $this->renameColumnIfExists($table, 'id', $oldColumn);
            }

            foreach ($this->fks as [$table, $column, $refTable, $oldRefColumn, $onDelete, $name]) {
                $this->addForeignIfMissing($table, $column, $refTable, $oldRefColumn, $onDelete, $name);
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    private function dropForeignIfExists(string $table, string $name): void
    {
        if (! $this->foreignKeyExists($table, $name)) {
            return;
        }

        Schema::table($table, function (Blueprint $t) use ($name): void {
            $t->dropForeign($name);
        });
    }

    private function renameColumnIfExists(string $table, string $from, string $to): void
    {
        if (! Schema::hasColumn($table, $from) || Schema::hasColumn($table, $to)) {
            return;
        }

        Schema::table($table, function (Blueprint $t) use ($from, $to): void {
            $t->renameColumn($from, $to);
        });
    }

    private function addForeignIfMissing(string $table, string $column, string $refTable, string $refColumn, string $onDelete, string $name): void
    {
        if ($this->foreignKeyExists($table, $name)) {
            return;
        }

        Schema::table($table, function (Blueprint $t) use ($column, $refTable, $refColumn, $onDelete, $name): void {
            $fk = $t->foreign($column, $name)->references($refColumn)->on($refTable);
            match ($onDelete) {
                'cascade' => $fk->cascadeOnDelete(),
                'restrict' => $fk->restrictOnDelete(),
                default => null,
            };
        });
    }

    private function foreignKeyExists(string $table, string $name): bool
    {
        return (bool) DB::selectOne(
            'SELECT 1 FROM information_schema.TABLE_CONSTRAINTS
             WHERE CONSTRAINT_SCHEMA = DATABASE()
               AND TABLE_NAME = ?
               AND CONSTRAINT_NAME = ?
               AND CONSTRAINT_TYPE = ?',
            [$table, $name, 'FOREIGN KEY']
        );
    }
};
