<div class="space-y-6 pb-12">
    <x-floating-toast :message="$flashMessage ?? $alertMessage ?? null" :type="$flashType ?? $alertType ?? 'success'" />

    {{-- ======================================================================== --}}
    {{-- PESTA??AS PRINCIPALES DEL SUBSISTEMA CENTRAL                               --}}
    {{-- ======================================================================== --}}
    @if ($activeTab === 'dashboard' || $activeTab === 'overview')
        @include('livewire.admin.tabs.overview')
    @elseif ($activeTab === 'people')
        @include('livewire.admin.tabs.people')
    @elseif ($activeTab === 'users')
        @include('livewire.admin.tabs.users')
    @elseif ($activeTab === 'roles')
        @include('livewire.admin.tabs.roles')
    @elseif ($activeTab === 'permissions')
        @include('livewire.admin.tabs.permissions')
    @elseif ($activeTab === 'matrix' || $activeTab === 'assignment')
        @include('livewire.admin.tabs.matrix')
    @elseif ($activeTab === 'offices')
        @include('livewire.admin.tabs.offices')
    @elseif ($activeTab === 'subsystems')
        @include('livewire.admin.tabs.subsystems')
    @endif

    {{-- ======================================================================= --}}
    {{-- MODALES DEL SUBSISTEMA CENTRAL                                         --}}
    {{-- ======================================================================= --}}
    @include('livewire.admin.modals.person-form-modal')
    @include('livewire.admin.modals.person-cease-modal')
    @include('livewire.admin.modals.person-cease-detail-modal')
    @include('livewire.admin.modals.user-form-modal')
    @include('livewire.admin.modals.user-permissions-modal')
    @include('livewire.admin.modals.role-form-modal')
    @include('livewire.admin.modals.office-form-modal')
    @include('livewire.admin.modals.sedes-modal')
    @include('livewire.admin.modals.office-qr-modal')
    @include('livewire.admin.modals.subsystem-form-modal')
</div>