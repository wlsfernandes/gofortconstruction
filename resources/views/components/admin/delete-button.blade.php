@props(['action', 'itemName'])

<form method="POST" action="{{ $action }}" x-data x-on:submit="if (! confirm(@js('Delete '.$itemName.'? This cannot be undone.'))) $event.preventDefault()">
    @csrf
    @method('DELETE')
    <button type="submit" {{ $attributes->merge(['class' => 'text-sm font-medium text-red-600 hover:underline']) }}>Delete</button>
</form>
