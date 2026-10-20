@props(['item', 'optimized' => false])

@php
    $url = $item->getFullUrl();
    $isActive = $item->isCurrentlyActive();
    // Use optimized children check that doesn't trigger database queries
    $hasChildren = isset($item->children) && $item->children instanceof \Illuminate\Support\Collection && $item->children->isNotEmpty();
    $target = $item->opens_new_tab ? '_blank' : null;
    $htmlAttributes = $item->html_attributes ?? [];

    $baseCssClasses = '';

    $attributes = array_merge($htmlAttributes, [
        'class' => $baseCssClasses
    ]);

    if ($target) {
        $attributes['target'] = $target;
    }
@endphp

@switch($item->type)
    @case('divider')
        <li class="border-t border-gray-200 dark:border-gray-600 my-1"></li>
        @break

    @case('heading')
        <li>
            <span class="block px-4 py-2 text-sm text-red-600 dark:text-gray-400">
                {{ $item->getDisplayTitle() }}
            </span>
        </li>
        @break

    @case('dropdown')
        @if($hasChildren)
            {{--
                Click to open/close on both desktop and mobile.
                Mobile: panel flows inline below the button.
                Desktop: panel floats absolutely below the nav item.
            --}}
            <li class="relative"
                x-data="{ open: false }"
            >
                <button
                    type="button"
                    @click="open = !open"
                    :aria-expanded="open.toString()"
                    aria-haspopup="true"
                    aria-label="Toggle {{ $item->getDisplayTitle() }} submenu"
                    class="border-transparent dark:border-transparent cursor-pointer flex items-center justify-between w-full md:w-auto border-b-2 px-3 py-2 font-semibold text-gray-800 transition duration-200 ease-in hover:border-gray-700 hover:opacity-75 dark:text-white dark:hover:border-gray-300 dark:hover:opacity-75 sm:my-0 sm:py-1"
                >
                    <span>{{ $item->getDisplayTitle() }}</span>
                    {{-- Chevron rotates 180° on mobile when open (accordion cue); stays flat on desktop --}}
                    <svg
                        class="w-6 h-6 ml-2.5 flex-shrink-0 transition-transform duration-200"
                        :class="open ? 'rotate-180 md:rotate-0' : ''"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 10 4 4 4-4"/>
                    </svg>
                </button>

                {{--
                    Level 2 panel
                    Mobile:  w-full, inline flow (no absolute), appears below the button
                    Desktop: absolute, floats below the nav item
                --}}
                <div
                    x-show="open"
                    x-cloak
                    @click.outside="open = false"
                    class="w-full mt-1 md:absolute md:top-full md:left-0 md:mt-1 md:z-10 md:w-56 bg-white dark:bg-gray-700 rounded-lg shadow-sm border border-gray-100 dark:border-gray-600"
                    role="menu"
                    aria-label="{{ $item->getDisplayTitle() }} submenu"
                >
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-400" role="none">
                        @foreach(($item->children ?? collect()) as $childItem)
                            {{-- Skip permission check if optimized mode (already filtered by parent) --}}
                            @if($optimized || $childItem->userCanSee())
                                @php
                                    $hasGrandchildren = isset($childItem->children) && $childItem->children instanceof \Illuminate\Support\Collection && $childItem->children->isNotEmpty();
                                @endphp

                                @if($childItem->type === 'divider')
                                    <li class="border-t border-gray-200 dark:border-gray-600 mx-2 my-1" role="separator"></li>
                                @elseif($childItem->type === 'heading')
                                    <li role="presentation">
                                        <span class="block px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide dark:text-gray-400">
                                            {{ $childItem->getDisplayTitle() }}
                                        </span>
                                    </li>
                                @elseif($hasGrandchildren)
                                    {{--
                                        Level 2 item that has level 3 children.
                                        Desktop: flyout opens to the right on click.
                                        Mobile:  accordion opens below, indented.
                                    --}}
                                    <li class="relative"
                                        x-data="{ subopen: false }"
                                        role="none"
                                    >
                                        <button
                                            type="button"
                                            @click.stop="subopen = !subopen"
                                            class="flex items-center justify-between w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white {{ $childItem->isCurrentlyActive() ? 'text-blue-700 dark:text-blue-500' : '' }}"
                                            aria-haspopup="true"
                                            aria-label="{{ $childItem->getDisplayTitle() }} submenu"
                                        >
                                            <span>{{ $childItem->getDisplayTitle() }}</span>
                                            {{--
                                                Chevron-right icon:
                                                Mobile open:   rotates 90° → points down (accordion cue)
                                                Desktop open:  stays 0° → points right (flyout cue)
                                            --}}
                                            <svg
                                                class="w-4 h-4 ml-2 flex-shrink-0 transition-transform duration-200"
                                                :class="subopen ? 'rotate-90 md:rotate-0' : ''"
                                                aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                            >
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                                            </svg>
                                        </button>

                                        {{--
                                            Level 3 panel
                                            Mobile:  w-full, inline, indented with pl-4
                                            Desktop: absolute flyout to the right
                                        --}}
                                        <div
                                            x-show="subopen"
                                            x-cloak
                                            class="w-full pl-4 md:pl-0 md:absolute md:left-full md:top-0 md:z-20 md:w-48 bg-white dark:bg-gray-700 rounded-lg shadow-lg border border-gray-100 dark:border-gray-600"
                                            role="menu"
                                            aria-label="{{ $childItem->getDisplayTitle() }} submenu"
                                        >
                                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-400" role="none">
                                                @foreach($childItem->children as $grandchildItem)
                                                    @if($optimized || $grandchildItem->userCanSee())
                                                        @if($grandchildItem->type === 'divider')
                                                            <li class="border-t border-gray-200 dark:border-gray-600 mx-2 my-1" role="separator"></li>
                                                        @elseif($grandchildItem->type === 'heading')
                                                            <li role="presentation">
                                                                <span class="block px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide dark:text-gray-400">
                                                                    {{ $grandchildItem->getDisplayTitle() }}
                                                                </span>
                                                            </li>
                                                        @else
                                                            <li role="none">
                                                                <a href="{{ $grandchildItem->getFullUrl() }}"
                                                                   @if($grandchildItem->opens_new_tab) target="_blank" @else wire:navigate @endif
                                                                   class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white {{ $grandchildItem->isCurrentlyActive() ? 'text-blue-700 dark:text-blue-500' : '' }}"
                                                                   role="menuitem"
                                                                   @if($grandchildItem->opens_new_tab) aria-label="{{ $grandchildItem->getDisplayTitle() }} (opens in new tab)" @endif>
                                                                    {{ $grandchildItem->getDisplayTitle() }}
                                                                    @if($grandchildItem->opens_new_tab)
                                                                        <span class="sr-only">(opens in new tab)</span>
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </li>
                                @else
                                    <li role="none">
                                        <a href="{{ $childItem->getFullUrl() }}"
                                           @if($childItem->opens_new_tab) target="_blank" @else wire:navigate @endif
                                           class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white {{ $childItem->isCurrentlyActive() ? 'text-blue-700 dark:text-blue-500' : '' }}"
                                           role="menuitem"
                                           @if($childItem->opens_new_tab) aria-label="{{ $childItem->getDisplayTitle() }} (opens in new tab)" @endif>
                                            {{ $childItem->getDisplayTitle() }}
                                            @if($childItem->opens_new_tab)
                                                <span class="sr-only">(opens in new tab)</span>
                                            @endif
                                        </a>
                                    </li>
                                @endif
                            @endif
                        @endforeach
                    </ul>
                </div>
            </li>
        @else
            <!-- Dropdown without children, treat as regular link -->
            <x-menu-nav-item
                :href="$url"
                :active="$isActive"
                :target="$target"
            >
                {{ $item->getDisplayTitle() }}
            </x-menu-nav-item>
        @endif
        @break

    @case('external')
    @case('link')
    @default
        <x-menu-nav-item
            :href="$url"
            :active="$isActive"
            :target="$target"
        >
            {{ $item->getDisplayTitle() }}
        </x-menu-nav-item>
        @break
@endswitch
