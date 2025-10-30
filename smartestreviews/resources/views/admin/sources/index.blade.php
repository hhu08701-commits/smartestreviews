@extends('layouts.admin')

@section('title', 'Sources Management')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Sources</h2>
            <p class="mt-1 text-sm text-gray-600">Manage "Our Sources" section</p>
        </div>
        <a href="{{ route('admin.sources.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
            Add Source
        </a>
    </div>
</div>

@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if($sources->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($sources as $source)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition-shadow">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white text-lg font-semibold mr-3" 
                             style="background-color: {{ $source->color }}">
                            @if(strpos($source->icon ?? '', 'fa-') === 0 || strpos($source->icon ?? '', 'fas ') === 0)
                                <i class="{{ $source->icon }}"></i>
                            @else
                                {{ $source->icon ?? substr($source->name, 0, 1) }}
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900">{{ $source->name }}</h3>
                            @if($source->description)
                                <p class="text-sm text-gray-600 line-clamp-1">{{ $source->description }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm mb-4">
                        <div class="flex items-center">
                            <span class="font-medium text-gray-600 mr-2">Color:</span>
                            <div class="w-5 h-5 rounded-full border border-gray-300" style="background-color: {{ $source->color }}"></div>
                            <span class="ml-2 text-gray-700">{{ $source->color }}</span>
                        </div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $source->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $source->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                        <span>Sort: {{ $source->sort_order }}</span>
                        @if($source->url)
                            <a href="{{ $source->url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        @endif
                    </div>

                    <div class="flex justify-end space-x-3 border-t border-gray-200 pt-4">
                        <a href="{{ route('admin.sources.edit', $source) }}" 
                           class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                        <form action="{{ route('admin.sources.destroy', $source) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this source?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="mt-8">
        {{ $sources->links() }}
    </div>
@else
    <div class="text-center py-12 bg-white rounded-lg shadow-lg">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No sources</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by creating a new source.</p>
        <div class="mt-6">
            <a href="{{ route('admin.sources.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                Add Source
            </a>
        </div>
    </div>
@endif
@endsection

