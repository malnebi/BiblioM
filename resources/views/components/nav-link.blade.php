@props(['active' => false])


<a class="{{ $active ? 'bg-gray-600 text-white': 'text-gray-300 hover:bg-gray-700 hover:text-white'}} 
rounded-md px-3 py-2 text-md font-semibold dark:text-gray-900 dark:hover:text-white mr-3" 
   aria-current= "{{ $active ? 'page': 'false' }}"

    {{$attributes}}

    > {{ $slot }}</a>


    
    
