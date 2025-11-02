@extends('layouts.admin')

@section('title', 'Sources Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900" style="font-family: 'Montserrat', sans-serif;">Sources</h2>
            <p class="mt-1 text-sm text-gray-600">Quản lý "Our Sources" section trong sidebar</p>
        </div>
        <a href="{{ route('admin.sources.create') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-[#f8c2eb] hover:bg-[#e8a8d8] font-semibold transition-colors"
           style="font-family: 'Montserrat', sans-serif;">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Source
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Stats Cards -->
    @php
        $totalSources = \App\Models\Source::count();
        $activeSources = \App\Models\Source::where('is_active', true)->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-xs text-gray-500 font-medium">Total Sources</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalSources }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-xs text-gray-500 font-medium">Active</p>
            <p class="text-2xl font-bold text-green-600">{{ $activeSources }}</p>
        </div>
    </div>

    <!-- Sources Grid -->
    <div class="bg-white shadow rounded-lg p-6">
        @if($sources->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($sources as $source)
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white text-lg font-semibold mr-3 flex-shrink-0" 
                                     style="background-color: {{ $source->color }}">
                                    @if(strpos($source->icon ?? '', 'fa-') === 0 || strpos($source->icon ?? '', 'fas ') === 0)
                                        <i class="{{ $source->icon }}"></i>
                                    @else
                                        {{ $source->icon ?? substr($source->name, 0, 1) }}
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-bold text-gray-900" style="font-family: 'Montserrat', sans-serif;">{{ $source->name }}</h3>
                                    @if($source->description)
                                        <p class="text-sm text-gray-600 line-clamp-1">{{ $source->description }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-sm mb-4 border-t border-gray-200 pt-4">
                                <div class="flex items-center">
                                    <span class="font-medium text-gray-600 mr-2">Color:</span>
                                    <div class="w-5 h-5 rounded-full border border-gray-300" style="background-color: {{ $source->color }}"></div>
                                    <span class="ml-2 text-gray-700 text-xs">{{ $source->color }}</span>
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
                                   class="text-[#f8c2eb] hover:text-[#e8a8d8] text-sm font-semibold">Edit</a>
                                <form action="{{ route('admin.sources.destroy', $source) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this source?');">
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
            @if($sources->hasPages())
            <div class="mt-6 border-t border-gray-200 pt-4">
                {{ $sources->links() }}
            </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No sources</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new source.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.sources.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-black bg-[#f8c2eb] hover:bg-[#e8a8d8] font-semibold"
                       style="font-family: 'Montserrat', sans-serif;">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Source
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
