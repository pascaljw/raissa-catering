@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-3 border-1.5 border-gray-300 rounded-md text-gray-900 placeholder-gray-400 focus:border-primary focus:ring-primary/20 focus:ring-2 transition ease-in-out duration-150']) }}>
