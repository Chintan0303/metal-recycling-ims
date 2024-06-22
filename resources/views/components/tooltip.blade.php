<div x-data="{ show: false }" @mouseover="show = true" @mouseleave="show = false" {{ $attributes }}>
    {{ $slot }}
    <div x-show="show" class="transition-opacity -translate-x-1/2 translate-y-full  absolute 
                            z-10 bg-gray-800 text-white  
                            py-1 px-1 m-4 mx-auto text-sm rounded-md shadow-lg left-16 bottom-1/3">
        {{ $content }}
    </div>
</div>