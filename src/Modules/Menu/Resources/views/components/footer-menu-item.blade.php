@props(['item', 'optimized' => false])

@php
    $url = $item->getFullUrl();
    $isActive = $item->isCurrentlyActive();
    $hasChildren = isset($item->children) && $item->children instanceof \Illuminate\Support\Collection && $item->children->isNotEmpty();
    $target = $item->opens_new_tab ? '_blank' : null;
    $title = $item->getDisplayTitle();
    $accessibleLabel = $item->description ?: $title;
@endphp

@switch($item->type)
    @case('divider')
        {{-- Dividers don't make sense in footer context, skip --}}
        @break

    @case('heading')
        <li>
            <span class="mx-2 text-sm font-semibold text-gray-600 uppercase dark:text-gray-400 md:mx-3">
                @if($item->icon)
                    <i class="{{ $item->icon }} mr-1" aria-hidden="true"></i>
                @endif
                {{ $title }}
            </span>
        </li>
        @break

    @case('dropdown')
        @if($hasChildren)
            <li>
                <a class="mx-2 hover:underline md:mx-3 {{ $isActive ? 'font-semibold' : '' }}"
                   href="{{ $url }}"
                   @if($target) target="{{ $target }}" @endif
                   @if($item->opens_new_tab && $url !== '#') wire:navigate.hover @endif
                   @if($item->opens_new_tab) aria-label="{{ $accessibleLabel }} (opens in new tab)"
                   @elseif(!$title && $accessibleLabel) aria-label="{{ $accessibleLabel }}"
                   @endif>
                    @if($item->icon)
                        <i class="{{ $item->icon }} @if($title) mr-1 @endif" aria-hidden="true"></i>
                    @endif
                    {{ $title }}
                    @if($item->opens_new_tab)
                        <span class="sr-only">(opens in new tab)</span>
                    @endif
                </a>
            </li>
            @foreach($item->children as $childItem)
                @if(($optimized || $childItem->userCanSee()) && $childItem->type !== 'divider')
                    @php
                        $childTitle = $childItem->getDisplayTitle();
                        $childAccessibleLabel = $childItem->description ?: $childTitle;
                    @endphp
                    <li>
                        <a class="mx-2 hover:underline md:mx-3 text-sm {{ $childItem->isCurrentlyActive() ? 'font-semibold' : '' }}"
                           href="{{ $childItem->getFullUrl() }}"
                           @if($childItem->opens_new_tab) target="_blank" @endif
                           @if(!$childItem->opens_new_tab && $childItem->getFullUrl() !== '#') wire:navigate.hover @endif
                           @if($childItem->opens_new_tab) aria-label="{{ $childAccessibleLabel }} (opens in new tab)"
                           @elseif(!$childTitle && $childAccessibleLabel) aria-label="{{ $childAccessibleLabel }}"
                           @endif>
                            @if($childItem->icon)
                                <i class="{{ $childItem->icon }} @if($childTitle) mr-1 @endif" aria-hidden="true"></i>
                            @endif
                            {{ $childTitle }}
                            @if($childItem->opens_new_tab)
                                <span class="sr-only">(opens in new tab)</span>
                            @endif
                        </a>
                    </li>
                @endif
            @endforeach
        @else
            <li>
                <a class="mx-2 hover:underline md:mx-3 {{ $isActive ? 'font-semibold' : '' }}"
                   href="{{ $url }}"
                   @if($target) target="{{ $target }}" @endif
                   @if(!$item->opens_new_tab && $url !== '#') wire:navigate.hover @endif
                   @if(!$title && $accessibleLabel) aria-label="{{ $accessibleLabel }}" @endif>
                    @if($item->icon)
                        <i class="{{ $item->icon }} @if($title) mr-1 @endif" aria-hidden="true"></i>
                    @endif
                    {{ $title }}
                </a>
            </li>
        @endif
        @break

    @case('external')
    @case('link')
    @default
        <li>
            <a class="mx-2 hover:underline md:mx-3 {{ $isActive ? 'font-semibold' : '' }}"
               href="{{ $url }}"
               @if($target) target="{{ $target }}" @endif
               @if(!$item->opens_new_tab && $url !== '#') wire:navigate.hover @endif
               @if($item->opens_new_tab) aria-label="{{ $accessibleLabel }} (opens in new tab)"
               @elseif(!$title && $accessibleLabel) aria-label="{{ $accessibleLabel }}"
               @endif>
                @if($item->icon)
                    <i class="{{ $item->icon }} @if($title) mr-1 @endif" aria-hidden="true"></i>
                @endif
                {{ $title }}
                @if($item->opens_new_tab)
                    <span class="sr-only">(opens in new tab)</span>
                @endif
            </a>
        </li>
        @break
@endswitch
