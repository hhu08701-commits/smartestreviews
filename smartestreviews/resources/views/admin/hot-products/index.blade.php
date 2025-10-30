@extends('layouts.admin')

@section('title', 'Hot Products Management')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Hot Products</h2>
            <p class="mt-1 text-sm text-gray-600">Manage hot/t trending products</p>
        </div>
        <a href="{{ route('admin.hot-products.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
            Add Hot Product
        </a>
    </div>
</div>

@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if($hotProducts->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($hotProducts as $product)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition-shadow">
                @if($product->image)
                    <img src="{{ $product->image }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-48 object-cover">
                @endif
                
                <div class="p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-gray-700">{{ $product->brand }}</span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $product->name }}</h3>
                    
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <span class="text-sm font-medium text-gray-700">{{ number_format($product->rating, 1) }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-fire text-red-500 mr-1"></i>
                            <span class="text-sm font-medium text-red-600">+{{ $product->trending }}%</span>
                        </div>
                    </div>

                    <p class="text-xl font-bold text-blue-600 mb-4">{{ $product->price }}</p>

                    <div class="flex items-center justify-between text-sm mb-4">
                        <form action="{{ route('admin.hot-products.update-sort', $product) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            @method('PATCH')
                            <label for="sort_order_{{ $product->id }}" class="text-gray-600 whitespace-nowrap">Thứ hạng:</label>
                            <input type="number" 
                                   name="sort_order" 
                                   id="sort_order_{{ $product->id }}" 
                                   value="{{ $product->sort_order }}" 
                                   min="0"
                                   class="w-16 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500"
                                   onchange="this.form.submit();"
                                   title="Nhập số để sắp xếp (số nhỏ hơn hiển thị trước)">
                            <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 ml-1" title="Cập nhật thứ hạng">
                                <i class="fas fa-save"></i>
                            </button>
                        </form>
                        @if($product->url)
                            <a href="{{ $product->url }}" target="_blank" class="text-blue-600 hover:text-blue-800" title="Xem link">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        @endif
                    </div>

                    <div class="flex justify-end space-x-3 border-t border-gray-200 pt-4">
                        <a href="{{ route('admin.hot-products.edit', $product) }}" 
                           class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                        <form action="{{ route('admin.hot-products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?');">
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
        {{ $hotProducts->links() }}
    </div>
@else
    <div class="text-center py-12 bg-white rounded-lg shadow-lg">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No hot products</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by creating a new hot product.</p>
        <div class="mt-6">
            <a href="{{ route('admin.hot-products.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                Add Hot Product
            </a>
        </div>
    </div>
@endif
@endsection

