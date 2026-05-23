@extends('layouts.admin')
@section('title', 'Edit Halaman Tentang Kami')

@section('content')
<div class="p-6">
    <div class="card p-6">
        <h1 class="text-2xl font-semibold text-charcoal mb-4">Edit Halaman Tentang Kami</h1>
        <form action="{{ route('admin.pages.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="grid gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="title">Judul Halaman</label>
                    <input id="title" name="title" type="text" value="{{ old('title', $page->title) }}" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary/30" required>
                    @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="subtitle">Ringkasan / Subtitle</label>
                    <textarea id="subtitle" name="subtitle" rows="3" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary/30">{{ old('subtitle', $page->subtitle) }}</textarea>
                    @error('subtitle')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="body">Isi Konten</label>
                    <textarea id="body" name="body" rows="12" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary/30">{{ old('body', $bodyForEdit) }}</textarea>
                    <p class="mt-2 text-sm text-gray-500">Masukkan teks biasa. Baris baru akan otomatis menjadi paragraf saat ditampilkan.</p>
                    @error('body')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
