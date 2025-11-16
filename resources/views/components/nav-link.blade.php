@props(['active' => false])


<a class="{{ $active ? ' text-blue-800': 'text-white hover:text-blue-800' }} 
rounded-md px-3 py-2 text-md font-semibold hover:text-blue-800 border border-transparent hover:border-blue-800 group  transition-colors duration-300 mr-3" 
   aria-current= "{{ $active ? 'page': 'false' }}"

    {{$attributes}}

    > {{ $slot }}</a>


    
    
