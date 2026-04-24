<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Foreign-key columns using the legacy `id_xxx` naming that must be
     * normalized to Laravel's `xxx_id` convention.
     *
     * Each entry: [table, oldColumn, newColumn, refTable, onDelete, legacyFkName].
     * The recreated FK uses Laravel's default naming: `{table}_{newColumn}_foreign`.
     *
     * @var array<int, array{0:string,1:string,2:string,3:string,4:string,5:string}>
     */
    private array $fks = [
        ['clients',                  'id_biof_contact',              'biof_contact_id',            'users',                 'restrict', 'nvn_clients_id_biof_contact_foreign'],
        ['clients',                  'id_city',                      'city_id',                    'locations',             '',         'nvn_clients_id_city_foreign'],
        ['clients',                  'id_client_channel',            'client_channel_id',          'dist_channels',         '',         'nvn_clients_id_client_channel_foreign'],
        ['clients',                  'id_client_type',               'client_type_id',             'client_types',          'cascade',  'nvn_clients_id_client_type_foreign'],
        ['clients',                  'id_department',                'department_id',              'locations',             '',         'nvn_clients_id_department_foreign'],
        ['clients',                  'id_diab_contact',              'diab_contact_id',            'users',                 'restrict', 'nvn_clients_id_diab_contact_foreign'],
        ['clients',                  'id_payterm',                   'payterm_id',                 'payment_terms',         '',         'nvn_clients_id_payterm_fkey'],
        ['client_files',             'id_client',                    'client_id',                  'clients',               'cascade',  'nvn_clients_files_id_client_foreign'],
        ['client_sap_codes',         'id_client',                    'client_id',                  'clients',               'cascade',  'nvn_clients_sap_codes_id_client_foreign'],
        ['credit_note_clients',      'id_credit_notes',              'credit_notes_id',            'credit_notes',          'cascade',  'nvn_credit_notes_clients_id_credit_notes_foreign'],
        ['credit_note_client_bills', 'id_credit_notes',              'credit_notes_id',            'credit_notes',          'cascade',  'nvn_credit_notes_clients_bills_id_credit_notes_foreign'],
        ['credit_note_details',      'id_credit_notes_clients',      'credit_notes_clients_id',    'credit_note_clients',   'cascade',  'nvn_credit_notes_details_id_credit_notes_clients_foreign'],
        ['credit_note_detail_bills', 'id_credit_notes_clients_b',    'credit_notes_clients_b_id',  'credit_note_client_bills', 'cascade', 'nvn_credit_notes_details_b_id_credit_notes_clients_b_foreign'],
        ['doc_formats',              'id_formattype',                'formattype_id',              'doc_format_types',      '',         'nvn_doc_formats_id_formattype_foreign'],
        ['doc_repository',           'id_folder',                    'folder_id',                  'folder_repository',     'cascade',  'nvn_doc_repository_id_folder_foreign'],
        ['format_certificates',      'id_formattype',                'formattype_id',              'doc_format_types',      'cascade',  'nvn_format_certificates_id_formattype_foreign'],
        ['negotiations',             'id_authorizer_user',           'authorizer_user_id',         'users',                 'restrict', 'nvn_negotiations_id_authorizer_user_foreign'],
        ['negotiations',             'id_channel',                   'channel_id',                 'dist_channels',         '',         'nvn_negotiations_id_channel_foreign'],
        ['negotiations',             'id_city',                      'city_id',                    'locations',             '',         'nvn_negotiations_id_city_foreign'],
        ['negotiations',             'id_client',                    'client_id',                  'clients',               'cascade',  'nvn_negotiations_id_client_foreign'],
        ['negotiation_comments',     'id_negotiation',               'negotiation_id',             'negotiations',          'cascade',  'nvn_negotiationxcomments_id_negotiation_foreign'],
        ['negotiation_details',      'id_client',                    'client_id',                  'clients',               'cascade',  'nvn_negotiations_details_id_client_foreign'],
        ['negotiation_details',      'id_negotiation',               'negotiation_id',             'negotiations',          'cascade',  'nvn_negotiations_details_id_negotiation_foreign'],
        ['negotiation_details',      'id_product',                   'product_id',                 'products',              '',         'nvn_negotiations_details_id_product_foreign'],
        ['negotiation_details',      'id_prod_auth_level',           'prod_auth_level_id',         'product_auth_levels',   '',         'nvn_negotiations_details_id_prod_auth_level_foreign'],
        ['negotiation_docs',         'id_negotiation',               'negotiation_id',             'negotiations',          'cascade',  'nvn_negotiationxdocs_id_negotiation_foreign'],
        ['negotiation_errors',       'id_negotiation_det',           'negotiation_det_id',         'negotiation_details',   'cascade',  'nvn_negotiations_errors_id_negotiation_det_foreign'],
        ['price_lists',              'id_authorizer_user',           'authorizer_user_id',         'users',                 'restrict', 'nvn_priceslists_id_authorizer_user_foreign'],
        ['products',                 'id_measure_unit',              'measure_unit_id',            'product_measure_units', '',         'nvn_products_id_measure_unit_foreign'],
        ['products',                 'id_prod_line',                 'prod_line_id',               'product_lines',         'cascade',  'nvn_products_id_prod_line_foreign'],
        ['product_auth_levels',      'id_dist_channel',              'dist_channel_id',            'dist_channels',         '',         'nvn_product_auth_levels_id_dist_channel_foreign'],
        ['product_auth_levels',      'id_level_discount',            'level_discount_id',          'discount_levels',       'cascade',  'nvn_product_auth_levels_id_level_discount_foreign'],
        ['product_auth_levels',      'id_pricelists',                'pricelists_id',              'price_lists',           'cascade',  'nvn_product_auth_levels_id_pricelists_fkey'],
        ['product_auth_levels',      'id_product',                   'product_id',                 'products',              'cascade',  'nvn_product_auth_levels_id_product_foreign'],
        ['product_client_scales',    'id_client',                    'client_id',                  'clients',               'cascade',  'nvn_productxclientxscales_id_client_foreign'],
        ['product_client_scales',    'id_product',                   'product_id',                 'products',              'cascade',  'nvn_productxclientxscales_id_product_foreign'],
        ['product_client_scales',    'id_scale',                     'scale_id',                   'product_scales',        'cascade',  'nvn_productxclientxscales_id_scale_foreign'],
        ['product_histories',        'id_product_h',                 'product_h_id',               'products',              'cascade',  'nvn_products_h_id_product_h_foreign'],
        ['product_prices',           'id_pricelists',                'pricelists_id',              'price_lists',           'cascade',  'nvn_productxprices_id_pricelists_foreign'],
        ['product_prices',           'id_product',                   'product_id',                 'products',              'cascade',  'nvn_productxprices_id_product_foreign'],
        ['product_sap_codes',        'id_product',                   'product_id',                 'products',              '',         'nvn_product_sapcodes_id_product_foreign'],
        ['product_scales',           'id_product',                   'product_id',                 'products',              'cascade',  'nvn_product_scales_id_product_foreign'],
        ['product_scale_levels',     'id_measure_unit',              'measure_unit_id',            'product_measure_units', '',         'nvn_product_scales_level_id_measure_unit_fkey'],
        ['product_scale_levels',     'id_scale',                     'scale_id',                   'product_scales',        'cascade',  'nvn_product_scales_level_id_scale_foreign'],
        ['quotations',               'id_authorizer_user',           'authorizer_user_id',         'users',                 'restrict', 'nvn_quotations_id_authorizer_user_foreign'],
        ['quotations',               'id_channel',                   'channel_id',                 'dist_channels',         '',         'nvn_quotations_id_channel_foreign'],
        ['quotations',               'id_city',                      'city_id',                    'locations',             '',         'nvn_quotations_id_city_foreign'],
        ['quotations',               'id_client',                    'client_id',                  'clients',               'cascade',  'nvn_quotations_id_client_foreign'],
        ['quotation_comments',       'id_quotation',                 'quotation_id',               'quotations',            'cascade',  'nvn_quotationxcomments_id_quotation_foreign'],
        ['quotation_details',        'id_client',                    'client_id',                  'clients',               'cascade',  'nvn_quotations_details_id_client_foreign'],
        ['quotation_details',        'id_payterm',                   'payterm_id',                 'payment_terms',         '',         'nvn_quotations_details_id_payterm_foreign'],
        ['quotation_details',        'id_product',                   'product_id',                 'products',              '',         'nvn_quotations_details_id_product_foreign'],
        ['quotation_details',        'id_prod_auth_level',           'prod_auth_level_id',         'product_auth_levels',   '',         'nvn_quotations_details_id_prod_auth_level_foreign'],
        ['quotation_details',        'id_quotation',                 'quotation_id',               'quotations',            'cascade',  'nvn_quotations_details_id_quotation_foreign'],
        ['quotation_docs',           'id_quotation',                 'quotation_id',               'quotations',            'cascade',  'nvn_quotationxdocs_id_quotation_foreign'],
        ['sale_details',             'id_sales',                     'sales_id',                   'sales',                 'cascade',  'nvn_sales_details_id_sales_foreign'],
    ];

    /**
     * Plain (non-FK) legacy columns that should also be normalized for consistency.
     *
     * @var array<int, array{0:string,1:string,2:string}>
     */
    private array $plainRenames = [
        ['arp_sales',           'id_sales_list', 'sales_list_id'],
        ['products',            'id_brand',      'brand_id'],
        ['negotiation_details', 'id_quotation',  'quotation_id'],
        ['negotiation_details', 'id_scale',      'scale_id'],
        ['negotiation_details', 'id_concept',    'concept_id'],
    ];

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            foreach ($this->fks as [$table, , , , , $legacyName]) {
                $this->dropForeignIfExists($table, $legacyName);
            }

            foreach ($this->fks as [$table, $old, $new]) {
                $this->renameColumnIfExists($table, $old, $new);
            }

            foreach ($this->plainRenames as [$table, $old, $new]) {
                $this->renameColumnIfExists($table, $old, $new);
            }

            foreach ($this->fks as [$table, , $new, $refTable, $onDelete]) {
                $this->addForeignIfMissing($table, $new, $refTable, $onDelete);
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            foreach ($this->fks as [$table, , $new]) {
                $this->dropForeignIfExists($table, $this->defaultFkName($table, $new));
            }

            foreach ($this->fks as [$table, $old, $new]) {
                $this->renameColumnIfExists($table, $new, $old);
            }

            foreach ($this->plainRenames as [$table, $old, $new]) {
                $this->renameColumnIfExists($table, $new, $old);
            }

            foreach ($this->fks as [$table, $old, , $refTable, $onDelete, $legacyName]) {
                $this->addForeignIfMissing($table, $old, $refTable, $onDelete, $legacyName);
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    private function defaultFkName(string $table, string $column): string
    {
        return $table.'_'.$column.'_foreign';
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

    private function addForeignIfMissing(string $table, string $column, string $refTable, string $onDelete, ?string $name = null): void
    {
        $constraintName = $name ?? $this->defaultFkName($table, $column);

        if ($this->foreignKeyExists($table, $constraintName)) {
            return;
        }

        Schema::table($table, function (Blueprint $t) use ($column, $refTable, $onDelete, $name): void {
            $fk = $name === null
                ? $t->foreign($column)->references('id')->on($refTable)
                : $t->foreign($column, $name)->references('id')->on($refTable);

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
