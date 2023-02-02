<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
@foreach (\App\Helpers\AdminMenuGenerator::items() as $item)
    <li class="nav-item">
        <a class="nav-link" href="{{ $item['route'] }}">
            <i class="{{ $item['icon'] }}"></i> {{ $item['label'] }}
        </a>
    </li>
@endforeach
