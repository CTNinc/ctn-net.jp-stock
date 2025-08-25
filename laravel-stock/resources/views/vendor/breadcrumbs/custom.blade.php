@if (count($breadcrumbs))
    <nav aria-label="breadcrumb" class="breadcrumb">
        <div class="inner">
        <ol class="flex list-reset space-x-2">
            @foreach ($breadcrumbs as $breadcrumb)
                @if (!is_null($breadcrumb->url) && !$loop->last)
                    <li>
                        <a href="{{ $breadcrumb->url }}" class="text-blue-600 hover:underline">
                            {{ $breadcrumb->title }}
                        </a>
                        <span>＞</span>
                    </li>
                @else
                    <li class="text-gray-500">{{ $breadcrumb->title }}</li>
                @endif
            @endforeach
        </ol>
        </div>
    </nav>
@endif
