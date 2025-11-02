@extends('layouts.admin')

@section('title', 'Breaking News Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900" style="font-family: 'Montserrat', sans-serif;">Breaking News</h2>
            <p class="mt-1 text-sm text-gray-600">Quản lý tin tức hiển thị trong phần Breaking News marquee ở đầu trang</p>
        </div>
        <a href="{{ route('admin.breaking-news.create') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-[#f8c2eb] hover:bg-[#e8a8d8] font-semibold transition-colors"
           style="font-family: 'Montserrat', sans-serif;">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Breaking News
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <p class="text-xs text-gray-500 font-medium">Total Items</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-xs text-gray-500 font-medium">Active</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-gray-500">
            <p class="text-xs text-gray-500 font-medium">Inactive</p>
            <p class="text-2xl font-bold text-gray-600">{{ $stats['inactive'] }}</p>
        </div>
    </div>

    <!-- Breaking News Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($breakingNews->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link/Post</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sort Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($breakingNews as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->image_url)
                                    @php
                                        $imageUrl = $item->image_url;
                                        if (!filter_var($imageUrl, FILTER_VALIDATE_URL) && !str_starts_with($imageUrl, '/')) {
                                            $imageUrl = asset($item->image_url);
                                        } elseif (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                                            $imageUrl = asset($item->image_url);
                                        }
                                    @endphp
                                    <img src="{{ $imageUrl }}" 
                                         alt="{{ $item->title }}"
                                         class="w-16 h-16 object-cover rounded"
                                         onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div style="width: 64px; height: 64px; background: #f0f0f0; display: none; align-items: center; justify-content: center; border-radius: 4px;">
                                        <i class="fas fa-image text-gray-400 text-xl"></i>
                                    </div>
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-xl"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900" style="font-family: 'Montserrat', sans-serif;">
                                    {{ Str::limit($item->title, 60) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->affiliateLink)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                        <i class="fas fa-tag mr-1"></i>
                                        {{ Str::limit($item->affiliateLink->label, 25) }}
                                    </span>
                                @elseif($item->post)
                                    <span class="text-xs text-blue-600">Post: {{ Str::limit($item->post->title, 30) }}</span>
                                @elseif($item->link)
                                    <a href="{{ $item->link }}" target="_blank" class="text-xs text-blue-600 hover:underline">
                                        {{ Str::limit($item->link, 40) }}
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">No link</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->sort_order }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.breaking-news.edit', $item) }}" 
                                       class="inline-flex items-center px-2 py-1 text-xs font-medium rounded text-white bg-[#f8c2eb] hover:bg-[#e8a8d8] transition-colors"
                                       title="Sửa">
                                        <i class="fas fa-edit mr-1"></i>
                                        Sửa
                                    </a>
                                    <form action="{{ route('admin.breaking-news.update', $item) }}" 
                                          method="POST" 
                                          class="inline"
                                          id="toggle-form-{{ $item->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_active" value="{{ $item->is_active ? 0 : 1 }}">
                                        <button type="submit" 
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded transition-colors {{ $item->is_active ? 'text-yellow-700 bg-yellow-100 hover:bg-yellow-200' : 'text-green-700 bg-green-100 hover:bg-green-200' }}"
                                                title="{{ $item->is_active ? 'Tắt hiển thị' : 'Bật hiển thị' }}">
                                            <i class="fas {{ $item->is_active ? 'fa-eye-slash' : 'fa-eye' }} mr-1"></i>
                                            {{ $item->is_active ? 'Tắt' : 'Bật' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.breaking-news.destroy', $item) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa Breaking News này?');"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700 transition-colors"
                                                title="Xóa">
                                            <i class="fas fa-trash mr-1"></i>
                                            Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $breakingNews->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                    <div class="flex items-center justify-center mb-3">
                        <i class="fas fa-info-circle text-yellow-600 text-2xl mr-2"></i>
                        <h3 class="text-lg font-semibold text-yellow-800" style="font-family: 'Montserrat', sans-serif;">Chưa có Breaking News</h3>
                    </div>
                    <p class="text-yellow-700 text-sm mb-2">
                        <strong>Trạng thái hiện tại:</strong>
                    </p>
                    <ul class="text-yellow-700 text-sm text-left max-w-md mx-auto space-y-1">
                        <li>• Trên trang client vẫn hiển thị Breaking News marquee (đang dùng Latest Posts làm fallback)</li>
                        <li>• Khi bạn tạo Breaking News và đánh dấu "Active", chúng sẽ thay thế Latest Posts trên client</li>
                        <li>• Breaking News có thể link đến Post cụ thể hoặc URL tùy chỉnh</li>
                    </ul>
                </div>
                
                <i class="fas fa-newspaper text-gray-400 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg font-medium" style="font-family: 'Montserrat', sans-serif;">Chưa có Breaking News Items</p>
                <p class="text-gray-400 text-sm mt-2">Bắt đầu bằng cách tạo Breaking News item đầu tiên</p>
                <div class="mt-6 flex justify-center gap-3">
                    <a href="{{ route('admin.breaking-news.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-[#f8c2eb] hover:bg-[#e8a8d8] font-semibold transition-colors"
                       style="font-family: 'Montserrat', sans-serif;">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tạo Breaking News
                    </a>
                    <a href="{{ route('admin.posts.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 font-semibold transition-colors"
                       style="font-family: 'Montserrat', sans-serif;">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Xem Posts
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

