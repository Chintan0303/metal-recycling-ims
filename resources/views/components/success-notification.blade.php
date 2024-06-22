@props(['on'])

<div 
     x-data="{ shown: false, timeout: null }"
     x-init="@this.on('{{ $on }}', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000); })"
     x-show.transition.out.opacity.duration.1500ms="shown"
     x-transition:leave.opacity.duration.1500ms
     class="mx-auto mb-4"
     style="display: none;"
>
    <div class="bg-green-500 text-white  py-2 px-4 rounded-md shadow-md flex items-center justify-between">
        <span class="mr-2" >{{ $slot->isEmpty() ? 'Saved.' : $slot  }}</span>
        <button @click.prevent="shown = !shown" class="text-white text-2xl">&times;</button>
    </div>
</div>