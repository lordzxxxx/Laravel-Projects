@php
    $tenant = \App\Models\Tenant::current();
    $biz = $tenant?->businessStatusParts();
@endphp

@if($biz)
<div class="business-status-card">
    <div class="business-status-card__head">
        <h3 class="business-status-card__title">
            <i class="fas fa-clipboard-check" aria-hidden="true"></i>
            Business status
        </h3>
    </div>
    <div class="business-status-card__body tone-{{ $biz['tone'] }}">
        <p><strong>Registration:</strong> {{ $biz['registration'] }}</p>
        <p><strong>Billing:</strong> {{ $biz['billing'] }}</p>
    </div>
</div>
@endif

<style>
.business-status-card {
    background: white;
    border-radius: 8px;
    padding: 16px 20px;
    border: 2px solid #e8f5e9;
    margin-bottom: 20px;
}
.business-status-card__title {
    color: #1b5e20;
    font-size: 1.05rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.business-status-card__body {
    margin-top: 12px;
    font-size: 0.92rem;
    color: #374151;
    line-height: 1.5;
}
.business-status-card__body p { margin: 0 0 6px; }
.business-status-card__body.tone-success { border-left: 3px solid #10b981; padding-left: 10px; }
.business-status-card__body.tone-warning { border-left: 3px solid #f59e0b; padding-left: 10px; }
.business-status-card__body.tone-danger { border-left: 3px solid #ef4444; padding-left: 10px; }
</style>
