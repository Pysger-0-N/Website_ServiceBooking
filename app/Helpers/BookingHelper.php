<?php

if (!function_exists('statusBadge')) {
    function statusBadge(string $status): string
    {
        return match($status) {
            'pending'     => '<span class="badge bg-warning text-dark">Menunggu</span>',
            'confirmed'   => '<span class="badge bg-primary">Dikonfirmasi</span>',
            'on_progress' => '<span class="badge bg-info">Berlangsung</span>',
            'done'        => '<span class="badge bg-success">Selesai</span>',
            'cancelled'   => '<span class="badge bg-danger">Dibatalkan</span>',
            default       => '<span class="badge bg-secondary">' . $status . '</span>',
        };
    }
}
