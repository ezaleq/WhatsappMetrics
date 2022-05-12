@props([
    "href" => "/"
])

<li class="nav-item">
    <a class="nav-link {{ Request::is($href) ? 'active' : '' }}" aria-current="page" href="{{ $href }}">{{ $slot }}</a>
</li>