@props([
    'words' => [],
    'duration' => 3000,
    'class' => '',
])

<span
    class="flip-words-wrapper relative inline-block {{ $class }}"
    data-words='@json($words)'
    data-duration="{{ $duration }}"
></span>
