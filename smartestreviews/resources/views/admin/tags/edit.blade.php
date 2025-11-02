@extends('layouts.admin')

@section('title', 'Edit Tag')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900" style="font-family: 'Montserrat', sans-serif;">Edit Tag</h2>
    <p class="mt-1 text-sm text-gray-600">Update tag information.</p>
</div>

<form method="POST" action="{{ route('admin.tags.update', $tag) }}" class="space-y-6">
    @csrf
    @method('PUT')
    
    <!-- Basic Information -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4" style="font-family: 'Montserrat', sans-serif;">Basic Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $tag->name) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $tag->slug) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm"
                       placeholder="auto-generated-if-empty">
                <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate from name</p>
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="3"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">{{ old('description', $tag->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Settings -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4" style="font-family: 'Montserrat', sans-serif;">Settings</h3>
        
        <div class="space-y-4">
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $tag->is_active) ? 'checked' : '' }}
                       class="h-4 w-4 text-[#f8c2eb] focus:ring-[#f8c2eb] border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    Active
                </label>
            </div>
        </div>
    </div>

    <!-- Post Count Info -->
    @if($tag->posts()->count() > 0)
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <p class="text-sm text-blue-800">
            <strong>Note:</strong> This tag is currently used by {{ $tag->posts()->count() }} {{ Str::plural('post', $tag->posts()->count()) }}.
            {{ $tag->posts()->count() > 0 ? 'You cannot delete it until all posts are unlinked.' : '' }}
        </p>
    </div>
    @endif

    <!-- Actions -->
    <div class="flex justify-end space-x-3">
        <a href="{{ route('admin.tags.index') }}" 
           class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors font-semibold"
           style="font-family: 'Montserrat', sans-serif;">
            Cancel
        </a>
        <button type="submit" 
                class="bg-[#f8c2eb] text-black px-4 py-2 rounded-md hover:bg-[#e8a8d8] transition-colors font-semibold"
                style="font-family: 'Montserrat', sans-serif;">
            Update Tag
        </button>
    </div>
</form>
@endsection

