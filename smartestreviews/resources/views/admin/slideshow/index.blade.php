@extends('layouts.admin')

@section('title', 'Slideshow Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900" style="font-family: 'Montserrat', sans-serif;">Slideshow</h2>
            <p class="mt-1 text-sm text-gray-600">Quản lý slideshow hiển thị trên trang chủ</p>
        </div>
        <a href="{{ route('admin.slideshow.create') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-[#f8c2eb] hover:bg-[#e8a8d8] font-semibold transition-colors"
           style="font-family: 'Montserrat', sans-serif;">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Slide
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Stats Cards -->
    @php
        $totalSlides = \App\Models\SlideshowSlide::count();
        $activeSlides = \App\Models\SlideshowSlide::where('is_active', true)->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-xs text-gray-500 font-medium">Total Slides</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalSlides }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-xs text-gray-500 font-medium">Active</p>
            <p class="text-2xl font-bold text-green-600">{{ $activeSlides }}</p>
        </div>
    </div>

    <!-- Slides Grid -->
    <div class="bg-white shadow rounded-lg p-6">
        @if($slides->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($slides as $slide)
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        @if($slide->image)
                            <img src="{{ $slide->image }}" 
                                 alt="{{ $slide->title }}"
                                 class="w-full h-48 object-cover"
                                 onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div style="width: 100%; height: 192px; background: #f0f0f0; display: none; align-items: center; justify-content: center;">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            </div>
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                @if($slide->category)
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                        {{ $slide->category }}
                                    </span>
                                @else
                                    <span></span>
                                @endif
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $slide->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $slide->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 mb-2" style="font-family: 'Montserrat', sans-serif;">{{ $slide->title }}</h3>
                            
                            @if($slide->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $slide->description }}</p>
                            @endif

                            <div class="flex items-center justify-between text-sm text-gray-600 mb-4 border-t border-gray-200 pt-4">
                                <span>Sort: {{ $slide->sort_order }}</span>
                                @if($slide->url)
                                    <a href="{{ $slide->url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @endif
                            </div>

                            <div class="flex justify-end space-x-3 border-t border-gray-200 pt-4">
                                <a href="{{ route('admin.slideshow.edit', $slide) }}" 
                                   class="text-[#f8c2eb] hover:text-[#e8a8d8] text-sm font-semibold">Edit</a>
                                <form action="{{ route('admin.slideshow.destroy', $slide) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this slide?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-semibold">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($slides->hasPages())
            <div class="mt-6 border-t border-gray-200 pt-4">
                {{ $slides->links() }}
            </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No slides</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new slide.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.slideshow.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-black bg-[#f8c2eb] hover:bg-[#e8a8d8] font-semibold"
                       style="font-family: 'Montserrat', sans-serif;">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Slide
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
