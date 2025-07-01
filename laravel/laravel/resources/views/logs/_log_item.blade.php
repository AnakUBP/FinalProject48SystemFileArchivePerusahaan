<tr>
    <td>{{ $log->created_at->format('d-m-Y H:i') }}</td>
    <td>{{ $log->user->name ?? '-' }}</td>
    <td>
        <span class="badge bg-{{ logColor($log->action) }}">
            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
        </span>
    </td>
    <td>{{ $log->description }}</td>
    <td>
        @if ($log->loggable)
            {{ class_basename($log->loggable_type) }} #{{ $log->loggable_id }}
        @else
            <em>tidak tersedia</em>
        @endif
    </td>
</tr>
