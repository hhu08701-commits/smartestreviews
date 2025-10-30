@extends('layouts.admin')

@section('title', 'Create Affiliate Link')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Create New Affiliate Link</h1>
        <a href="{{ route('admin.affiliate-links.index') }}" class="btn-secondary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Links
        </a>
    </div>

    <form action="{{ route('admin.affiliate-links.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="label" class="block text-sm font-medium text-gray-700">Link Label *</label>
                    <input type="text" name="label" id="label" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                           value="{{ old('label') }}" required>
                    @error('label')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="url" class="block text-sm font-medium text-gray-700">Affiliate URL *</label>
                    <input type="url" name="url" id="url" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                           value="{{ old('url') }}" required>
                    @error('url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="merchant" class="block text-sm font-medium text-gray-700">Merchant</label>
                    <input type="text" name="merchant" id="merchant" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                           value="{{ old('merchant') }}">
                    @error('merchant')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="rel" class="block text-sm font-medium text-gray-700">Rel Attribute</label>
                    <select name="rel" id="rel" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">None</option>
                        <option value="sponsored nofollow" {{ old('rel') == 'sponsored nofollow' ? 'selected' : '' }}>sponsored nofollow</option>
                        <option value="nofollow" {{ old('rel') == 'nofollow' ? 'selected' : '' }}>nofollow</option>
                        <option value="sponsored" {{ old('rel') == 'sponsored' ? 'selected' : '' }}>sponsored</option>
                    </select>
                    @error('rel')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Association</h3>
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="post_id" class="block text-sm font-medium text-gray-700">Associated Post</label>
                    <select name="post_id" id="post_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Select a post (optional)</option>
                        @foreach($posts as $post)
                            <option value="{{ $post->id }}" {{ old('post_id') == $post->id ? 'selected' : '' }}>
                                {{ $post->title }} ({{ ucfirst($post->post_type) }})
                            </option>
                        @endforeach
                    </select>
                    @error('post_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700">Associated Product</label>
                    <select name="product_id" id="product_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Select a product (optional)</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} - {{ $product->brand }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Settings</h3>
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="utm_params" class="block text-sm font-medium text-gray-700">UTM Parameters</label>
                    <textarea name="utm_params" id="utm_params" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                              placeholder="utm_source=smartestreviews&utm_medium=affiliate&utm_campaign=product_review">{{ old('utm_params') }}</textarea>
                    @error('utm_params')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="enabled" id="enabled" value="1" 
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" 
                           {{ old('enabled', true) ? 'checked' : '' }}>
                    <label for="enabled" class="ml-2 block text-sm text-gray-900">
                        Enable this affiliate link
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('admin.affiliate-links.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Create Affiliate Link
            </button>
        </div>
    </form>
@endsection
