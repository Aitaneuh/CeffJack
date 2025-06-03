@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-[#4B5563] dark:bg-[#383838] dark:text-[#ffffff] focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm']) }}>
