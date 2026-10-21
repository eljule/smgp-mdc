<div class="space-y-6 pb-12" x-data="{ qrScanner: false }">
    <x-floating-toast :message="$flashMessage ?? $alertMessage ?? null" :type="$flashType ?? $alertType ?? 'success'" />

    {{-- ========================================================================= --}}
    {{-- PESTAÑAS PRINCIPALES DEL SUBSISTEMA HELPDESK                             --}}
    {{-- ========================================================================= --}}
    @if ($activeTab === 'tickets')
        @include('livewire.helpdesk.tabs.tickets')
    @elseif ($activeTab === 'my_tickets')
        @include('livewire.helpdesk.tabs.my-tickets')
    @elseif ($activeTab === 'workload')
        @include('livewire.helpdesk.tabs.workload')
    @elseif ($activeTab === 'knowledge')
        @include('livewire.helpdesk.tabs.knowledge')
    @elseif ($activeTab === 'qr_scanner')
        @include('livewire.helpdesk.tabs.qr-scanner')
    @elseif ($activeTab === 'metrics')
        @include('livewire.helpdesk.tabs.metrics')
    @endif

    {{-- ========================================================================= --}}
    {{-- MODALES DEL SUBSISTEMA HELPDESK                                          --}}
    {{-- ========================================================================= --}}
    @include('livewire.helpdesk.modals.create-ticket-modal')
    @include('livewire.helpdesk.modals.ticket-detail-modal')
    @include('livewire.helpdesk.modals.ticket-pause-modal')
    @include('livewire.helpdesk.modals.ticket-resolution-modal')
    @include('livewire.helpdesk.modals.ticket-supplies-modal')
    @include('livewire.helpdesk.modals.ticket-provisional-loan-modal')
    @include('livewire.helpdesk.modals.ticket-conformity-modal')
    @include('livewire.helpdesk.modals.ticket-reassign-modal')
    @include('livewire.helpdesk.modals.ticket-bulk-reassign-modal')
    @include('livewire.helpdesk.modals.ticket-decommission-act-modal')
    @include('livewire.helpdesk.modals.knowledge-modal')
</div>
