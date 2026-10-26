{{-- Menu Module: Navigation Item Component --}}
{{-- Used for rendering individual menu items with proper styling and behavior --}}

@props([
    "href" => route("home"),
    "active" => "",
    "target" => "_self",
    "icon" => null,
    "label" => null,
])

<?php
$active_classes = "border-transparent dark:border-transparent";

if ($active) {
    $active_classes =
        "rounded-sm border-gray-700 bg-gray-200 hover:opacity-75 dark:border-gray-300 dark:bg-gray-700 sm:rounded-none sm:bg-transparent dark:sm:rounded-none dark:sm:bg-transparent";
}

$shouldNavigate = $target === '_self';
$iconOnly = $icon && $slot->isEmpty();
$accessibleLabel = $label ?? ($iconOnly ? '' : null);
?>

<li>
    <a
        class="{{ $active_classes }} block border-b-2 px-3 py-2 font-semibold text-gray-800 transition duration-200 ease-in hover:border-gray-700 hover:opacity-75 dark:text-white dark:hover:border-gray-300 dark:hover:opacity-75 sm:my-0 sm:py-1"
        href="{{ $href }}"
        target="{{ $target }}"
        @if($shouldNavigate) wire:navigate @endif
        @if($target === '_blank')
            aria-label="{{ $iconOnly ? ($accessibleLabel ?: '') : ($slot . ' (opens in new tab)') }}"
        @elseif($iconOnly && $accessibleLabel)
            aria-label="{{ $accessibleLabel }}"
        @endif
    >
        @if($icon)
            <i class="{{ $icon }}" aria-hidden="true"></i>
        @endif
        {{ $slot }}
        @if($target === '_blank' && !$iconOnly)
            <span class="sr-only">(opens in new tab)</span>
        @endif
    </a>
</li>
