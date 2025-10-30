@extends('layouts.admin')

@section('title', 'Slideshow Management')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Slideshow</h2>
            <p class="mt-1 text-sm text-gray-600">Manage slideshow slides</p>
        </div>
        <a href="{{ route('admin.slideshow.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
            Add Slide
        </a>
    </div>
</div>

@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if($slides->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($slides as $slide)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition-shadow">
                @if($slide->image)
                    <img src="{{ $slide->image }}" 
                         alt="{{ $slide->title }}"
                         class="w-full h-48 object-cover">
                @endif
                
                <div class="p-6">
                    <div class="flex items-center justify-between mb-2">
                        @if($slide->category)
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                {{ $slide->category }}
                            </span>
                        @endif
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $slide->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $slide->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $slide->title }}</h3>
                    
                    @if($slide->description)
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $slide->description }}</p>
                    @endif

                    <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                        <span>Sort: {{ $slide->sort_order }}</span>
                        @if($slide->url)
                            <a href="{{ $slide->url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        @endif
                    </div>

                    <div class="flex justify-end space-x-3 border-t border-gray-200 pt-4">
                        <a href="{{ route('admin.slideshow.edit', $slide) }}" 
                           class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                        <form action="{{ route('admin.slideshow.destroy', $slide) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this slide?');">
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
        {{ $slides->links() }}
    </div>
@else
    <div class="text-center py-12 bg-white rounded-lg shadow-lg">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No slides</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by creating a new slide.</p>
        <div class="mt-6">
            <a href="{{ route('admin.slideshow.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                Add Slide
            </a>
        </div>
    </div>
@endif
@endsection

