@extends('layouts.admin')

@section('title', 'Hot Products Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900" style="font-family: 'Montserrat', sans-serif;">Hot Products</h2>
            <p class="mt-1 text-sm text-gray-600">Quản lý sản phẩm trending hiển thị trong "TRENDING NOW" sidebar</p>
        </div>
        <a href="{{ route('admin.hot-products.create') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-[#f8c2eb] hover:bg-[#e8a8d8] font-semibold transition-colors"
           style="font-family: 'Montserrat', sans-serif;">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Hot Product
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Stats Cards -->
    @php
        $totalProducts = \App\Models\HotProduct::count();
        $activeProducts = \App\Models\HotProduct::where('is_active', true)->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-xs text-gray-500 font-medium">Total Products</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-xs text-gray-500 font-medium">Active</p>
            <p class="text-2xl font-bold text-green-600">{{ $activeProducts }}</p>
        </div>
    </div>

    <!-- Hot Products Grid -->
    <div class="bg-white shadow rounded-lg p-6">
        @if($hotProducts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($hotProducts as $product)
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        @if($product->image)
                            @php
                                $imageUrl = $product->image;
                                if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                                    $imageUrl = asset($product->image);
                                }
                            @endphp
                            <img src="{{ $imageUrl }}" 
                                 alt="{{ $product->name }}"
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
                                <span class="text-sm font-semibold text-gray-700">{{ $product->brand }}</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2" style="font-family: 'Montserrat', sans-serif;">{{ $product->name }}</h3>
                            
                            <div class="flex items-center space-x-4 mb-4">
                                @if($product->rating && $product->rating > 0)
                                <div class="flex items-center">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    <span class="text-sm font-medium text-gray-700">{{ number_format($product->rating, 1) }}</span>
                                </div>
                                @endif
                                @if($product->trending)
                                <div class="flex items-center">
                                    <i class="fas fa-fire text-red-500 mr-1"></i>
                                    <span class="text-sm font-medium text-red-600">+{{ $product->trending }}%</span>
                                </div>
                                @endif
                            </div>

                            @if($product->price)
                            <p class="text-xl font-bold text-blue-600 mb-4">{{ $product->price }}</p>
                            @endif

                            <div class="flex items-center justify-between text-sm mb-4 border-t border-gray-200 pt-4">
                                <form action="{{ route('admin.hot-products.update-sort', $product) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    @method('PATCH')
                                    <label for="sort_order_{{ $product->id }}" class="text-gray-600 whitespace-nowrap font-medium">Thứ hạng:</label>
                                    <input type="number" 
                                           name="sort_order" 
                                           id="sort_order_{{ $product->id }}" 
                                           value="{{ $product->sort_order }}" 
                                           min="0"
                                           class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-[#f8c2eb] focus:border-[#f8c2eb]"
                                           onchange="this.form.submit();"
                                           title="Nhập số để sắp xếp (số nhỏ hơn hiển thị trước)">
                                </form>
                                @if($product->url)
                                    <a href="{{ $product->url }}" target="_blank" class="text-blue-600 hover:text-blue-800" title="Xem link">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @endif
                            </div>

                            <div class="flex justify-end space-x-3 border-t border-gray-200 pt-4">
                                <a href="{{ route('admin.hot-products.edit', $product) }}" 
                                   class="text-[#f8c2eb] hover:text-[#e8a8d8] text-sm font-semibold">Edit</a>
                                <form action="{{ route('admin.hot-products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?');">
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
            @if($hotProducts->hasPages())
            <div class="mt-6 border-t border-gray-200 pt-4">
                {{ $hotProducts->links() }}
            </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hot products</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new hot product.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.hot-products.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-black bg-[#f8c2eb] hover:bg-[#e8a8d8] font-semibold"
                       style="font-family: 'Montserrat', sans-serif;">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Hot Product
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
