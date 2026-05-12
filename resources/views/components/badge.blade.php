@props(['status' => ''])

@php
    $map = [
        'pending' => 'bg-amber-100 text-amber-800 ring-amber-600/20',
        'waiting_payment' => 'bg-orange-100 text-orange-800 ring-orange-600/20',
        'paid' => 'bg-emerald-100 text-emerald-800 ring-emerald-600/20',
        'processing' => 'bg-sky-100 text-sky-800 ring-sky-600/20',
        'shipped' => 'bg-indigo-100 text-indigo-800 ring-indigo-600/20',
        'completed' => 'bg-green-100 text-green-800 ring-green-600/20',
        'cancelled' => 'bg-red-100 text-red-800 ring-red-600/20',
        'refunded' => 'bg-zinc-100 text-zinc-700 ring-zinc-600/20',
        'unpaid' => 'bg-amber-100 text-amber-800',
        'failed' => 'bg-red-100 text-red-800',
        'refund' => 'bg-zinc-100 text-zinc-700',
    ];
    $cls = $map[$status] ?? 'bg-accent-100 text-accent-800 ring-accent-600/10';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {$cls}"]) }}>
    {{ str_replace('_', ' ', $status) ?: '—' }}
</span>
