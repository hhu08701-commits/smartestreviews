@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900" style="font-family: 'Montserrat', sans-serif;">Categories</h2>
            <p class="mt-1 text-sm text-gray-600">Manage content categories.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" 
           class="bg-[#f8c2eb] text-black px-4 py-2 rounded-md hover:bg-[#e8a8d8] transition-colors font-semibold"
           style="font-family: 'Montserrat', sans-serif;">
            Add Category
        </a>
    </div>
</div>

<div>
    @if($categories->count() > 0)
        <!-- Card grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($categories as $category)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white font-semibold mr-4" style="background-color: {{ $category->color }}">
                                @if($category->icon)
                                    <i class="{{ $category->icon }}"></i>
                                @else
                                    {{ strtoupper(substr($category->name, 0, 2)) }}
                                @endif
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                                @if($category->description)
                                    <p class="text-sm text-gray-500 line-clamp-2">{{ $category->description }}</p>
                                @endif
                            </div>
                        </div>
                        <span class="text-xs px-2.5 py-1 rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
                        <div class="flex items-center space-x-4">
                            <span class="flex items-center"><i class="fa-regular fa-file mr-1"></i>{{ $category->posts_count }} posts</span>
                            <span class="flex items-center"><i class="fa-solid fa-sort mr-1"></i>Order: {{ $category->sort_order }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-flex items-center text-xs text-gray-600">
                                <span class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $category->color }}"></span>
                                {{ $category->color }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end space-x-2">
                        <a href="{{ route('admin.categories.show', $category) }}" class="px-3 py-2 text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50">View</a>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="px-3 py-2 text-sm rounded-md bg-[#f8c2eb] text-black hover:bg-[#e8a8d8] font-semibold">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-2 text-sm rounded-md bg-red-600 text-white hover:bg-red-700">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No categories</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new category.</p>
            <div class="mt-6">
                <a href="{{ route('admin.categories.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-black bg-[#f8c2eb] hover:bg-[#e8a8d8] font-semibold"
                   style="font-family: 'Montserrat', sans-serif;">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Category
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
