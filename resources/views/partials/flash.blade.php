@php
    $types = [
        'success' => ['icon' => 'check-circle', 'class' => 'flash-toast--success'],
        'error'   => ['icon' => 'x-circle',     'class' => 'flash-toast--error'],
        'warning' => ['icon' => 'alert-triangle','class' => 'flash-toast--warning'],
        'info'    => ['icon' => 'info',          'class' => 'flash-toast--info'],
    ];
@endphp

@foreach($types as $key => $config)
    @if(session($key))
        <div class="flash-toast {{ $config['class'] }}" role="alert" data-auto-dismiss>
            <i data-lucide="{{ $config['icon'] }}" class="flash-toast__icon"></i>
            <span class="flash-toast__msg">{{ session($key) }}</span>
            <button class="flash-toast__close" aria-label="Dismiss">
                <i data-lucide="x"></i>
            </button>
        </div>
    @endif
@endforeach
