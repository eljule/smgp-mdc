<div class="space-y-6 pb-12" x-data="{ qrPreview: false }">
    <x-floating-toast :message="$flashMessage ?? $alertMessage ?? null" :type="$flashType ?? $alertType ?? 'success'" />

    {{-- ========================================================================= --}}
    {{-- PESTAÑAS PRINCIPALES DEL SUBSISTEMA ITAM                                  --}}
    {{-- ========================================================================= --}}
    @if ($activeTab === 'inventory')
        @include('livewire.itam.tabs.inventory')
    @elseif ($activeTab === 'movements')
        @include('livewire.itam.tabs.movements')
    @elseif ($activeTab === 'maintenance')
        @include('livewire.itam.tabs.maintenance')
    @elseif ($activeTab === 'supplies')
        @include('livewire.itam.tabs.supplies')
    @elseif ($activeTab === 'loans')
        @include('livewire.itam.tabs.loans')
    @elseif ($activeTab === 'audits')
        @include('livewire.itam.tabs.audits')
    @elseif ($activeTab === 'software')
        @include('livewire.itam.tabs.software')
    @elseif ($activeTab === 'categories')
        @include('livewire.itam.tabs.categories')
    @elseif ($activeTab === 'catalogs')
        @include('livewire.itam.tabs.catalogs')
    @elseif ($activeTab === 'metrics')
        @include('livewire.itam.tabs.metrics')
    @elseif ($activeTab === 'qr_lookup')
        @include('livewire.itam.tabs.qr-lookup')
    @endif

    {{-- ========================================================================= --}}
    {{-- MODALES DEL SUBSISTEMA ITAM                                               --}}
    {{-- ========================================================================= --}}
    @include('livewire.itam.modals.link-children-modal')
    @include('livewire.itam.modals.category-schema-modal')
    @include('livewire.itam.modals.asset-detail-modal')
    @include('livewire.itam.modals.asset-form-modal')
    @include('livewire.itam.modals.movement-modal')
    @include('livewire.itam.modals.maintenance-modal')
    @include('livewire.itam.modals.supply-modal')
    @include('livewire.itam.modals.loan-modal')
    @include('livewire.itam.modals.brand-model-modal')
    @include('livewire.itam.modals.official-act-modal')
    @include('livewire.itam.modals.audit-modal')
    @include('livewire.itam.modals.audit-reconciliation-modal')
    @include('livewire.itam.modals.software-modal')
    @include('livewire.itam.modals.category-form-modal')
</div>
