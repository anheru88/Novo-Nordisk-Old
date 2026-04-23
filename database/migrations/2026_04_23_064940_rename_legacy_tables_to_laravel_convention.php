<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Map legacy/cryptic table names to Laravel-convention names.
     * Applied as RENAME TABLE statements (FKs remain intact on MariaDB).
     */
    private array $renames = [
        // Typos and run-together names
        'aditional_terms'         => 'additional_terms',
        'priceslists'             => 'price_lists',
        // Cryptic shorthands
        'products_h'              => 'product_histories',
        'credit_notes_details_b'  => 'credit_notes_details_bills',
        'product_scales_level'    => 'product_scale_levels',
        // Legacy "x" bridge tables
        'productxclientxscales'   => 'product_client_scales',
        'productxprices'          => 'product_prices',
        'quotationxcomments'      => 'quotation_comments',
        'quotationxdocs'          => 'quotation_docs',
        'quotationxstatus'        => 'quotation_status_changes',
        'negotiationxcomments'    => 'negotiation_comments',
        'negotiationxdocs'        => 'negotiation_docs',
        'negotiationxapprovers'   => 'negotiation_approvers',
        'negotiationxstatus'      => 'negotiation_status_changes',
        // Plural-owner → singular-owner convention
        'negotiations_details'    => 'negotiation_details',
        'negotiations_errors'     => 'negotiation_errors',
        'quotations_details'      => 'quotation_details',
        'arp_simulations_details' => 'arp_simulation_details',
        'clients_files'           => 'client_files',
        'clients_sap_codes'       => 'client_sap_codes',
        'sales_details'           => 'sale_details',
        'credit_notes_clients'        => 'credit_note_clients',
        'credit_notes_clients_bills'  => 'credit_note_client_bills',
        'credit_notes_details'        => 'credit_note_details',
        'credit_notes_details_bills'  => 'credit_note_detail_bills',
        // Pattern alignment
        'services_arp' => 'arp_services',
        'arp_service'  => 'arp_service_details',
    ];

    public function up(): void
    {
        foreach ($this->renames as $from => $to) {
            if (Schema::hasTable($from) && !Schema::hasTable($to)) {
                Schema::rename($from, $to);
            }
        }
    }

    public function down(): void
    {
        foreach (array_reverse($this->renames) as $from => $to) {
            if (Schema::hasTable($to) && !Schema::hasTable($from)) {
                Schema::rename($to, $from);
            }
        }
    }
};
